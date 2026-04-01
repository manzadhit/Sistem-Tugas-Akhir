<?php

namespace App\Services\Mahasiswa;

use App\Models\KajurSubmission;
use App\Models\KajurSubmissionFile;
use App\Models\TugasAkhir;
use Illuminate\Support\Facades\DB;

class kajurSubmissionService
{
  public function createKajurSubmission(int $mahasiswaId, ?string $catatan, string $abstrak, string $kataKunci, array $files): KajurSubmission
  {
    $tugasAkhir = TugasAkhir::where('mahasiswa_id', $mahasiswaId)->first();

    return DB::transaction(function () use ($tugasAkhir, $catatan, $abstrak, $kataKunci, $files) {
      $tugasAkhir->update([
        'abstrak' => $abstrak,
        'kata_kunci' => $kataKunci,
      ]);

      $kajurSubmission = KajurSubmission::create([
        'tugas_akhir_id' => $tugasAkhir->id,
        'catatan' => $catatan,
      ]);

      foreach ($files as $file) {
        $fileName = $file->getClientOriginalName();
        $file_path = $file->storeAs('kajur-submission-file', $fileName);

        KajurSubmissionFile::create([
          'kajur_submission_id' => $kajurSubmission->id,
          'uploaded_by' => 'mahasiswa',
          'file_path' => $file_path
        ]);
      }

      return $kajurSubmission;
    });
  }
}