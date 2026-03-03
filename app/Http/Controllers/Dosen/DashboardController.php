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
            ->count();

        $totalPublikasi = PublikasiDosen::where('dosen_id', $dosenId)->count();

        $mahasiswaBimbingan = DosenPembimbing::with(['mahasiswa.tugasAkhir'])
            ->where('dosen_id', $dosenId)
            ->where('status_aktif', true)
            ->latest()
            ->limit(5)
            ->get();

        $publikasiTerbaru = PublikasiDosen::where('dosen_id', $dosenId)
            ->latest()
            ->limit(5)
            ->get();

        return view('dosen.dashboard', compact(
            'totalMahasiswaBimbingan',
            'totalPublikasi',
            'mahasiswaBimbingan',
            'publikasiTerbaru'
        ));
    }
}
