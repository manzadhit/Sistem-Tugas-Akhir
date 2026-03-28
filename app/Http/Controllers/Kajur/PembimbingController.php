<?php

namespace App\Http\Controllers\Kajur;

use App\Http\Controllers\Controller;
use App\Models\DosenPembimbing;
use App\Models\PermintaanPembimbing;
use App\Models\ProfileDosen;
use App\Models\TugasAkhir;
use App\Notifications\NewPembimbingRequest;
use App\Notifications\PembimbingAssigned;
use App\Notifications\PembimbingRequestReviewed;
use App\Services\CBF\ContentBasedFilteringService;
use App\Services\MAUT\MAUTService;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PembimbingController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search'));

        $permintaanPembimbing = PermintaanPembimbing::with('mahasiswa')
            ->where('status', 'pending')
            ->where('status_verifikasi_bukti', '!=', 'ditolak')
            ->when($search !== '', function ($query) use ($search) {
                $query->whereHas('mahasiswa', function ($mahasiswaQuery) use ($search) {
                    $mahasiswaQuery->where('nama_lengkap', 'like', "%{$search}%")
                        ->orWhere('nim', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(8)
            ->withQueryString();

        return view('kajur.permintaan-pembimbing', compact('permintaanPembimbing', 'search'));
    }

    public function show(
        $permintaan,
        ContentBasedFilteringService $cbfService,
        MAUTService $mautService
    ) {
        $permintaan = PermintaanPembimbing::with('mahasiswa')->findOrFail($permintaan);

        request()->user()->unreadNotifications()
            ->where('type', NewPembimbingRequest::class)
            ->where('data->permintaan_pembimbing_id', $permintaan->id)
            ->update(['read_at' => now()]);

        $similarityScores = $cbfService->getTopN($permintaan->id, 5);
        $mautResult = $mautService->rankWithDetails($similarityScores, 'pembimbing', $permintaan->mahasiswa);

        $rankedIds = array_keys($mautResult);
        $activeBimbinganCount = [
            'pembimbingMahasiswa as total_bimbingan_aktif' => fn($q) => $q->where('status_aktif', true),
        ];

        $rankedDosens = ProfileDosen::whereIn('id', $rankedIds)
            ->withCount($activeBimbinganCount)
            ->get()
            ->sortBy(fn($item) => array_search($item->id, $rankedIds))
            ->values();

        $unrankedDosens = ProfileDosen::whereNotIn('id', $rankedDosens->pluck('id'))
            ->withCount($activeBimbinganCount)
            ->get();

        return view('kajur.penetapan-pembimbing', compact(
            'permintaan',
            'similarityScores',
            'mautResult',
            'rankedDosens',
            'unrankedDosens'
        ));
    }


    public function verifyBukti(Request $request, $permintaan)
    {
        $permintaan = PermintaanPembimbing::with('mahasiswa.user')->findOrFail($permintaan);

        $data = $request->validate([
            'status' => 'required|in:disetujui,ditolak',
            'alasan' => 'required_if:status,ditolak|nullable|string|max:500'
        ]);

        $permintaan->status_verifikasi_bukti = $data['status'];
        $permintaan->catatan = $data['status'] == 'ditolak' ? $data['alasan'] : null;
        $permintaan->diproses_pada = now();

        $permintaan->save();

        $permintaan->mahasiswa?->user?->notify(new PembimbingRequestReviewed($permintaan));

        return back()->with('success', 'Verifikasi bukti berhasil disimpan.');
    }

    public function tetapkanPembimbing(Request $request, PermintaanPembimbing $permintaan)
    {
        $request->validate([
            'dosen_ids' => ['required', 'array', 'min:1'],
            'dosen_ids.*' => ['integer', 'distinct', 'exists:profile_dosen,id'],
        ]);

        $mahasiswaId = $permintaan->mahasiswa_id;
        $dosenIds = $request->input('dosen_ids');

        DB::transaction(function () use ($dosenIds, $mahasiswaId, $permintaan) {
            foreach ($dosenIds as $index => $dosenId) {
                DosenPembimbing::create(
                    [
                        'dosen_id' => $dosenId,
                        'mahasiswa_id' => $mahasiswaId,
                        'jenis_pembimbing' => 'pembimbing_' . ($index + 1),
                        'tanggal_mulai' => now()
                    ]
                );
            }

            $permintaan->update([
                'status' => 'selesai',
                'diproses_pada' => now(),
            ]);

            TugasAkhir::create([
                'judul' => $permintaan->judul_ta,
                'mahasiswa_id' => $permintaan->mahasiswa_id,
            ]);
        });

        $permintaan->loadMissing('mahasiswa.user');

        $permintaan->mahasiswa?->user?->notify(new PembimbingAssigned($permintaan));

        return back()->with('success', 'Pembimbing berhasil ditetapkan');
    }
}
