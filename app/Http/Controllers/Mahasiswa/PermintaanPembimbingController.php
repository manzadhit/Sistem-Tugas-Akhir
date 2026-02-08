<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mahasiswa\StorePermintaanPembimbingRequest;
use App\Models\PermintaanPembimbing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PermintaanPembimbingController extends Controller
{
    public function create(Request $request)
    {
        $mahasiswa = $request->user()->profileMahasiswa;

        if (!$mahasiswa) {
            abort(403, 'Profile mahasiswa belum lengkap.');
        }

        if ($mahasiswa->dosenPembimbing()->exists()) {
            return redirect()->route('mahasiswa.dashboard');
        }

        if ($mahasiswa->permintaanPembimbing()->exists()) {
            return view('mahasiswa.permintaan-pembimbing');
        }

        return view('mahasiswa.permintaan-pembimbing-form');
    }

    public function store(StorePermintaanPembimbingRequest $request)
    {
        $mahasiswa = $request->user()->profileMahasiswa;

        if (!$mahasiswa) {
            abort(403, 'Profile mahasiswa belum lengkap.');
        }

        $validated = $request->validated();

        $existing = PermintaanPembimbing::where('mahasiswa_id', $mahasiswa->id)->first();
        if ($existing?->bukti_acc_path && Storage::disk('public')->exists($existing->bukti_acc_path)) {
            Storage::disk('public')->delete($existing->bukti_acc_path);
        }

        $path = $request->file('bukti_acc')->store('bukti-acc', 'public');

        PermintaanPembimbing::updateOrCreate(
            ['mahasiswa_id' => $mahasiswa->id],
            ['judul_ta' => $validated['judul_ta'], 'bukti_acc_path' => $path, 'status' =>
            'pending']
        );

        return redirect()
            ->route('mahasiswa.permintaan-pembimbing.create')
            ->with('success', 'Permintaan pembimbing berhasil dikirim. Menunggu penetapan pembimbing dari jurusan.');
    }
}
