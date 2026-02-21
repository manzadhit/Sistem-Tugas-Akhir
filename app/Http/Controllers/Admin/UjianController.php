<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ujian;
use App\Models\DokumenUjian;

class UjianController extends Controller
{
    public function index($jenis)
    {
        $ujians = Ujian::with(['tugasAkhir.mahasiswa', 'dokumenUjian' => function ($q) {
                $q->where('kategori', 'syarat')->where('status', 'pending');
            }])
            ->where('jenis_ujian', $jenis)
            ->where('status', 'menunggu_verifikasi')
            ->get();

        return view('admin.ujian.list-mahasiswa', compact('ujians', 'jenis'));
    }

    public function detailVerifikasi($jenis, $id)
    {
        $ujian = Ujian::with([
            'tugasAkhir.mahasiswa.dosenPembimbing.dosen',
            'tugasAkhir.mahasiswa.dosenPenguji.dosen',
            'dokumenUjian' => fn($q) => $q->where('kategori', 'syarat')->where('status', 'pending'),
            'jadwalUjian',
        ])
            ->where('jenis_ujian', $jenis)
            ->where('id', $id)
            ->first();

return view('admin.ujian.detail-verifikasi', compact('ujian', 'jenis'));
    }
}
