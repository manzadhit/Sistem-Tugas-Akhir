<?php

namespace App\Http\Controllers\Dosen;

use App\Models\Submission;
use Illuminate\Http\Request;
use App\Models\DosenPembimbing;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dosen\ReviewSubmissionRequest;
use App\Services\Dosen\BimbinganService;

class MahasiswaBimbingan extends Controller
{
    public function __construct(protected BimbinganService $bimbinganService) {}

    public function index(Request $request)
    {
        $dosenId = $request->user()?->profileDosen->id;

        $mahasiswaBimbingan = DosenPembimbing::where('dosen_id', $dosenId)->get();

        $totalMahasiswaBimbingan = $mahasiswaBimbingan->count();

        $pendingSubmissions = $this->bimbinganService->getPendingSubmissionByDosen($dosenId);


        return view('dosen.bimbingan', compact('mahasiswaBimbingan', 'totalMahasiswaBimbingan', 'pendingSubmissions'));
    }

    public function getDetail(Submission $submission)
    {
        $submission->loadMissing([
            'tugasAkhir.mahasiswa',
            'submissionFiles' => fn($query) => $query->where('uploaded_by', 'mahasiswa')
        ]);

        return view('dosen.detail-bimbingan', compact('submission'));
    }

    public function review(ReviewSubmissionRequest $request, Submission $submission)
    {
        try {
            $this->bimbinganService->reviewSubmission(
                submission: $submission,
                payload: $request->validated(),
                files: $request->file('files', [])
            );

            return back()->with('success', 'Review berhasil diberikan.');
        } catch (\Exception $e) {
            return back()->with('error', "Review gagal diberikan. Silahkan coba lagi");
        }
    }
}
