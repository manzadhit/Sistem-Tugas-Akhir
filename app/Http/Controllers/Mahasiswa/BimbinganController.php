<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\DosenPembimbing;
use Illuminate\Http\Request;

class BimbinganController extends Controller
{
    public function index(Request $request) {
        $mahasiswa = $request->user()->profileMahasiswa;

        $pembimbing = DosenPembimbing::with('dosen')->where('mahasiswa_id', $mahasiswa->id)->orderBy('jenis_pembimbing')->get();

        return view('mahasiswa.bimbingan', compact('pembimbing'));
    }
}
