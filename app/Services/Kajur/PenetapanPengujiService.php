<?php

namespace App\Services\Kajur;

use App\Models\DosenPenguji;
use App\Models\KajurSubmission;
use App\Models\KajurSubmissionFile;
use App\Models\PeriodeAkademik;
use App\Models\Ujian;
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

  public function tetapkanPenguji(int $mahasiswaId, int $tugasAkhirId, array $dosen_ids)
  {
    return DB::transaction(function () use ($mahasiswaId, $tugasAkhirId, $dosen_ids) {
      foreach ($dosen_ids as $index => $id) {
        DosenPenguji::create([
          'mahasiswa_id' => $mahasiswaId,
          'dosen_id' => $id,
          'jenis_penguji' => 'penguji_' . ($index + 1),
        ]);
      }

      // Buat record ujian proposal langsung saat penguji ditetapkan,
      // agar beban pengujian periode langsung terhitung.
      $periodeAktifId = PeriodeAkademik::aktif()->value('id');

      Ujian::firstOrCreate(
        ['tugas_akhir_id' => $tugasAkhirId, 'jenis_ujian' => 'proposal'],
        [
          'status' => 'draft',
          'periode_akademik_id' => $periodeAktifId,
        ]
      );
    });
  }

  public function getPengujianPeriodeQuery(?int $periodeAktifId)
  {
    return function ($query) use ($periodeAktifId) {
      if (!$periodeAktifId) {
        return $query->whereRaw('1 = 0');
      }

      $query->whereHas('mahasiswa.tugasAkhir.ujian', function ($q) use ($periodeAktifId) {
        $q->where('jenis_ujian', 'proposal')
          ->where('periode_akademik_id', $periodeAktifId);
      });
    };
  }

  public function getPengujianAktifQuery()
  {
    return function ($query) {
      $query->whereHas('mahasiswa', function ($q) {
        $q->where('status_akademik', 'aktif')
          ->whereHas('tugasAkhir.ujian', function ($q2) {
            $q2->where('jenis_ujian', 'proposal')->where('status', 'selesai');
          });
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
