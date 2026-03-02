<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mahasiswa\SubmitHasilUjianRequest;
use App\Http\Requests\Mahasiswa\SubmitPengajuanUjianRequest;
use App\Services\Mahasiswa\UjianService;
use Illuminate\Http\Request;

class UjianController extends Controller
{
    public function __construct(protected UjianService $ujianService)
    {
    }

    public function index(Request $request)
    {
        $jenis = $request->route('jenis');
        $mahasiswa = $request->user()->profileMahasiswa;
        $tugasAkhirId = $mahasiswa->tugasAkhir?->id;

        if (!$tugasAkhirId) {
            return redirect()->route('mahasiswa.dashboard')->with('error', 'Tugas akhir belum tersedia.');
        }

        $ujian = $this->ujianService->getOrCreateUjian($tugasAkhirId, $jenis);

        return match ($ujian->status) {
            'draft', 'revisi_syarat' => redirect()->route('mahasiswa.ujian.pengajuan', ['jenis' => $jenis]),
            'menunggu_verifikasi_syarat' => redirect()->route('mahasiswa.ujian.pengajuan', ['jenis' => $jenis]),
            'menunggu_undangan' => redirect()->route('mahasiswa.ujian.undangan', ['jenis' => $jenis]),
            'menunggu_hasil' => redirect()->route('mahasiswa.ujian.undangan', ['jenis' => $jenis]),
            'selesai' => redirect()->route('mahasiswa.ujian.selesai', ['jenis' => $jenis]),
            default => abort(500, 'Status ujian tidak valid')
        };
    }

    public function showPengajuan(Request $request)
    {
        $jenis = $request->route('jenis');
        $mahasiswa = $request->user()->profileMahasiswa;
        $tugasAkhirId = $mahasiswa->tugasAkhir?->id;

        $daftarSyarat = collect(config("ujian.{$jenis}"));
        $ujian = $this->ujianService->getOrCreateUjian($tugasAkhirId, $jenis);

        $isRevisi = $ujian->status === 'revisi_syarat';
        $rejectedDokumen = collect();

        if ($isRevisi) {
            $rejectedDokumen = $ujian->dokumenUjian()
                ->where('status', 'tolak')
                ->where('kategori', 'syarat')
                ->get()
                ->keyBy('jenis_dokumen');

            $daftarSyarat = $daftarSyarat->filter(function ($syarat) use ($rejectedDokumen) {
                return $rejectedDokumen->has($syarat['name']);
            })->values();
        }

        return view("mahasiswa.ujian.upload-syarat", compact('jenis', 'daftarSyarat', 'ujian', 'isRevisi', 'rejectedDokumen'));
    }

    public function submitPengajuan(SubmitPengajuanUjianRequest $request)
    {
        $jenis = $request->route('jenis');
        $mahasiswa = $request->user()->profileMahasiswa;
        $tugasAkhirId = $mahasiswa->tugasAkhir?->id;

        $ujian = $this->ujianService->getOrCreateUjian($tugasAkhirId, $jenis);

        $files = $request->file('files', []);

        try {
            // Upload Dokumen
            $this->ujianService->uploadDokumen($ujian, $files, 'syarat', $mahasiswa->nim);

            // Simpan Jadwal (skip jika revisi, karena jadwal sudah ada)
            if ($request->filled('slot_waktu')) {
                [$jamMulai, $jamSelesai] = explode('-', $request->input('slot_waktu'));
                $this->ujianService->simpanJadwal($ujian, [
                    'tanggal_ujian' => $request->input('tanggal_ujian'),
                    'jam_mulai' => $jamMulai,
                    'jam_selesai' => $jamSelesai,
                    'ruangan' => $request->input('ruang_ujian'),
                ]);
            }

            // Mengecek kelengkapan jika kurang
            if (!$this->ujianService->isDokumenLengkap($ujian, $jenis)) {
                return back()->with('success', 'Berhasil upload berkas dan jadwal. Namun berkas syarat belum lengkap.')->withInput();
            }

            // Update status ke menunggu_verifikasi_syarat jika semua lengkap
            $ujian->update(['status' => 'menunggu_verifikasi_syarat']);

            // Redirect ke index (sementara akan abort 500 sampai route menunggu dibuat)
            return back()->with('success', 'Berhasil mengajukan ujian. Menunggu verifikasi dari admin.');

        } catch (\Throwable $th) {
            return back()->with('error', 'Gagal memproses pengajuan: ' . $th->getMessage())->withInput();
        }
    }

    public function showUndangan(Request $request, $jenis)
    {
        $mahasiswa = $request->user()->profileMahasiswa;
        $tugasAkhirId = $mahasiswa->tugasAkhir?->id;

        $ujian = $this->ujianService->getOrCreateUjian($tugasAkhirId, $jenis);

        return view('mahasiswa.ujian.undangan', compact('jenis', 'ujian'));
    }

    public function showHasilUjian(Request $request, $jenis)
    {
        $mahasiswa = $request->user()->profileMahasiswa;
        $tugasAkhirId = $mahasiswa->tugasAkhir?->id;

        $ujian = $this->ujianService->getOrCreateUjian($tugasAkhirId, $jenis);

        $daftarSyarat = collect(config("pasca_ujian.{$jenis}"));

        $isRevisi = $ujian->status === 'revisi_hasil';
        $rejectedDokumen = collect();

        if ($isRevisi) {
            $rejectedDokumen = $ujian->dokumenUjian()
                ->where('status', 'tolak')
                ->where('kategori', 'hasil')
                ->get()
                ->keyBy('jenis_dokumen');

            $daftarSyarat = $daftarSyarat->filter(function ($syarat) use ($rejectedDokumen) {
                return $rejectedDokumen->has($syarat['name']);
            })->values();
        }

        return view('mahasiswa.ujian.upload-hasil-ujian', compact('jenis', 'ujian', 'daftarSyarat', 'isRevisi', 'rejectedDokumen'));
    }

    public function submitHasilUjian(SubmitHasilUjianRequest $request)
    {
        $jenis = $request->route('jenis');
        $mahasiswa = $request->user()->profileMahasiswa;
        $tugasAkhirId = $mahasiswa->tugasAkhir?->id;

        $ujian = $this->ujianService->getOrCreateUjian($tugasAkhirId, $jenis);

        $files = $request->file('files', []);

        try {
            // Upload Dokumen
            $this->ujianService->uploadDokumen($ujian, $files, 'hasil', $mahasiswa->nim);


            // Mengecek kelengkapan jika kurang
            if (!$this->ujianService->isDokumenLengkap($ujian, $jenis, 'hasil', 'pasca_ujian')) {
                return back()->with('success', 'Berhasil upload berkas dan jadwal. Namun berkas hasil ujian belum lengkap.')->withInput();
            }

            // Update status ke menunggu_verifikasi_syarat jika semua lengkap
            $ujian->update(['status' => 'menunggu_verifikasi_hasil']);

            // Redirect ke index (sementara akan abort 500 sampai route menunggu dibuat)
            return back()->with('success', 'Berhasil mengajukan hasil ujian. Menunggu verifikasi dari admin.');

        } catch (\Throwable $th) {
            return back()->with('error', 'Gagal memproses pengajuan hasil ujian: ' . $th->getMessage())->withInput();
        }
    }

    public function selesai(Request $request, $jenis)
    {
        $mahasiswa = $request->user()->profileMahasiswa;
        $tugasAkhirId = $mahasiswa->tugasAkhir?->id;

        $ujian = $this->ujianService->getOrCreateUjian($tugasAkhirId, $jenis);

        if ($ujian->status == "selesai") {
            return view('mahasiswa.ujian.selesai', compact('jenis', 'ujian'));
        }

        return back()->with('error', 'Ujian belum selesai.');
    }
}
