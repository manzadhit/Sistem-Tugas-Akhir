<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mahasiswa\StorePermintaanPembimbingRequest;
use App\Models\MataKuliah;
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

        $mataKuliahOptions = MataKuliah::orderBy('nama')
            ->get(['id', 'kode', 'nama'])
            ->map(fn($mataKuliah) => [
                'id' => (string) $mataKuliah->id,
                'label' => "{$mataKuliah->kode} - {$mataKuliah->nama}",
            ])
            ->values()
            ->all();

        $permintaanPembimbing = $mahasiswa->permintaanPembimbing;

        if ($permintaanPembimbing) {
            $isRejected = $permintaanPembimbing->status_verifikasi_bukti === 'ditolak';

            if ($isRejected) {
                return view('mahasiswa.permintaan-pembimbing-form', compact(
                    'permintaanPembimbing',
                    'mataKuliahOptions',
                    'mahasiswa',
                ));
            }

            return view('mahasiswa.permintaan-pembimbing', compact('permintaanPembimbing'));
        }

        return view('mahasiswa.permintaan-pembimbing-form', [
            'mahasiswa' => $mahasiswa,
            'mataKuliahOptions' => $mataKuliahOptions,
            'permintaanPembimbing' => null,
        ]);
    }

    public function store(StorePermintaanPembimbingRequest $request)
    {
        $mahasiswa = $request->user()->profileMahasiswa;

        if (!$mahasiswa) {
            abort(403, 'Profile mahasiswa belum lengkap.');
        }

        $validated = $request->validated();

        $mahasiswa->update([
            'ipk' => $validated['ipk'],
        ]);

        $existing = PermintaanPembimbing::where('mahasiswa_id', $mahasiswa->id)->first();
        if ($existing?->bukti_acc_path && Storage::disk('local')->exists($existing->bukti_acc_path)) {
            Storage::disk('local')->delete($existing->bukti_acc_path);
        }

        $file = $request->file('bukti_acc');
        $filename = $mahasiswa->nim . '_bukti_acc_judul_' . time() . '.' . $file->extension();
        $path = $file->storeAs('bukti-acc/' . $mahasiswa->nim, $filename, 'local');

        $permintaanPembimbing = PermintaanPembimbing::updateOrCreate(
            ['mahasiswa_id' => $mahasiswa->id],
            [
                'judul_ta' => $validated['judul_ta'],
                'bukti_acc_path' => $path,
                'status' => 'pending',
                'status_verifikasi_bukti' => 'pending',
                'catatan' => null,
                'diproses_pada' => null,
            ]
        );

        $permintaanPembimbing->mataKuliah()->sync($validated['mata_kuliah_ids']);
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
