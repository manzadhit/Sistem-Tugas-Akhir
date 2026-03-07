<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mahasiswa\StoreSubmissionRequest;
use App\Models\DosenPembimbing;
use App\Models\DosenPenguji;
use App\Models\KajurSubmission;
use App\Models\ProfileDosen;
use App\Models\User;
use App\Notifications\NewSubmission;
use App\Notifications\SubmissionReviewed;
use App\Services\SubmissionService;
use Illuminate\Http\Request;

class BimbinganController extends Controller
{
    public function __construct(
        protected SubmissionService $submissionService
    ) {}

    public function index(Request $request, string $jenis)
    {
        $mahasiswa = $request->user()->profileMahasiswa;
        $tugasAkhir = $mahasiswa->tugasAkhir;

        $latestSubmissionPerPembimbing = $this->submissionService
            ->getHistorySubmission($tugasAkhir->id, $jenis)
            ->groupBy('dosen_pembimbing_id')
            ->map->first();

        $hasTwoAccPembimbing = $latestSubmissionPerPembimbing
            ->where('status', 'acc')
            ->count() >= 2;

        if ($hasTwoAccPembimbing) {
            return redirect()->route('mahasiswa.bimbingan.mintaPenguji', ['jenis' => $jenis]);
        }

        return redirect()->route('mahasiswa.bimbingan.bimbingan', ['jenis' => $jenis]);
    }

    public function bimbingan(Request $request, string $jenis)
    {
        $mahasiswa = $request->user()->profileMahasiswa;

        $tugasAkhir = $mahasiswa->tugasAkhir;
        $tugasAkhirId = $tugasAkhir->id;

        $urutan = ['proposal' => 1, 'hasil' => 2, 'skripsi' => 3];
        $tahapanSelesai = ($urutan[$tugasAkhir->tahapan] ?? 1) > ($urutan[$jenis] ?? 1);

        $allSubmission = $this->submissionService->getHistorySubmission($tugasAkhirId, $jenis);
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

        // Mark notif SubmissionReviewed terkait jenis ini sebagai read
        $request->user()->unreadNotifications()
            ->where('type', SubmissionReviewed::class)
            ->whereJsonContains('data->action_url', route('mahasiswa.bimbingan.bimbingan', ['jenis' => $jenis]))
            ->update(['read_at' => now()]);

        return view('mahasiswa.bimbingan', compact('pembimbing', 'allSubmission', 'latestPerPembimbing', 'hasTwoAccPembimbing', 'tugasAkhir', 'jenis', 'tahapanSelesai'));
    }

    public function createSubmission(StoreSubmissionRequest $request, string $jenis)
    {
        $mahasiswa = $request->user()?->profileMahasiswa;
        abort_if(!$mahasiswa, 403, 'Profil mahasiswa tidak ditemukan.');

        $dosenPembimbingId = $request->input('pembimbing');

        $dosenPembimbing = DosenPembimbing::with('dosen.user')
            ->where('id', $dosenPembimbingId)
            ->where('mahasiswa_id', $mahasiswa->id)
            ->firstOrFail();


        try {
            $submission = $this->submissionService->createSubmission(
                mahasiswa: $mahasiswa,
                dosenPembimbingId: $dosenPembimbingId,
                catatan: $request->input('catatan'),
                files: $request->file('file_submission'),
                tahapan: $jenis,
            );

            $dosenPembimbing->dosen->user->notify(new NewSubmission($mahasiswa, $submission));

            return back()->with('success', 'Submission berhasil dikirim');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengirim submission: ' . $e->getMessage());
        }
    }

    public function mintaPenguji(Request $request, string $jenis)
    {
        $mahasiswa = $request->user()->profileMahasiswa;
        $tugasAkhir = $mahasiswa->tugasAkhir;

        $kajur = User::with('profileDosen')->where('role', 'kajur')->first();

        $kajurSubmission = KajurSubmission::with('kajurSubmissionFiles')
            ->where('tugas_akhir_id', $tugasAkhir->id)
            ->latest()
            ->first();

        $dosenPenguji = DosenPenguji::with('dosen.user')
            ->where('mahasiswa_id', $mahasiswa->id)
            ->orderBy('jenis_penguji')
            ->get();

        return view('mahasiswa.minta-penguji', compact('kajur', 'jenis', 'kajurSubmission', 'dosenPenguji'));
    }
}
