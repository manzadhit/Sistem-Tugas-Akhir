<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureMahasiswaAktif
{
    /**
     * Cek status akademik mahasiswa di setiap request.
     * Jika admin mengubah status saat mahasiswa masih login, middleware ini akan auto-logout.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->role === 'mahasiswa') {
            $status = $user->profileMahasiswa?->status_akademik;

            if (! in_array($status, ['aktif', 'lulus'])) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')->withErrors([
                    'username' => 'Sesi Anda diakhiri karena status akademik Anda saat ini: ' . ($status ?? 'tidak diketahui') . '. Silakan hubungi admin untuk informasi lebih lanjut.',
                ]);
            }
        }

        return $next($request);
    }
}
