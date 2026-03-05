<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileMahasiswaRequest;
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
        $profile = $user->profileMahasiswa;

        return view('mahasiswa.profile', compact('user', 'profile'));
    }

    public function update(ProfileMahasiswaRequest $request): RedirectResponse
    {
        $user    = $request->user();
        $profile = $user->profileMahasiswa;

        // Update foto jika ada
        $fotoPath = $profile->foto;
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($fotoPath && Storage::disk('public')->exists($fotoPath)) {
                Storage::disk('public')->delete($fotoPath);
            }
            $fotoPath = $request->file('foto')->store('photos', 'public');
        }

        // Update profile mahasiswa
        $profile->update([
            'nama_lengkap'  => $request->nama_lengkap,
            'nim'           => $request->nim,
            'jurusan'       => $request->jurusan,
            'program_studi' => $request->program_studi,
            'angkatan'      => $request->angkatan,
            'ipk'           => $request->ipk,
            'no_telp'       => $request->no_telp,
            'foto'          => $fotoPath,
        ]);

        // Update email
        if ($user->email !== $request->email) {
            $user->email = $request->email;
        }

        // Update password jika diisi
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('mahasiswa.profile.edit')
            ->with('success', 'Profil berhasil diperbarui.');
    }
}
