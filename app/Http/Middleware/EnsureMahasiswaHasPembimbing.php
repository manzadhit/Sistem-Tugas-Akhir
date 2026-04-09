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
            $mahasiswa = $user->profileMahasiswa;
            $hasPembimbing = $mahasiswa?->dosenPembimbing()->exists();

            if(!$hasPembimbing) {
                return redirect()->route('mahasiswa.permintaan-pembimbing.create')->with('warning', 'Silahkan ajukan pembimbing terlebih dahulu.');
            }

            // Cek apakah mahasiswa sudah melihat halaman hasil penetapan pembimbing
            $permintaan = $mahasiswa->permintaanPembimbing;
            if ($permintaan && !$permintaan->penetapan_dilihat) {
                return redirect()->route('mahasiswa.hasil-penetapan');
            }
        }

        return $next($request);
    }
}
