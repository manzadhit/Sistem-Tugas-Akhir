<?php

namespace App\Http\Controllers\Kajur;

use App\Http\Controllers\Controller;
use App\Models\DosenPembimbing;
use App\Models\KajurSubmission;
use App\Models\PermintaanPembimbing;
use App\Models\ProfileMahasiswa;
use App\Models\PublikasiDosen;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Data Kajur
        $totalMahasiswa = ProfileMahasiswa::count();
        $menungguPembimbing = PermintaanPembimbing::where('status', 'pending')
            ->where('status_verifikasi_bukti', '!=', 'ditolak')
            ->count();

        $menungguPenguji = KajurSubmission::whereIn('status', ['pending', 'acc'])
            ->whereDoesntHave('tugasAkhir.mahasiswa.dosenPenguji')
            ->count();

        // Data Dosen (berdasarkan profil dosen kajur yang login)
        $dosenId = $request->user()?->profileDosen?->id;

        $totalMahasiswaBimbingan = $dosenId
            ? DosenPembimbing::where('dosen_id', $dosenId)->where('status_aktif', true)->count()
            : 0;

        $totalPublikasi = $dosenId
            ? PublikasiDosen::where('dosen_id', $dosenId)->count()
            : 0;

        $mahasiswaBimbingan = $dosenId
            ? DosenPembimbing::with(['mahasiswa.tugasAkhir'])
                ->where('dosen_id', $dosenId)
                ->where('status_aktif', true)
                ->latest()
                ->limit(5)
                ->get()
            : collect();

        $publikasiTerbaru = $dosenId
            ? PublikasiDosen::where('dosen_id', $dosenId)
                ->latest()
                ->limit(5)
                ->get()
            : collect();

        return view('kajur.dashboard', compact(
            'totalMahasiswa',
            'menungguPembimbing',
            'menungguPenguji',
            'totalMahasiswaBimbingan',
            'totalPublikasi',
            'mahasiswaBimbingan',
            'publikasiTerbaru',
        ));
    }
}
