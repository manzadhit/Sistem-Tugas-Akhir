<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mahasiswa\StoreSubmissionRequest;
use App\Models\DosenPembimbing;
use App\Models\User;
use App\Services\SubmissionService;
use Illuminate\Http\Request;

class BimbinganController extends Controller
{
    public function __construct(
        protected SubmissionService $submissionService
    ) {}

    public function index(Request $request)
    {
        $mahasiswa = $request->user()->profileMahasiswa;

        $tugasAkhir = $mahasiswa->tugasAkhir;
        $tugasAkhirId = $tugasAkhir->id;

        $allSubmission = $this->submissionService->getHistorySubmission($tugasAkhirId);
        $latestSubmissionPerPembimbing = $allSubmission
            ->groupBy('dosen_pembimbing_id')
            ->map->first();

        $pembimbing = DosenPembimbing::with('dosen')
            ->where('mahasiswa_id', $mahasiswa->id)
            ->orderBy('jenis_pembimbing')
            ->get()
            ->each(function ($p) use ($latestSubmissionPerPembimbing) {
                $latestSubmission = $latestSubmissionPerPembimbing->get($p->id);
                $p->hasSubmission = $latestSubmission?->status === 'pending';
                $p->isAcc = $latestSubmission?->status === 'acc';
            });

        $latestPerPembimbing = $latestSubmissionPerPembimbing
            ->where('status', '!=', 'pending');

        $hasTwoAccPembimbing = $latestSubmissionPerPembimbing
            ->where('status', 'acc')
            ->count() >= 2;

        return view('mahasiswa.bimbingan', compact('pembimbing', 'allSubmission', 'latestPerPembimbing', 'hasTwoAccPembimbing', 'tugasAkhir'));
    }

    public function createSubmission(StoreSubmissionRequest $request)
    {
        $mahasiswa = $request->user()?->profileMahasiswa;
        abort_if(!$mahasiswa, 403, 'Profil mahasiswa tidak ditemukan.');

        try {
            $this->submissionService->createSubmission(
                mahasiswa: $mahasiswa,
                dosenPembimbingId: $request->input('pembimbing'),
                catatan: $request->input('catatan'),
                files: $request->file('file_submission')
            );

            return back()->with('success', 'Submission berhasil dikirim');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengirim submission: ' . $e->getMessage());
        }
    }

    public function mintaPenguji()
    {
        $kajur = User::with('profileDosen')->where('role', 'kajur')->first();

        return view('mahasiswa.minta-penguji', compact('kajur'));
    }
}
