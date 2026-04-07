<?php

namespace App\Services\Mahasiswa;

use App\Models\KajurSubmission;
use App\Models\KajurSubmissionFile;
use App\Models\TugasAkhir;
use Illuminate\Support\Facades\DB;

class kajurSubmissionService
{
  public function createKajurSubmission(int $mahasiswaId, string $jenis, ?string $catatan, string $abstrak, string $kataKunci, array $files): KajurSubmission
  {
    $tugasAkhir = TugasAkhir::with('mahasiswa')->where('mahasiswa_id', $mahasiswaId)->first();

    return DB::transaction(function () use ($tugasAkhir, $jenis, $catatan, $abstrak, $kataKunci, $files) {
      $tugasAkhir->update([
        'abstrak' => $abstrak,
        'kata_kunci' => $kataKunci,
      ]);

      $kajurSubmission = KajurSubmission::create([
        'tugas_akhir_id' => $tugasAkhir->id,
        'tahapan' => $jenis,
        'catatan' => $catatan,
      ]);

      foreach ($files as $file) {
        $file_path = $file->storeAs('kajur-submission-file/' . $tugasAkhir->mahasiswa->nim, $this->createFileName($file), 'local');

        KajurSubmissionFile::create([
          'kajur_submission_id' => $kajurSubmission->id,
          'uploaded_by' => 'mahasiswa',
          'file_path' => $file_path
        ]);
      }

      return $kajurSubmission;
    });
  }

  private function createFileName($file)
  {
    $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
    $extention = $file->getClientOriginalExtension();
    return $originalName . '_' . time() . '.' . $extention;
  }
}
