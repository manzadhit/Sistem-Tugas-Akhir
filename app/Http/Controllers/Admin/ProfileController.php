<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateAdminProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('admin.profile', [
            'user' => $request->user(),
        ]);
    }

    public function update(UpdateAdminProfileRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();
        $hasChanges = false;

        if ($user->email !== $validated['email']) {
            $user->email = $validated['email'];
            $hasChanges = true;
        }

        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
            $user->must_change_password = false;
            $hasChanges = true;
        }

        if (! $hasChanges) {
            return redirect()->route('admin.profile.edit')
                ->with('warning', 'Tidak ada perubahan yang disimpan.');
        }

        $user->save();

        return redirect()->route('admin.profile.edit')
            ->with('success', 'Profil admin berhasil diperbarui.');
    }
}
