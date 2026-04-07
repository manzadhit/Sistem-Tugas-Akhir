<?php

namespace App\Http\Controllers\Kajur;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dosen\ProfileDosenRequest;
use App\Models\MataKuliah;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $user = $request->user();
        $profile = $user->profileDosen()->with('mataKuliah')->first();
        $mataKuliahOptions = $this->mataKuliahOptions();

        return view('kajur.profile', compact('user', 'profile', 'mataKuliahOptions'));
    }

    public function update(ProfileDosenRequest $request): RedirectResponse
    {
        $user = $request->user();
        $profile = $user->profileDosen;

        // Update foto jika ada
        $fotoPath = $profile->foto;
        if ($request->hasFile('foto')) {
            if ($fotoPath && Storage::exists($fotoPath)) {
                Storage::delete($fotoPath);
            }
            $fotoPath = $request->file('foto')->store('photos');
        }

        $profile->update([
            'nama_lengkap' => $request->nama_lengkap,
            'nidn' => $request->nidn,
            'jurusan' => $request->jurusan,
            'keahlian' => $request->keahlian,
            'jabatan_fungsional' => $request->jabatan_fungsional,
            'no_telp' => $request->no_telp,
            'foto' => $fotoPath,
        ]);

        $profile->mataKuliah()->sync($request->validated('mata_kuliah_ids', []));

        // Update email
        if ($user->email !== $request->email) {
            $user->email = $request->email;
        }

        // Update password jika diisi
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('kajur.profile.edit')
            ->with('success', 'Profil berhasil diperbarui.');
    }

    protected function mataKuliahOptions(): array
    {
        return MataKuliah::orderBy('nama')
            ->get(['id', 'kode', 'nama'])
            ->map(fn ($mataKuliah) => [
                'id' => (string) $mataKuliah->id,
                'label' => "{$mataKuliah->kode} - {$mataKuliah->nama}",
            ])
            ->values()
            ->all();
    }
}
