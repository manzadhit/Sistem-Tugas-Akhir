<?php

namespace App\Http\Middleware;

use App\Models\KajurSubmission;
use App\Models\Submission;
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
        $mahasiswaId = $request->user()->profileMahasiswa->id;
        $jenis = $request->route('jenis');

        $tugasAkhir = TugasAkhir::where('mahasiswa_id', $mahasiswaId)->first();
        abort_if(!$tugasAkhir, 403, 'Tugas akhir belum tersedia.');

        $tahapan = $tugasAkhir->tahapan;
        $urutan = ['proposal' => 1, 'hasil' => 2, 'skripsi' => 3];

        if (($urutan[$jenis] ?? 0) > ($urutan[$tahapan] ?? 0)) {
            abort(403, 'Belum bisa akses ujian ini.');
        }

        $latestSubmissionPerPembimbing = Submission::query()
            ->where('tugas_akhir_id', $tugasAkhir->id)
            ->where('tahapan', $jenis)
            ->latest('id')
            ->get()
            ->groupBy('dosen_pembimbing_id')
            ->map
            ->first();

        $hasTwoAccPembimbing = $latestSubmissionPerPembimbing
            ->where('status', 'acc')
            ->count() >= 2;

        $kajurSubmissionApproved = KajurSubmission::query()
            ->where('tugas_akhir_id', $tugasAkhir->id)
            ->latest('id')
            ->value('status') === 'acc';

        if (!$hasTwoAccPembimbing || !$kajurSubmissionApproved) {
            abort(403, 'Akses ujian dibuka setelah dua pembimbing dan Ketua Jurusan menyetujui tahap ini.');
        }

        return $next($request);
    }
}
