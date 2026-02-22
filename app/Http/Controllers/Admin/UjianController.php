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
        $ujians = Ujian::with([
            'tugasAkhir.mahasiswa',
            'dokumenUjian' => function ($q) {
                $q->where('kategori', 'syarat')->where('status', 'pending');
            }
        ])
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

    public function prosesVerifikasi(Request $request, $jenis, $id)
    {
        $request->validate([
            'dokumen.*.status' => 'required|in:acc,tolak',
            'dokumen.*.catatan' => 'nullable|string|max:500',
        ]);

        $ujian = Ujian::where('jenis_ujian', $jenis)->findOrFail($id);

        $adaTolak = false;

        foreach ($request->input('dokumen', []) as $dokumenId => $data) {
            DokumenUjian::where('id', $dokumenId)
                ->where('ujian_id', $ujian->id)
                ->update([
                    'status' => $data['status'],
                    'catatan' => $data['catatan'] ?? null,
                ]);

            if ($data['status'] === 'tolak') {
                $adaTolak = true;
            }
        }

        if ($adaTolak) {
            $ujian->update(['status' => 'revisi']);

            return redirect()
                ->back()
                ->with('warning', 'Beberapa berkas ditolak. Pengajuan dikembalikan ke mahasiswa.');
        }

        $ujian->update(['status' => 'menunggu_undangan']);

        return redirect()
            ->back()
            ->with('success', 'Semua berkas di-ACC. Silakan buat undangan ujian.');
    }
}
