<?php

namespace App\Services\Admin;

use App\Models\Ujian;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class UndanganPdfService
{
  /**
   * Menyiapkan data untuk template PDF undangan berdasarkan Ujian.
   */
  public function buildData(Ujian $ujian, array $dataSurat = []): array
  {
    Carbon::setLocale('id');

    $mahasiswa = $ujian->tugasAkhir->mahasiswa;
    $tugasAkhir = $ujian->tugasAkhir;
    $jadwal = $ujian->jadwalUjian;

    $pembimbing = $mahasiswa->dosenPembimbing
      ->sortBy('jenis_pembimbing')
      ->map(fn($dp) => $dp->dosen->nama_lengkap)
      ->values()
      ->toArray();

    // Penguji – ambil dari relasi jika tersedia, fallback statis
    $penguji = $mahasiswa->dosenPenguji
      ->sortBy('jenis_penguji')
      ->map(fn($dp) => $dp->dosen->nama_lengkap)
      ->values()
      ->toArray();

    $ketuaSidang = $mahasiswa->dosenPenguji
      ->where('jenis_penguji', 'penguji_1')
      ->first()
      ?->dosen->nama_lengkap ?? '-';

    return [
      'nomor' => $dataSurat['nomor'] ?? '-',
      'hal' => $dataSurat['hal'] ?? 'Undangan Ujian ' . ucwords(str_replace('_', ' ', $ujian->jenis_ujian)),
      'tanggal_surat' => $dataSurat['tanggal_surat'] ?? Carbon::now()->locale('id')->isoFormat('D MMMM Y'),
      'jenis_ujian' => ucwords(str_replace('_', ' ', $ujian->jenis_ujian)),

      'mahasiswa' => [
        'nama' => $mahasiswa->nama_lengkap,
        'nim' => $mahasiswa->nim,
        'judul' => $tugasAkhir->judul,
      ],

      'jadwal' => [
        'hari' => $jadwal
          ? Carbon::parse($jadwal->tanggal_ujian)->locale('id')->isoFormat('dddd, D MMMM Y')
          : '-',
        'jam' => $jadwal
          ? $jadwal->jam_mulai->format('H:i') . ' - ' . $jadwal->jam_selesai->format('H:i') . ' WITA'
          : '-',
        'tempat' => $jadwal?->ruangan ?? '-',
      ],

      'panitia' => [
        'ketua_sidang' => $ketuaSidang,
        'sekretaris' => $dataSurat['sekretaris'] ?? '-',
        'penguji' => $penguji,
        'pembimbing' => $pembimbing,
      ],

      'penandatangan' => $dataSurat['penandatangan'] ?? [
        'nama' => '-',
        'nip' => '-',
      ],
    ];
  }

  /**
   * Generate instance PDF dari data yang sudah diproses.
   */
  public function generate(array $data): \Barryvdh\DomPDF\PDF
  {
    return Pdf::loadView('pdf.undangan', $data)
      ->setPaper([0, 0, 595, 936], 'portrait');
  }
}
