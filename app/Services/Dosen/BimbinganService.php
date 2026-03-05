<?php

namespace App\Services\Dosen;

use App\Models\Submission;
use App\Models\DosenPembimbing;
use App\Models\SubmissionFile;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class BimbinganService
{
  public function getPendingSubmissionByDosen(int $dosenId, ?string $search = null, ?string $tahap = null)
  {
    return Submission::with(['tugasAkhir.mahasiswa', 'dosenPembimbing'])
      ->where('status', 'pending')
      ->whereHas('dosenPembimbing', function ($q) use ($dosenId) {
        $q->where('dosen_id', $dosenId)
          ->where('status_aktif', true);
      })
      ->when($search, function ($q) use ($search) {
        $q->whereHas('tugasAkhir.mahasiswa', function ($q) use ($search) {
          $q->where('nama_lengkap', 'like', "%{$search}%")
            ->orWhere('nim', 'like', "%{$search}%");
        });
      })
      ->when($tahap, function ($q) use ($tahap) {
        $q->whereHas('tugasAkhir', fn($q) => $q->where('tahapan', $tahap));
      })
      ->oldest()
      ->paginate(10)
      ->withQueryString();
  }

  public function getAllMahasiswaBimbingan(int $dosenId, ?string $search = null)
  {
    return DosenPembimbing::with(['mahasiswa.tugasAkhir'])
      ->where('dosen_id', $dosenId)
      ->where('status_aktif', true)
      ->when($search, function ($q) use ($search) {
        $q->whereHas('mahasiswa', function ($q) use ($search) {
          $q->where('nama_lengkap', 'like', "%{$search}%")
            ->orWhere('nim', 'like', "%{$search}%");
        });
      })
      ->paginate(15)
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
        $path = $file->storeAs('submission-file', $filename);

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
