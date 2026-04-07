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
    $mahasiswaNim = $kajurSubmission->tugasAkhir->mahasiswa->nim;

    return DB::transaction(function () use ($kajurSubmission, $payload, $files, $mahasiswaNim) {
      $kajurSubmission->update([
        'status' => $payload['status'],
        'review' => $payload['review'] ?? null,
      ]);

      if ($files) {
        foreach ($files as $file) {
          $path = $file->storeAs('kajur-submission-file/' . $mahasiswaNim, $this->createFileName($file), 'local');

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
      foreach ($dosen_ids as $index => $id) {
        DosenPenguji::create([
          'mahasiswa_id' => $mahasiswaId,
          'dosen_id' => $id,
          'jenis_penguji' => 'penguji_' . ($index + 1),
        ]);
      }
    });
  }

  public function getPengujianAktifQuery(?int $periodeAktifId)
  {
    return function ($query) use ($periodeAktifId) {
      if (!$periodeAktifId) {
        return $query->whereRaw('1 = 0');
      }

      $query->whereHas('mahasiswa.tugasAkhir.ujian', function ($q) use ($periodeAktifId) {
        $q->where('periode_akademik_id', $periodeAktifId);
      });
    };
  }

  private function createFileName($file)
  {
    $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
    $extention = $file->getClientOriginalExtension();
    return $originalName . '_' . time() . '.' . $extention;
  }
}
