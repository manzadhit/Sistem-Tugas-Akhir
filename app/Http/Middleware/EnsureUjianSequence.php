<?php

namespace App\Http\Middleware;

use App\Models\TugasAkhir;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUjianSequence
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $mahasiswaId =$request->user()->profileMahasiswa->id;
        $jenis = $request->route('jenis');

        $tahapan = TugasAkhir::where('mahasiswa_id', $mahasiswaId)->value('tahapan');
        
        $urutan = ['proposal' => 1, 'hasil' => 2, 'skripsi' => 3];

        if($urutan[$jenis] > $urutan[$tahapan]) {
            abort(403, 'Belum bisa akses ujian ini.');
        }

        return $next($request);
    }
}
