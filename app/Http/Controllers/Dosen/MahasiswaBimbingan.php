<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dosen\ReviewSubmissionRequest;
use App\Models\DosenPembimbing;
use App\Models\Submission;
use App\Notifications\NewSubmission;
use App\Services\Dosen\BimbinganService;
use Illuminate\Http\Request;

class MahasiswaBimbingan extends Controller
{
    public function __construct(protected BimbinganService $bimbinganService) {}

    public function index(Request $request)
    {
        $dosenId = $request->user()?->profileDosen->id;

        $mahasiswaBimbingan = DosenPembimbing::where('dosen_id', $dosenId)->get();

        $totalMahasiswaBimbingan = $mahasiswaBimbingan->count();

        $search = $request->input('search');
        $tahap  = $request->input('tahap');

        $pendingSubmissions = $this->bimbinganService->getPendingSubmissionByDosen($dosenId, $search, $tahap);

        return view('dosen.bimbingan', compact('mahasiswaBimbingan', 'totalMahasiswaBimbingan', 'pendingSubmissions'));
    }

    public function mahasiswaList(Request $request)
    {
        $dosenId = $request->user()?->profileDosen->id;
        $search  = $request->input('search');

        $mahasiswaBimbingan = $this->bimbinganService->getAllMahasiswaBimbingan($dosenId, $search);

        return view('dosen.mahasiswa-bimbingan', compact('mahasiswaBimbingan'));
    }

    public function riwayatBimbingan(DosenPembimbing $dosenPembimbing)
    {
        $dosenPembimbing->loadMissing([
            'mahasiswa.tugasAkhir',
            'submissions' => fn($q) => $q->latest(),
        ]);

        return view('dosen.riwayat-bimbingan', compact('dosenPembimbing'));
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

            $request->user()->unreadNotifications()->where('type', NewSubmission::class)
            ->whereJsonContains('data->submission_id', $submission->id)->update(['read_at' => now()]);

            return back()->with('success', 'Review berhasil diberikan.');
        } catch (\Exception $e) {
            return back()->with('error', "Review gagal diberikan. Silahkan coba lagi");
        }
    }
}
