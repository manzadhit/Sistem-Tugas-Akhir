<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RequiredPasswordChangeController extends Controller
{
    /**
     * Display the required password change form.
     */
    public function edit(Request $request): View|RedirectResponse
    {
        if (! $request->user()?->requiresPasswordChange()) {
            return redirect()->route('dashboard');
        }

        return view('auth.required-password-change');
    }

    /**
     * Update the password for users who must replace their default password.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        abort_unless($user?->requiresPasswordChange(), 403);

        $validated = $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::min(8)
                ->mixedCase()
                ->symbols()],
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
            'must_change_password' => false,
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Password berhasil diperbarui. Silakan lanjut menggunakan sistem.');
    }
}
