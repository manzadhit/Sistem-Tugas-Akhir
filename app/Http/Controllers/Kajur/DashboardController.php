<?php

namespace App\Http\Controllers\Kajur;

use App\Http\Controllers\Controller;
use App\Models\PermintaanPembimbing;
use App\Models\ProfileMahasiswa;

class DashboardController extends Controller
{
    public function index()
    {
        $totalMahasiswa = ProfileMahasiswa::count();

        $menungguPembimbing = PermintaanPembimbing::where('status', 'pending')->count();

        // $menungguPenguji = ProfileMahasiswa::query()
        //     ->whereHas('dosenPembimbing')
        //     ->whereDoesntHave('dosenPenguji')
        //     ->count();

        return view('kajur.dashboard', compact(
            'totalMahasiswa',
            'menungguPembimbing',
        ));
    }
}
