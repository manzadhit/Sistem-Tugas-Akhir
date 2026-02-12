<?php

namespace App\Services\Dosen;

use App\Models\Submission;
use App\Models\SubmissionFile;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

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

  public function reviewSubmission(Submission $submission, array $payload, array $files = [])
  {
    return DB::transaction(function () use ($submission, $payload, $files) {
      $submission->update([
        'review' => $payload['review'] ?? null,
        'status' => $payload['status'],
      ]);

      /** @var UploadedFile $file */
      foreach ($files as $file) {
        $filename = $file->getClientOriginalName();
        $path = $file->storeAs('submission-file', $filename, 'public');

        SubmissionFile::create([
          'submission_id' => $submission->id,
          'uploaded_by' => 'dosen',
          'file_path' => $path,
        ]);
      }

      return $submission->refresh();
    });
  }
}
