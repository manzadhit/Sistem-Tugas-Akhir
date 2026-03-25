<?php

namespace App\Services\MAUT;

use App\Models\ProfileDosen;

class CriteriaDataService
{
  public function getData($dosenIds)
  {
    $jabatan = $this->getJabatanFungsional($dosenIds);
    $jumlahPublikasi = $this->getJumlahPublikasi($dosenIds);
    $bebanBimbingan = $this->getBebanBimbingan($dosenIds);

    $result = [];

    foreach($dosenIds as $id) {
      $result[$id] = [
        'jabatan_fungsional' => $jabatan[$id] ?? 0,
        'jumlah_publikasi' => $jumlahPublikasi[$id] ?? 0,
        'beban_bimbingan' => $beban[$id] ?? 0
      ];
    }

    return $result;
  }

  public function getJabatanFungsional($dosenIds)
  {
    $dosens = ProfileDosen::whereIn('id', $dosenIds)
      ->select('id', 'jabatan_fungsional')
      ->get();

    $result = [];

    foreach ($dosens as $dosen) {
      $result[$dosen->id] = $this->mapJabatanToValue($dosen->jabatan_fungsional);
    }

    return $result;
  }

  public function getJumlahPublikasi($dosenIds)
  {
    $dosens = ProfileDosen::whereIn('id', $dosenIds)
      ->withCount('publikasi')
      ->select('id')
      ->get();

    $result = [];

    foreach ($dosens as $dosen) {
      $result[$dosen->id] = $dosen->publikasi_count;
    }

    return $result;
  }

  public function getBebanBimbingan($dosenIds)
  {
    $dosens = ProfileDosen::whereIn('id', $dosenIds)
      ->select('id', 'total_mahasiswa_dibimbing')
      ->get();

    $result = [];

    foreach ($dosens as $dosen) {
      $result[$dosen->id] = $dosen->total_mahasiswa_dibimbing;
    }

    return $result;
  }

  protected function mapJabatanToValue($jabatan)
  {
    return match (strtolower($jabatan)) {
      'guru_besar' => 5,
      'lektor_kepala' => 4,
      'lektor' => 3,
      'asisten_ahli' => 2,
      'tenaga_pendidik' => 1,
      default => 0
    };
  }
}
