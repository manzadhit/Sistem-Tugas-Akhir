<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProfileDosen;
use App\Models\ProfileMahasiswa;
use App\Models\PublikasiDosen;
use App\Models\Ujian;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_mahasiswa' => ProfileMahasiswa::count(),
            'total_dosen'     => ProfileDosen::count(),
            'total_publikasi' => PublikasiDosen::count(),
            'mhs_aktif'       => ProfileMahasiswa::where('status_akademik', 'aktif')->count(),
            'mhs_cuti'        => ProfileMahasiswa::where('status_akademik', 'cuti')->count(),
            'mhs_lulus'       => ProfileMahasiswa::where('status_akademik', 'lulus')->count(),
            'dosen_aktif'     => ProfileDosen::where('status', 'aktif')->count(),
            'ujian_pending'   => Ujian::whereIn('status', [
                'menunggu_verifikasi_syarat',
                'menunggu_undangan',
                'menunggu_verifikasi_hasil',
            ])->count(),
        ];

        $topPublikasi = ProfileDosen::withCount('publikasi')
            ->orderByDesc('publikasi_count')
            ->limit(5)
            ->get()
            ->filter(fn($d) => $d->publikasi_count > 0);

        return view('admin.dashboard', compact('stats', 'topPublikasi'));
    }
}
