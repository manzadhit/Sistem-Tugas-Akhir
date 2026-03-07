<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mahasiswa\StorePermintaanPembimbingRequest;
use App\Models\PermintaanPembimbing;
use App\Models\User;
use App\Notifications\NewPembimbingRequest;
use App\Notifications\PembimbingRequestReviewed;
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

        $request->user()->unreadNotifications()
            ->where('type', PembimbingRequestReviewed::class)
            ->update(['read_at' => now()]);

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
        if ($existing?->bukti_acc_path && Storage::exists($existing->bukti_acc_path)) {
            Storage::delete($existing->bukti_acc_path);
        }

        $file = $request->file('bukti_acc');
        $filename = "{$mahasiswa->nim}_bukti_acc_judul.{$file->extension()}";
        $path = $file->storeAs('bukti-acc', $filename);

        $permintaanPembimbing = PermintaanPembimbing::updateOrCreate(
            ['mahasiswa_id' => $mahasiswa->id],
            [
                'judul_ta' => $validated['judul_ta'],
                'bukti_acc_path' => $path,
                'status' =>
                    'pending'
            ]
        );

        $permintaanPembimbing->loadMissing('mahasiswa');

        User::where('role', 'kajur')
            ->get()
            ->each
            ->notify(new NewPembimbingRequest($permintaanPembimbing));

        return redirect()
            ->route('mahasiswa.permintaan-pembimbing.create')
            ->with('success', 'Permintaan pembimbing berhasil dikirim. Menunggu penetapan pembimbing dari jurusan.');
    }
}
