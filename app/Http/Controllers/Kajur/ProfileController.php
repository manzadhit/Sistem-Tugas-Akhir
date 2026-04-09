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
        $validated = $request->validated();

        // Update foto jika ada
        $fotoPath = $profile->foto;
        if ($request->hasFile('foto')) {
            if ($fotoPath && Storage::exists($fotoPath)) {
                Storage::delete($fotoPath);
            }
            $fotoPath = $request->file('foto')->store('photos');
        }

        // Update profile dosen
        $profile->update([
            'nama_lengkap' => $validated['nama_lengkap'],
            'nidn' => $validated['nidn'],
            'jurusan' => $validated['jurusan'],
            'keahlian' => $validated['keahlian'] ?? null,
            'jabatan_fungsional' => $validated['jabatan_fungsional'] ?? null,
            'no_telp' => $validated['no_telp'] ?? null,
            'foto' => $fotoPath,
        ]);

        $profile->mataKuliah()->sync($validated['mata_kuliah_ids'] ?? []);

        // Update email
        if ($user->email !== $validated['email']) {
            $user->email = $validated['email'];
        }

        // Update password jika diisi
        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
            $user->must_change_password = false;
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
