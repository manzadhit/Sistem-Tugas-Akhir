<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mahasiswa\StoreSubmissionRequest;
use App\Models\DosenPembimbing;
use App\Models\Ujian;
use App\Models\DosenPenguji;
use App\Models\KajurSubmission;
use App\Models\User;
use App\Notifications\KajurSubmissionReviewed;
use App\Notifications\NewSubmission;
use App\Notifications\PengujiAssigned;
use App\Notifications\SubmissionReviewed;
use App\Services\Mahasiswa\SubmissionService;
use Illuminate\Http\Request;
use App\Models\Submission;
use Illuminate\Support\Facades\Log;

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

        $ujianSelesai = Ujian::where('tugas_akhir_id', $tugasAkhir->id)
            ->where('jenis_ujian', $jenis)
            ->where('status', 'selesai')
            ->exists();

        if ($ujianSelesai) {
            return redirect()->route('mahasiswa.bimbingan.selesai', ['jenis' => $jenis]);
        }

        if ($hasTwoAccPembimbing) {
            if ($jenis === 'proposal') {
                return redirect()->route('mahasiswa.bimbingan.mintaPenguji', ['jenis' => $jenis]);
            }
            return redirect()->route('mahasiswa.bimbingan.persetujuanKajur', ['jenis' => $jenis]);
        }

        return redirect()->route('mahasiswa.bimbingan.bimbingan', ['jenis' => $jenis]);
    }

    public function bimbingan(Request $request, string $jenis)
    {
        $mahasiswa = $request->user()->profileMahasiswa;

        $tugasAkhir = $mahasiswa->tugasAkhir;
        $tugasAkhirId = $tugasAkhir->id;

        $ujianSelesai = Ujian::where('tugas_akhir_id', $tugasAkhirId)
            ->where('jenis_ujian', $jenis)
            ->where('status', 'selesai')
            ->exists();

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

        return view('mahasiswa.bimbingan.bimbingan', compact('pembimbing', 'allSubmission', 'latestPerPembimbing', 'hasTwoAccPembimbing', 'tugasAkhir', 'jenis', 'ujianSelesai'));
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
            Log::error('Gagal mengirim submission bimbingan.', [
                'user_id' => $request->user()?->id,
                'jenis' => $jenis,
                'mahasiswa_id' => $mahasiswa?->id,
                'dosen_pembimbing_id' => $dosenPembimbingId,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Gagal mengirim submission. Silakan coba lagi.');
        }
    }

    public function mintaPenguji(Request $request, string $jenis)
    {
        $mahasiswa = $request->user()->profileMahasiswa;
        $tugasAkhir = $mahasiswa->tugasAkhir;

        $kajur = User::with('profileDosen')->where('role', 'kajur')->first();

        $kajurSubmission = KajurSubmission::with('kajurSubmissionFiles')
            ->where('tugas_akhir_id', $tugasAkhir->id)
            ->where('tahapan', 'proposal')
            ->latest()
            ->first();

        $request->user()->unreadNotifications()
            ->where('type', KajurSubmissionReviewed::class)
            ->whereJsonContains('data->action_url', route('mahasiswa.bimbingan.mintaPenguji', ['jenis' => $jenis]))
            ->update(['read_at' => now()]);

        $dosenPenguji = DosenPenguji::with('dosen.user')
            ->where('mahasiswa_id', $mahasiswa->id)
            ->orderBy('jenis_penguji')
            ->get();

        if ($dosenPenguji->isNotEmpty()) {
            $request->user()->unreadNotifications()
                ->where('type', PengujiAssigned::class)
                ->whereJsonContains('data->action_url', route('mahasiswa.bimbingan.mintaPenguji', ['jenis' => $jenis]))
                ->update(['read_at' => now()]);
        }

        $ujianSelesai = Ujian::where('tugas_akhir_id', $tugasAkhir->id)
            ->where('jenis_ujian', $jenis)
            ->where('status', 'selesai')
            ->exists();

        return view('mahasiswa.bimbingan.minta-penguji', compact('kajur', 'jenis', 'kajurSubmission', 'tugasAkhir', 'dosenPenguji', 'ujianSelesai'));
    }

    public function persetujuanKajur(Request $request, string $jenis)
    {
        $mahasiswa = $request->user()->profileMahasiswa;
        $tugasAkhir = $mahasiswa->tugasAkhir;

        $kajur = User::with('profileDosen')->where('role', 'kajur')->first();

        $kajurSubmission = KajurSubmission::with('kajurSubmissionFiles')
            ->where('tugas_akhir_id', $tugasAkhir->id)
            ->where('tahapan', $jenis)
            ->latest()
            ->first();

        $request->user()->unreadNotifications()
            ->where('type', KajurSubmissionReviewed::class)
            ->whereJsonContains('data->action_url', route('mahasiswa.bimbingan.persetujuanKajur', ['jenis' => $jenis]))
            ->update(['read_at' => now()]);

        $ujianSelesai = Ujian::where('tugas_akhir_id', $tugasAkhir->id)
            ->where('jenis_ujian', $jenis)
            ->where('status', 'selesai')
            ->exists();

        return view('mahasiswa.bimbingan.persetujuan-kajur', compact('kajur', 'jenis', 'kajurSubmission', 'tugasAkhir', 'ujianSelesai'));
    }

    public function selesai(Request $request, string $jenis)
    {
        $mahasiswa = $request->user()->profileMahasiswa;
        $tugasAkhir = $mahasiswa->tugasAkhir;

        $ujianSelesai = Ujian::where('tugas_akhir_id', $tugasAkhir->id)
            ->where('jenis_ujian', $jenis)
            ->where('status', 'selesai')
            ->exists();

        abort_unless($ujianSelesai, 403, 'Ujian belum selesai.');

        return view('mahasiswa.bimbingan.bimbingan-selesai', compact('jenis', 'tugasAkhir'));
    }

    public function history(Request $request)
    {
        $mahasiswa = $request->user()->profileMahasiswa;
        $tugasAkhir = $mahasiswa->tugasAkhir;

        abort_unless($tugasAkhir, 404, 'Tugas akhir tidak ditemukan.');

        $tahapanOrder = ['skripsi', 'hasil', 'proposal'];

        $submissionsByTahapan = Submission::with(['submissionFiles', 'dosenPembimbing.dosen'])
            ->where('tugas_akhir_id', $tugasAkhir->id)
            ->whereIn('tahapan', $tahapanOrder)
            ->latest()
            ->get()
            ->groupBy('tahapan');

        return view('mahasiswa.bimbingan.history', compact('tugasAkhir', 'tahapanOrder', 'submissionsByTahapan'));
    }
}
