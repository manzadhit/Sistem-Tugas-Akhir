<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mahasiswa\StoreKajurSubmissionRequest;
use App\Services\Mahasiswa\KajurSubmissionService;

class KajurSubmissionController extends Controller
{
    public function __construct(protected KajurSubmissionService $kajurSubmissionService)
    {}

    public function createKajurSubmission(StoreKajurSubmissionRequest $request)
    {
        try {
            $mahasiswaId = $request->user()->profileMahasiswa->id;

            $catatan = $request->input('catatan');
            $files = $request->file('files');

            $this->kajurSubmissionService->createKajurSubmission($mahasiswaId, $catatan, $files);

            return back()->with('success', 'Berhasil mengirim laporan ke Ketua Jurusan');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengirim laporan ke Ketua Jurusan.' . $e->getMessage());
        }
    }
}
