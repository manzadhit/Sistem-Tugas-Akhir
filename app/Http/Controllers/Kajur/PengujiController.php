<?php

namespace App\Http\Controllers\Kajur;

use App\Http\Controllers\Controller;
use App\Http\Requests\Kajur\TetapkanPengujiRequest;
use App\Http\Requests\Kajur\VerifyLaporanRequest;
use App\Models\KajurSubmission;
use App\Models\PeriodeAkademik;
use App\Models\ProfileDosen;
use App\Notifications\KajurSubmissionReviewed;
use App\Notifications\NewPengujiRequest;
use App\Notifications\PengujiAssigned;
use App\Services\CBF\ContentBasedFilteringService;
use App\Services\Kajur\PenetapanPengujiService;
use App\Services\MAUT\MAUTService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\MultipleRecordsFoundException;
use Illuminate\Http\Request;

class PengujiController extends Controller
{
    public function __construct(protected PenetapanPengujiService $penetapanPengujiService) {}

    public function index(Request $request)
    {
        $search = trim((string) $request->query('search'));

        $permintaanPenguji = KajurSubmission::with('tugasAkhir.mahasiswa.dosenPembimbing.dosen')
            ->whereIn('status', ['pending', 'acc'])
            ->whereDoesntHave('tugasAkhir.mahasiswa.dosenPenguji')
            ->when($search !== '', function ($query) use ($search) {
                $query->whereHas('tugasAkhir.mahasiswa', function ($mahasiswaQuery) use ($search) {
                    $mahasiswaQuery->where('nama_lengkap', 'like', "%{$search}%")
                        ->orWhere('nim', 'like', "%{$search}%");
                });
            })
            ->oldest()
            ->paginate(8)
            ->withQueryString();

        return view('kajur.permintaan-penguji', compact('permintaanPenguji', 'search'));
    }

    public function show(
        KajurSubmission $permintaan,
        ContentBasedFilteringService $cbfService,
        MAUTService $mautService
    ) {
        $permintaan->load(['tugasAkhir.mahasiswa.dosenPembimbing.dosen', 'kajurSubmissionFiles' => fn($q) => $q->where('uploaded_by', 'mahasiswa')->latest()]);

        request()->user()->unreadNotifications()
            ->where('type', NewPengujiRequest::class)
            ->where('data->kajur_submission_id', $permintaan->id)
            ->update(['read_at' => now()]);

        $mahasiswa = $permintaan->tugasAkhir->mahasiswa;
        $hasPenguji = $mahasiswa->dosenPenguji()->exists();

        $similarityScores = [];
        $mautResult = [];
        $rankedDosens = collect();
        $unrankedDosens = collect();
        $periodeAktifId = PeriodeAkademik::aktif()->value('id');

        $pengujiCount = [
            'pengujiMahasiswa as total_pengujian_periode' => $this->penetapanPengujiService->getPengujianAktifQuery($periodeAktifId)
        ];

        if (! $hasPenguji && $permintaan->status === 'acc') {
            $similarityScores = $cbfService->getTopN($permintaan->id, 5, 'penguji');
            $mautResult = $mautService->rankWithDetails($similarityScores, 'penguji');
            $rankedIds = array_keys($mautResult);

            $rankedDosens = ProfileDosen::whereIn('id', $rankedIds)
                ->withCount($pengujiCount)
                ->get()
                ->sortBy(fn($item) => array_search($item->id, $rankedIds))
                ->values();

            $unrankedDosens = ProfileDosen::whereNotIn('id', $rankedDosens->pluck('id'))
                ->withCount($pengujiCount)
                ->get();
        }

        return view('kajur.penetapan-penguji', compact(
            'permintaan',
            'hasPenguji',
            'similarityScores',
            'mautResult',
            'rankedDosens',
            'unrankedDosens'
        ));
    }

    public function verifyLaporan(VerifyLaporanRequest $request, KajurSubmission $permintaan)
    {
        try {
            $reviewed = $this->penetapanPengujiService->verifyLaporan(
                kajurSubmission: $permintaan,
                payload: $request->validated(),
                files: $request->file('files', [])
            );

            $reviewed->loadMissing('tugasAkhir.mahasiswa.user');
            $reviewed->tugasAkhir->mahasiswa?->user?->notify(new KajurSubmissionReviewed($reviewed));

            return back()->with('success', $request->input('status') . ' telah diberikan');
        } catch (\Throwable $th) {
            return back()->with('error', 'Gagal menyimpan verifikasi');
        }
    }

    public function tetapkanPenguji(KajurSubmission $permintaan, TetapkanPengujiRequest $request)
    {
        $dosenIds = $request->validated()['penguji_ids'];

        $mahasiswaId = $permintaan->tugasAkhir->mahasiswa->id;

        try {
            $this->penetapanPengujiService->tetapkanPenguji($mahasiswaId, $dosenIds);
            $permintaan->loadMissing('tugasAkhir.mahasiswa.user');
            $permintaan->tugasAkhir->mahasiswa?->user?->notify(new PengujiAssigned($permintaan));

            return back()->with('show_success_modal', true);
        } catch (\Throwable $th) {
            return back()->with('error', 'Penguji gagal ditetapkan');
        }
    }
}
