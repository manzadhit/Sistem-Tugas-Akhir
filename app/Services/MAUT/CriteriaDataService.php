<?php

namespace App\Services\MAUT;

use App\Models\DosenPembimbing;
use App\Models\DosenPenguji;
use App\Models\PeriodeAkademik;
use App\Models\ProfileDosen;

class CriteriaDataService
{
  public function getData($dosenIds, $context, $mahasiswa)
  {
    $jabatan = $this->getJabatanFungsional($dosenIds);
    $jumlahPublikasi = $this->getJumlahPublikasi($dosenIds);
    $beban = $this->getBebanByContext($dosenIds, $context);

    $pemerataanIpk = $context == 'pembimbing' ? $this->getPemerataanIpk($dosenIds, $mahasiswa) : [];

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
        $row['pemerataan_ipk'] = $pemerataanIpk[$id] ?? 0;
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
    $counts = DosenPembimbing::query()
      ->whereIn('dosen_id', $dosenIds)
      ->where('status_aktif', true)
      ->selectRaw('dosen_id, COUNT(*) as total')
      ->groupBy('dosen_id')
      ->pluck('total', 'dosen_id');

    $result = [];

    foreach ($dosenIds as $dosenId) {
      $result[$dosenId] = (int) ($counts[$dosenId] ?? 0);
    }

    return $result;
  }

  public function getBebanPenguji($dosenIds)
  {
    $periodeAkademik = PeriodeAkademik::query()
      ->aktif()
      ->sole();

    $counts = DosenPenguji::query()
      ->whereIn('dosen_id', $dosenIds)
      ->where('periode_akademik_id', $periodeAkademik->id)
      ->selectRaw('dosen_id, COUNT(*) as total')
      ->groupBy('dosen_id')
      ->pluck('total', 'dosen_id');

    $result = [];

    foreach ($dosenIds as $dosenId) {
      $result[$dosenId] = (int) ($counts[$dosenId] ?? 0);
    }

    return $result;
  }

  public function getPemerataanIpk($dosenIds, $mahasiswa)
  {
    $kategoriIpk = $this->getKategoriIpk($mahasiswa->ipk);

    $data = DosenPembimbing::with('mahasiswa')
      ->whereIn('dosen_id', $dosenIds)
      ->where('status_aktif', true)
      ->get()
      ->groupBy('dosen_id');

    $result = [];

    foreach ($dosenIds as $dosenId) {
      $bimbingan = $data->get($dosenId, collect());

      $jumlahKategoriSama = $bimbingan
        ->filter(fn($item) => $item->mahasiswa != null &&
          $this->getKategoriIpk($item->mahasiswa->ipk) == $kategoriIpk)
        ->count();

      $result[$dosenId] = $jumlahKategoriSama;
    }

    return $result;
  }

  protected function getKategoriIpk($ipk)
  {
    if ($ipk < 3.00) {
      return 'rendah';
    } elseif ($ipk < 3.50) {
      return 'sedang';
    } else {
      return 'tinggi';
    }
  }

  protected function getBebanByContext($dosenIds, $context)
  {
    return $context === 'penguji'
      ? $this->getBebanPenguji($dosenIds)
      : $this->getBebanBimbingan($dosenIds);
  }

  protected function mapJabatanToValue($jabatan)
  {
    return match (strtolower((string) $jabatan)) {
      'guru besar' => 5,
      'lektor kepala' => 4,
      'lektor' => 3,
      'asisten ahli' => 2,
      'tenaga pendidik' => 1,
      default => 0
    };
  }
}
