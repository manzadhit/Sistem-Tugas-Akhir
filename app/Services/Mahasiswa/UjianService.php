<?php

namespace App\Services\Mahasiswa;

use App\Models\DokumenUjian;
use App\Models\Ujian;
use Illuminate\Support\Facades\DB;

class UjianService
{
  public function getOrCreateUjian(int $tugasAkhirId, string $jenis)
  {
    return Ujian::firstOrCreate(
      ['tugas_akhir_id' => $tugasAkhirId, 'jenis_ujian' => $jenis],
      ['status' => 'draft']
    );
  }

  public function uploadDokumen(Ujian $ujian, array $files, string $kategoriFile): void
  {
    DB::transaction(function () use ($ujian, $files, $kategoriFile) {
      foreach ($files as $jenisDokumen => $file) {
        $extension = $file->getClientOriginalExtension();
        $filename  = "{$jenisDokumen}_{$ujian->jenis_ujian}_" . now()->format('YmdHi') . ".{$extension}";
        $path      = $file->storeAs("dokumen-ujian/{$ujian->jenis_ujian}/{$kategoriFile}", $filename, 'public');

        DokumenUjian::updateOrCreate(
          [
            'ujian_id'      => $ujian->id,
            'jenis_dokumen' => $jenisDokumen,
            'kategori'      => $kategoriFile,
          ],
          [
            'file_path' => $path,
            'status'    => 'pending',
            'catatan'   => null,
          ]
        );
      }
    });
  }
}
