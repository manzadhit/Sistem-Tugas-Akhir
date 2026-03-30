<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PeriodeAkademik;
use App\Models\ProfileDosen;
use App\Models\ProfileMahasiswa;
use App\Models\PublikasiDosen;
use App\Models\Ujian;

class DashboardController extends Controller
{
    public function index()
    {
        $periodeAktif = PeriodeAkademik::where('status', 'aktif')->first();

        $stats = [
            'periode_aktif'   => $periodeAktif ? $periodeAktif->tahun_ajaran . ' - ' . ucfirst($periodeAktif->semester) : 'Tidak Ada',
            'total_mahasiswa' => ProfileMahasiswa::count(),
            'total_dosen'     => ProfileDosen::count(),
            'total_publikasi' => PublikasiDosen::count(),
            'mhs_aktif'       => ProfileMahasiswa::where('status_akademik', 'aktif')->count(),
            'mhs_cuti'        => ProfileMahasiswa::where('status_akademik', 'cuti')->count(),
            'mhs_lulus'       => ProfileMahasiswa::where('status_akademik', 'lulus')->count(),
            'dosen_aktif'     => ProfileDosen::where('status', 'aktif')->count(),
            'verif_syarat'    => Ujian::where('status', 'menunggu_verifikasi_syarat')->count(),
            'verif_hasil'     => Ujian::where('status', 'menunggu_verifikasi_hasil')->count(),
        ];

        $topPublikasi = ProfileDosen::withCount('publikasi')
            ->orderByDesc('publikasi_count')
            ->limit(5)
            ->get()
            ->filter(fn($d) => $d->publikasi_count > 0);

        return view('admin.dashboard', compact('stats', 'topPublikasi'));
    }
}
