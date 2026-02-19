<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mahasiswa\UploadDokumenUjianRequest;
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

    public function uploadDokumen(UploadDokumenUjianRequest $request) {
        $jenis = $request->route('jenis');
        $tugasAkhirId = $request->user()->profileMahasiswa?->tugasAkhir?->id;

        $ujian = $this->ujianService->getOrCreateUjian($tugasAkhirId, $jenis);

        $files = $request->file('files', []);
        try {
            $this->ujianService->uploadDokumen($ujian, $files, 'syarat');

            return back()->with('success', 'Berhasil upload berkas syarat ujian.');
        } catch (\Throwable $th) {
            return back()->with('error', 'Gagal upload berkas: '. $th->getMessage());
        }
    }
}
