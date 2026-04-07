<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mahasiswa\StoreKajurSubmissionRequest;
use App\Models\User;
use App\Notifications\NewPengujiRequest;
use App\Services\Mahasiswa\KajurSubmissionService;
use Illuminate\Support\Facades\Log;

class KajurSubmissionController extends Controller
{
    public function __construct(protected KajurSubmissionService $kajurSubmissionService)
    {}

    public function createKajurSubmission(StoreKajurSubmissionRequest $request)
    {
        try {
            $mahasiswaId = $request->user()->profileMahasiswa->id;
            $jenis = $request->route('jenis');

            $catatan = $request->input('catatan');
            $abstrak = $request->input('abstrak');
            $kataKunci = $request->input('kata_kunci');
            $files = $request->file('files');

            $kajurSubmission = $this->kajurSubmissionService->createKajurSubmission($mahasiswaId, $jenis, $catatan, $abstrak, $kataKunci, $files);
            $kajurSubmission->loadMissing('tugasAkhir.mahasiswa');

            User::where('role', 'kajur')
                ->get()
                ->each
                ->notify(new NewPengujiRequest($kajurSubmission));

            return back()->with('success', 'Berhasil mengirim laporan ke Ketua Jurusan');
        } catch (\Exception $e) {
            Log::error('Gagal mengirim laporan ke Ketua Jurusan.', [
                'user_id' => $request->user()?->id,
                'jenis' => $request->route('jenis'),
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Gagal mengirim laporan ke Ketua Jurusan. Silakan coba lagi.');
        }
    }
}
