<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureMahasiswaHasPembimbing
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if($user?->role === 'mahasiswa') {
            $hasPembimbing = $user->profileMahasiswa?->dosenPembimbing()->exists();

            if(!$hasPembimbing) {
                return redirect()->route('mahasiswa.permintaan-pembimbing.create')->with('warning', 'Silahkan ajukan pembimbing terlebih dahulu.');
            }
        }

        return $next($request);
    }
}
