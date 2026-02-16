<?php

namespace App\Services\Kajur;

use App\Models\DosenPenguji;
use App\Models\KajurSubmission;
use App\Models\KajurSubmissionFile;
use Illuminate\Support\Facades\DB;

class PenetapanPengujiService
{
  public function verifyLaporan(KajurSubmission $kajurSubmission, array $payload, ?array $files)
  {
    return DB::transaction(function () use ($kajurSubmission, $payload, $files) {
      $kajurSubmission->update([
        'status' => $payload['status'],
        'review' => $payload['review'] ?? null,
      ]);

      if ($files) {
        foreach ($files as $file) {
          $filename = $file->getClientOriginalName();
          $path = $file->storeAs('kajur-submission-file', $filename, 'public');

          KajurSubmissionFile::create([
            'kajur_submission_id' => $kajurSubmission->id,
            'uploaded_by' => 'kajur',
            'file_path' => $path,
          ]);
        }
      }

      return $kajurSubmission->refresh();
    });
  }

  public function tetapkanPenguji(int $mahasiswaId, array $dosen_ids)
  {
    return DB::transaction(function () use ($mahasiswaId, $dosen_ids) {
      foreach($dosen_ids as $index => $id) {
        DosenPenguji::create([
          'mahasiswa_id' => $mahasiswaId,
          'dosen_id' => $id,
          'jenis_penguji' => 'penguji_'.$index + 1,
        ]);
      }
    });
  }
}
