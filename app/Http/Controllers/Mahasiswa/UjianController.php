<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
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
            'draft', 'revisi' => redirect()->route('mahasiswa.ujian.pengajuan', ['jenis' => $jenis]),
            'menunggu_verifikasi' => redirect()->route('mahasiswa.ujian.pengajuan', ['jenis' => $jenis]),
            // 'menunggu_undangan'   => redirect()->route('mahasiswa.undangan', ['jenis' => $jenis]),
            // 'menunggu_hasil'      => redirect()->route('mahasiswa.hasil', ['jenis' => $jenis]),
            // 'selesai'             => redirect()->route('mahasiswa.selesai', ['jenis' => $jenis]),
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

        $isRevisi = $ujian->status === 'revisi';
        $rejectedDokumen = collect();

        if ($isRevisi) {
            $rejectedDokumen = $ujian->dokumenUjian()
                ->where('status', 'tolak')
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

            // Update status ke menunggu_verifikasi jika semua lengkap
            $ujian->update(['status' => 'menunggu_verifikasi']);

            // Redirect ke index (sementara akan abort 500 sampai route menunggu dibuat)
            return back()->with('success', 'Berhasil mengajukan ujian. Menunggu verifikasi dari admin.');

        } catch (\Throwable $th) {
            return back()->with('error', 'Gagal memproses pengajuan: ' . $th->getMessage())->withInput();
        }
    }
}
