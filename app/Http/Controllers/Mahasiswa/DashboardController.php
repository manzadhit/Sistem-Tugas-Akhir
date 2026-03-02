<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\DosenPembimbing;
use App\Models\DosenPenguji;
use App\Models\Submission;
use App\Models\TugasAkhir;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $mahasiswa = $request->user()?->profileMahasiswa;

        if (!$mahasiswa) {
            abort(403, 'Profile mahasiswa belum lengkap.');
        }

        $tugasAkhir = TugasAkhir::where('mahasiswa_id', $mahasiswa->id)->first();


        $dosenPembimbing = DosenPembimbing::with('dosen')
            ->where('mahasiswa_id', $mahasiswa->id)
            ->where('status_aktif', true)
            ->orderBy('jenis_pembimbing')->get();


        $dosenPenguji = DosenPenguji::with('dosen')
            ->where('mahasiswa_id', $mahasiswa->id)
            ->where('status_aktif', true)
            ->orderBy('jenis_penguji')->get();

        $riwayatBimbingan = Submission::with(['dosenPembimbing.dosen'])
            ->where('tugas_akhir_id', $tugasAkhir->id)
            ->latest()
            ->take(3)
            ->get();

        return view('mahasiswa.dashboard', compact('dosenPembimbing', 'tugasAkhir', 'dosenPenguji', 'riwayatBimbingan'));
    }
}
