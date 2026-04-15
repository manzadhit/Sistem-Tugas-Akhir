<?php

namespace App\Services\Dosen;

use App\Models\Submission;
use App\Models\DosenPembimbing;
use App\Models\SubmissionFile;
use App\Notifications\SubmissionReviewed;
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
      ->whereHas('tugasAkhir.mahasiswa', function ($q) use ($search) {
        $q->where('status_akademik', 'aktif')
          ->when($search, function ($q) use ($search) {
            $q->where(function ($q) use ($search) {
              $q->where('nama_lengkap', 'like', "%{$search}%")
                ->orWhere('nim', 'like', "%{$search}%");
            });
          });
      })
      ->when($tahap, function ($q) use ($tahap) {
        $q->whereHas('tugasAkhir', fn($q) => $q->where('tahapan', $tahap));
      })
      ->oldest()
      ->paginate(10)
      ->withQueryString();
  }

  public function getMahasiswaByStatus(int $dosenId, string $status, ?string $search = null)
  {
    return DosenPembimbing::with(['mahasiswa.tugasAkhir'])
      ->where('dosen_id', $dosenId)
      ->when($status === 'aktif', fn($q) => $q->where('status_aktif', true))
      ->whereHas('mahasiswa', function ($q) use ($status, $search) {
        $q->where('status_akademik', $status)
          ->when($search, function ($q) use ($search) {
            $q->where(function ($q) use ($search) {
              $q->where('nama_lengkap', 'like', "%{$search}%")
                ->orWhere('nim', 'like', "%{$search}%");
            });
          });
      })
      ->orderByDesc('tanggal_mulai')
      ->paginate(15)
      ->withQueryString();
  }

  public function reviewSubmission(Submission $submission, array $payload, array $files = [])
  {
    $mahasiswaNim = $submission->tugasAkhir->mahasiswa->nim;
    $reviewed = DB::transaction(function () use ($submission, $payload, $files, $mahasiswaNim) {
      $submission->update([
        'review' => $payload['review'] ?? null,
        'status' => $payload['status'],
      ]);

      /** @var UploadedFile $file */
      foreach ($files as $file) {
        $path = $file->storeAs('submission-files/' . $mahasiswaNim, $this->createFileName($file), 'local');

        SubmissionFile::create([
          'submission_id' => $submission->id,
          'uploaded_by' => 'dosen',
          'file_path' => $path,
        ]);
      }

      return $submission->refresh();
    });

    $mahasiswa = $reviewed->tugasAkhir->mahasiswa->user;
    $mahasiswa->notify(new SubmissionReviewed($reviewed, $payload['status']));

    return $reviewed;
  }

  private function createFileName($file)
  {
    $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
    $extention = $file->getClientOriginalExtension();
    return $originalName . '_' . time() . '.' . $extention;
  }
}
