<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\PeriodeAkademik;
use App\Notifications\DosenDitetapkanPenguji;
use App\Services\Dosen\PengujianService;
use Illuminate\Http\Request;

class PengujianController extends Controller
{
    public function __construct(protected PengujianService $pengujianService) {}

    public function index(Request $request)
    {
        $dosenId = $request->user()?->profileDosen->id;
        $search = trim((string) $request->input('search'));

        $request->user()->unreadNotifications()
            ->where('type', DosenDitetapkanPenguji::class)
            ->update(['read_at' => now()]);

        $semuaPeriode = PeriodeAkademik::orderByDesc('mulai_at')->get();
        $periodeAktif = PeriodeAkademik::aktif()->first();
        $selectedPeriodeId = $request->input('periode_akademik_id', $periodeAktif?->id);
        $selectedPeriode = $semuaPeriode->firstWhere('id', $selectedPeriodeId);

        $daftarPengujian = $this->pengujianService
            ->getQuery($dosenId, $selectedPeriode, $search)
            ->paginate(15)
            ->withQueryString();

        return view('dosen.daftar-pengujian', compact(
            'daftarPengujian',
            'semuaPeriode',
            'selectedPeriode',
            'selectedPeriodeId'
        ));
    }
}
