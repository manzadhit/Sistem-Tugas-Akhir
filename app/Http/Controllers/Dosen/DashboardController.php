<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\DosenPembimbing;
use App\Models\PublikasiDosen;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $dosenId = $request->user()?->profileDosen->id;

        $totalMahasiswaBimbingan = DosenPembimbing::where('dosen_id', $dosenId)
            ->where('status_aktif', true)
            ->whereHas('mahasiswa', fn($q) => $q->where('status_akademik', 'aktif'))
            ->count();

        $totalPublikasi = PublikasiDosen::where('dosen_id', $dosenId)->count();

        $mahasiswaBimbingan = DosenPembimbing::with(['mahasiswa.tugasAkhir'])
            ->where('dosen_id', $dosenId)
            ->where('status_aktif', true)
            ->whereHas('mahasiswa', fn($q) => $q->where('status_akademik', 'aktif'))
            ->latest()
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

        return view('dosen.dashboard', compact(
            'totalMahasiswaBimbingan',
            'totalMahasiswaLulus',
            'totalPublikasi',
            'mahasiswaBimbingan',
            'mahasiswaLulus',
            'publikasiTerbaru'
        ));
    }
}
