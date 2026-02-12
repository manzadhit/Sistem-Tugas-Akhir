<?php

namespace App\Services\Dosen;

use App\Models\Submission;

class BimbinganService
{
  public function getPendingSubmissionByDosen(int $dosenId)
  {
    return Submission::with(['tugasAkhir.mahasiswa', 'dosenPembimbing'])
      ->where('status', 'pending')
      ->whereHas('dosenPembimbing', function ($q) use ($dosenId) {
        $q->where('dosen_id', $dosenId)
          ->where('status_aktif', true);
      })
      ->oldest()
      ->paginate(10)
      ->withQueryString();
  }
}
