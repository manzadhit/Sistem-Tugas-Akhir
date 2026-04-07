<?php

namespace App\Services\Mahasiswa;

use App\Models\DokumenUjian;
use App\Models\JadwalUjian;
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

  public function uploadDokumen(Ujian $ujian, array $files, string $kategoriFile, string $mahasiswaNim): void
  {

    DB::transaction(function () use ($ujian, $files, $kategoriFile, $mahasiswaNim) {
      foreach ($files as $jenisDokumen => $file) {
        $extension = $file->getClientOriginalExtension();
        $filename = "{$jenisDokumen}" . ".{$extension}";
        $path = $file->storeAs("dokumen-ujian/{$mahasiswaNim}/{$ujian->jenis_ujian}/{$kategoriFile}", $filename, 'local');

        if (!$path) {
          throw new \RuntimeException("Gagal menyimpan file: {$filename}");
        }

        DokumenUjian::updateOrCreate(
          [
            'ujian_id' => $ujian->id,
            'jenis_dokumen' => $jenisDokumen,
            'kategori' => $kategoriFile,
          ],
          [
            'file_path' => $path,
            'status' => 'pending',
            'catatan' => null,
          ]
        );
      }
    });
  }

  public function simpanJadwal(Ujian $ujian, array $dataJadwal): void
  {
    JadwalUjian::updateOrCreate(
      ['ujian_id' => $ujian->id],
      [
        'tanggal_ujian' => $dataJadwal['tanggal_ujian'],
        'jam_mulai' => $dataJadwal['jam_mulai'],
        'jam_selesai' => $dataJadwal['jam_selesai'],
        'ruangan' => $dataJadwal['ruangan'],
      ]
    );
  }

  public function isDokumenLengkap(Ujian $ujian, string $jenis, string $kategori = 'syarat', string $configPrefix = 'ujian'): bool
  {
    $syaratConfig = config("{$configPrefix}.{$jenis}");
    if (!$syaratConfig) {
      return false;
    }

    $jumlahSyarat = count($syaratConfig);

    $dokumenUploaded = DokumenUjian::where('ujian_id', $ujian->id)
      ->where('kategori', $kategori)
      ->count();

    return $dokumenUploaded >= $jumlahSyarat;
  }
}
