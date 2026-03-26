<?php

namespace App\Services\MAUT;

use App\Models\ProfileDosen;

class CriteriaDataService
{
  public function getData($dosenIds, $context = 'pembimbing')
  {
    $jabatan = $this->getJabatanFungsional($dosenIds);
    $jumlahPublikasi = $this->getJumlahPublikasi($dosenIds);
    $beban = $this->getBebanByContext($dosenIds, $context);

    $result = [];

    foreach ($dosenIds as $id) {
      $row = [
        'jabatan_fungsional' => $jabatan[$id] ?? 0,
        'jumlah_publikasi' => $jumlahPublikasi[$id] ?? 0,
      ];

      if ($context === 'penguji') {
        $row['beban_pengujian'] = $beban[$id] ?? 0;
      } else {
        $row['beban_bimbingan'] = $beban[$id] ?? 0;
      }

      $result[$id] = $row;
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

  public function getBebanPenguji($dosenIds)
  {
    $dosens = ProfileDosen::whereIn('id', $dosenIds)
      ->select('id', 'total_mahasiswa_diuji')
      ->get();

    $result = [];

    foreach ($dosens as $dosen) {
      $result[$dosen->id] = $dosen->total_mahasiswa_diuji;
    }

    return $result;
  }

  protected function getBebanByContext($dosenIds, $context)
  {
    return $context === 'penguji'
      ? $this->getBebanPenguji($dosenIds)
      : $this->getBebanBimbingan($dosenIds);
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
