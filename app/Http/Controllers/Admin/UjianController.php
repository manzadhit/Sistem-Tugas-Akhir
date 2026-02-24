<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ujian;
use App\Models\DokumenUjian;
use App\Models\UndanganUjian;
use App\Services\Admin\UndanganPdfService;
use Illuminate\Support\Facades\Storage;
use App\Models\ProfileDosen;

class UjianController extends Controller
{
    public function __construct(private UndanganPdfService $pdfService)
    {
    }

    public function index($jenis)
    {
        $ujians = Ujian::with([
            'tugasAkhir.mahasiswa',
            'dokumenUjian' => function ($q) {
                $q->where('kategori', 'syarat')->where('status', 'pending');
            }
        ])
            ->where('jenis_ujian', $jenis)
            ->whereIn('status', ['menunggu_verifikasi', 'menunggu_undangan'])
            ->get();

        return view('admin.ujian.list-mahasiswa', compact('ujians', 'jenis'));
    }

    public function detailVerifikasi($jenis, $id)
    {
        $ujian = Ujian::with([
            'tugasAkhir.mahasiswa.dosenPembimbing.dosen',
            'tugasAkhir.mahasiswa.dosenPenguji.dosen',
            'dokumenUjian' => fn($q) => $q->where('kategori', 'syarat'),
            'jadwalUjian',
        ])
            ->where('jenis_ujian', $jenis)
            ->where('id', $id)
            ->first();

        return view('admin.ujian.detail-verifikasi', compact('ujian', 'jenis'));
    }

    public function prosesVerifikasi(Request $request, $jenis, $id)
    {
        $request->validate([
            'dokumen.*.status' => 'required|in:acc,tolak',
            'dokumen.*.catatan' => 'nullable|string|max:500',
        ]);

        $ujian = Ujian::where('jenis_ujian', $jenis)->findOrFail($id);

        $adaTolak = false;

        foreach ($request->input('dokumen', []) as $dokumenId => $data) {
            DokumenUjian::where('id', $dokumenId)
                ->where('ujian_id', $ujian->id)
                ->update([
                    'status' => $data['status'],
                    'catatan' => $data['catatan'] ?? null,
                ]);

            if ($data['status'] === 'tolak') {
                $adaTolak = true;
            }
        }

        if ($adaTolak) {
            $ujian->update(['status' => 'revisi']);

            return redirect()
                ->back()
                ->with('warning', 'Beberapa berkas ditolak. Pengajuan dikembalikan ke mahasiswa.');
        }

        $ujian->update(['status' => 'menunggu_undangan']);

        return redirect()
            ->route('admin.ujian.undangan', [$jenis, $ujian->id])
            ->with('success', 'Semua berkas di-ACC. Silakan buat undangan ujian.');
    }

    public function showUndangan($jenis, $id)
    {
        $ujian = Ujian::with([
            'tugasAkhir.mahasiswa.dosenPembimbing.dosen',
            'tugasAkhir.mahasiswa.dosenPenguji.dosen',
            'jadwalUjian',
            'undanganUjian',
        ])
            ->where('jenis_ujian', $jenis)
            ->where('id', $id)
            ->first();

        $ketuaSidang = $ujian->tugasAkhir->mahasiswa->dosenPenguji
            ->where('jenis_penguji', 'penguji_1')->first()?->dosen;

        $ketuaJurusan = ProfileDosen::whereHas('user', function ($q) {
            $q->where('role', 'kajur');
        })->first();

        $sekretarisJurusan = ProfileDosen::whereHas('user', function ($q) {
            $q->where('role', 'sekjur');
        })->first();

        return view('admin.ujian.undangan', compact('ujian', 'jenis', 'ketuaJurusan', 'sekretarisJurusan', 'ketuaSidang'));
    }

    public function storeUndangan(Request $request, $jenis, $id)
    {
        $request->validate([
            'nomor_surat' => 'required|string|max:100',
            'hal' => 'required|string|max:255',
            'tanggal_surat' => 'required|date',
            'ketua_sidang_id' => 'nullable|exists:profile_dosen,id',
            'sekretaris_sidang_id' => 'nullable|exists:profile_dosen,id',
        ]);

        $ujian = Ujian::with([
            'tugasAkhir.mahasiswa.dosenPembimbing.dosen',
            'tugasAkhir.mahasiswa.dosenPenguji.dosen',
            'jadwalUjian',
        ])
            ->where('jenis_ujian', $jenis)
            ->findOrFail($id);

        $sekretarisJurusan = ProfileDosen::whereHas('user', function ($q) {
            $q->where('role', 'sekjur');
        })->first();

        $sekretarisSidang = ProfileDosen::find($request->sekretaris_sidang_id);

        $data = $this->pdfService->buildData($ujian, [
            'nomor' => $request->nomor_surat,
            'hal' => $request->hal,
            'tanggal_surat' => \Carbon\Carbon::parse($request->tanggal_surat)
                ->locale('id')->isoFormat('D MMMM Y'),
            'sekretaris' => $sekretarisSidang?->nama_lengkap ?? '-',
            'penandatangan' => [
                'nama' => $sekretarisJurusan?->nama_lengkap ?? '-',
                'nip' => $sekretarisJurusan?->nip ?? '-',
            ],
        ]);

        $pdf = $this->pdfService->generate($data);
        $fileName = 'undangan_seminar_'. $jenis . '_' . $ujian->tugasAkhir->mahasiswa->nim . '_' . $ujian->tugasAkhir->mahasiswa->nama_lengkap . '.pdf';
        $filePath = 'undangan/' . $fileName;
        Storage::disk('public')->put($filePath, $pdf->output());

        UndanganUjian::updateOrCreate(
            ['ujian_id' => $ujian->id],
            [
                'nomor_surat' => $request->nomor_surat,
                'hal' => $request->hal,
                'tanggal_surat' => $request->tanggal_surat,
                'ketua_sidang_id' => $request->ketua_sidang_id,
                'sekretaris_sidang_id' => $request->sekretaris_sidang_id,
                'file_path' => $filePath,
                'status' => 'draft',
            ]
        );

        return redirect()
            ->route('admin.ujian.undangan', [$jenis, $id])
            ->with('success', 'Undangan berhasil di-generate');
    }
}
