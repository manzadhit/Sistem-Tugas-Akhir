<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Models\Submission;
use App\Models\TugasAkhir;
use Illuminate\Http\Request;
use App\Models\DosenPembimbing;
use App\Http\Controllers\Controller;
use App\Http\Requests\Mahasiswa\StoreSubmissionRequest;
use App\Models\SubmissionFile;
use App\Services\SubmissionService;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\TryCatch;

class BimbinganController extends Controller
{
    public function __construct(
        protected SubmissionService $submissionService
    )
    {}

    public function index(Request $request)
    {
        $mahasiswa = $request->user()->profileMahasiswa;

        $pembimbing = DosenPembimbing::with('dosen')->where('mahasiswa_id', $mahasiswa->id)->orderBy('jenis_pembimbing')->get();

        return view('mahasiswa.bimbingan', compact('pembimbing'));
    }

    public function createSubmission(StoreSubmissionRequest $request)
    {
        $mahasiswa = $request->user()?->profileMahasiswa;
        abort_if(!$mahasiswa, 403, 'Profil mahasiswa tidak ditemukan.');

        try {
            $this->submissionService->createSubmission(
                mahasiswa: $mahasiswa,
                dosenPembimbingId: $request->input('pembimbing'),
                files: $request->file('file_submission')
            );

            return back()->with('success', 'Submission berhasil dikirim');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengirim submission: '. $e->getMessage());
        }
    }
}
