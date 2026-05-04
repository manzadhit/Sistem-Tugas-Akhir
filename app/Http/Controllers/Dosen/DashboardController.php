<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\DosenPembimbing;
use App\Models\DosenPenguji;
use App\Models\PeriodeAkademik;
use App\Models\PublikasiDosen;
use App\Services\Dosen\PengujianService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(protected PengujianService $pengujianService) {}

    public function index(Request $request)
    {
        $dosenId = $request->user()?->profileDosen->id;
        $periodeAktif = PeriodeAkademik::aktif()->first();

        // === Bimbingan ===
        $totalMahasiswaBimbingan = DosenPembimbing::where('dosen_id', $dosenId)
            ->where('status_aktif', true)
            ->whereHas('mahasiswa', fn($q) => $q->where('status_akademik', 'aktif'))
            ->count();

        $totalPublikasi = PublikasiDosen::where('dosen_id', $dosenId)->count();

        $mahasiswaBimbingan = DosenPembimbing::with(['mahasiswa.tugasAkhir'])
            ->where('dosen_id', $dosenId)
            ->where('status_aktif', true)
            ->whereHas('mahasiswa', fn($q) => $q->where('status_akademik', 'aktif'))
            ->orderByDesc('tanggal_mulai')
            ->limit(5)
            ->get();

        $totalMahasiswaLulus = DosenPembimbing::where('dosen_id', $dosenId)
            ->whereHas('mahasiswa', fn($q) => $q->where('status_akademik', 'lulus'))
            ->count();

        $mahasiswaLulus = DosenPembimbing::with(['mahasiswa.tugasAkhir'])
            ->where('dosen_id', $dosenId)
            ->whereHas('mahasiswa', fn($q) => $q->where('status_akademik', 'lulus'))
            ->latest()
            ->limit(5)
            ->get();

        $publikasiTerbaru = PublikasiDosen::where('dosen_id', $dosenId)
            ->latest()
            ->limit(5)
            ->get();

        $pengujianQuery = $this->pengujianService->getQuery($dosenId, $periodeAktif);
        $totalPengujianPeriode = (clone $pengujianQuery)->count();
        $mahasiswaPengujian = (clone $pengujianQuery)->limit(5)->get();

        // Pengujian aktif: mahasiswa yg sudah proposal tapi belum lulus (semua periode)
        $totalPengujianAktif = DosenPenguji::where('dosen_id', $dosenId)
            ->whereHas('mahasiswa', fn($q) => $q
                ->where('status_akademik', 'aktif')
                ->whereHas('tugasAkhir.ujian', fn($q2) => $q2->where('jenis_ujian', 'proposal')->where('status', 'selesai'))
            )->count();

        return view('dosen.dashboard', compact(
            'totalMahasiswaBimbingan',
            'totalMahasiswaLulus',
            'totalPublikasi',
            'totalPengujianPeriode',
            'totalPengujianAktif',
            'mahasiswaBimbingan',
            'mahasiswaLulus',
            'publikasiTerbaru',
            'mahasiswaPengujian',
            'periodeAktif'
        ));
    }
}
