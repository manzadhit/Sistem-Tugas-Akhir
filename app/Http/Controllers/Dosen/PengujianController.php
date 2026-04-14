<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\DosenPenguji;
use App\Models\PeriodeAkademik;
use Illuminate\Http\Request;

class PengujianController extends Controller
{
    public function index(Request $request)
    {
        $dosenId = $request->user()?->profileDosen->id;

        // All periode options for dropdown (newest first)
        $semuaPeriode = PeriodeAkademik::orderByDesc('mulai_at')->get();
        $periodeAktif = PeriodeAkademik::aktif()->first();

        // Use selected periode or default to active
        $selectedPeriodeId = $request->input('periode_akademik_id', $periodeAktif?->id);
        $selectedPeriode = $semuaPeriode->firstWhere('id', $selectedPeriodeId);

        $query = DosenPenguji::with(['mahasiswa.tugasAkhir.ujian' => function ($q) use ($selectedPeriodeId) {
                if ($selectedPeriodeId) {
                    $q->where('periode_akademik_id', $selectedPeriodeId);
                }
            }])
            ->where('dosen_id', $dosenId)
            ->whereHas('mahasiswa.tugasAkhir.ujian', function ($q) use ($selectedPeriodeId) {
                if ($selectedPeriodeId) {
                    $q->where('periode_akademik_id', $selectedPeriodeId);
                }
            });

        // Search filter
        if ($search = $request->input('search')) {
            $query->whereHas('mahasiswa', function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%");
            });
        }

        $daftarPengujian = $query->latest()->paginate(15)->withQueryString();

        return view('dosen.daftar-pengujian', compact(
            'daftarPengujian',
            'semuaPeriode',
            'selectedPeriode',
            'selectedPeriodeId'
        ));
    }
}
