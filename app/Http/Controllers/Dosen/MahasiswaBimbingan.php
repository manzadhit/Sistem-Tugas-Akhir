<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\DosenPembimbing;
use App\Services\Dosen\BimbinganService;
use Illuminate\Http\Request;

class MahasiswaBimbingan extends Controller
{
    public function __construct(protected BimbinganService $bimbinganService)
    {}

    public function index(Request $request) 
    {
        $dosenId = $request->user()?->profileDosen->id;

        $mahasiswaBimbingan = DosenPembimbing::where('dosen_id', $dosenId)->get();

        $totalMahasiswaBimbingan = $mahasiswaBimbingan->count();

        $pendingSubmissions = $this->bimbinganService->getPendingSubmissionByDosen($dosenId);


        return view('dosen.bimbingan', compact('mahasiswaBimbingan', 'totalMahasiswaBimbingan', 'pendingSubmissions'));
    }
}
