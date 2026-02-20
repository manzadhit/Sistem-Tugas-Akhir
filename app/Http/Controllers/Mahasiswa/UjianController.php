<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mahasiswa\StoreJadwalUjianRequest;
use App\Http\Requests\Mahasiswa\UploadDokumenUjianRequest;
use App\Models\JadwalUjian;
use App\Models\TugasAkhir;
use App\Services\Mahasiswa\UjianService;
use Illuminate\Http\Request;

class UjianController extends Controller
{
    public function __construct(protected UjianService $ujianService) {}

    public function show(Request $request)
    {
        $jenis = $request->route('jenis');
        $daftarSyarat = config("ujian.{$jenis}");
        return view("mahasiswa.ujian", compact('jenis', 'daftarSyarat'));
    }

    public function uploadDokumen(UploadDokumenUjianRequest $request)
    {
        $jenis = $request->route('jenis');
        $mahasiswa = $request->user()->profileMahasiswa;
        $tugasAkhirId = $mahasiswa->tugasAkhir?->id;

        $ujian = $this->ujianService->getOrCreateUjian($tugasAkhirId, $jenis);

        $files = $request->file('files', []);
        try {
            $this->ujianService->uploadDokumen($ujian, $files, 'syarat', $mahasiswa->nim);

            return back()->with('success', 'Berhasil upload berkas syarat ujian.');
        } catch (\Throwable $th) {
            return back()->with('error', 'Gagal upload berkas: ' . $th->getMessage());
        }
    }

    public function showJadwal(Request $request)
    {
        $jenis = $request->route('jenis');

        return view('mahasiswa.jadwal', compact('jenis'));
    }

    public function addJadwal(StoreJadwalUjianRequest $request)
    {
        $mahasiswa = $request->user()->profileMahasiswa;
        $tugasAkhirId = $mahasiswa->tugasAkhir?->id;
        $jenis = $request->route('jenis');

        abort_if(!$tugasAkhirId, 403, 'Tugas akhir belum tersedia.');

        [$jamMulai, $jamSelesai] = explode('-', $request->input('slot_waktu'));

        try {
            $ujian = $this->ujianService->getOrCreateUjian($tugasAkhirId, $jenis);

            $this->ujianService->simpanJadwal($ujian, [
                'tanggal_ujian' => $request->input('tanggal_ujian'),
                'jam_mulai'     => $jamMulai,
                'jam_selesai'   => $jamSelesai,
                'ruangan'       => $request->input('ruang_ujian'),
            ]);

            return back()->with('success', 'Jadwal ujian berhasil disimpan.');
        } catch (\Throwable $th) {
            return back()->with('error', 'Gagal menyimpan jadwal ujian: ' . $th->getMessage());
        }
    }
}
