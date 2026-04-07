<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mahasiswa\ProfileMahasiswaRequest;
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
        $user = $request->user();
        $profile = $user->profileMahasiswa;
        $validated = $request->validated();

        // Update foto jika ada
        $fotoPath = $profile->foto;
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($fotoPath && Storage::exists($fotoPath)) {
                Storage::delete($fotoPath);
            }
            $fotoPath = $request->file('foto')->store('photos');
        }

        // Update profile mahasiswa
        $profile->update([
            'nama_lengkap' => $validated['nama_lengkap'],
            'nim' => $validated['nim'],
            'jurusan' => $validated['jurusan'],
            'angkatan' => $validated['angkatan'],
            'ipk' => $validated['ipk'] ?? null,
            'no_telp' => $validated['no_telp'] ?? null,
            'foto' => $fotoPath,
        ]);

        // Update email
        if ($user->email !== $validated['email']) {
            $user->email = $validated['email'];
        }

        // Update password jika diisi
        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('mahasiswa.profile.edit')
            ->with('success', 'Profil berhasil diperbarui.');
    }
}
