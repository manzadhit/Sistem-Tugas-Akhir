<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\DosenPembimbing;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $mahasiswa = $request->user()?->profileMahasiswa;

        if (!$mahasiswa) {
            abort(403, 'Profile mahasiswa belum lengkap.');
        }

        $dosenPembimbing = DosenPembimbing::with('dosen')->where('mahasiswa_id', $mahasiswa->id)->where('status_aktif', true)->orderby('jenis_pembimbing')->get();


        return view('mahasiswa.dashboard', compact('dosenPembimbing'));
    }
}
