<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Undangan Ujian Seminar Proposal</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Times New Roman', Times, serif;
      font-size: 12pt;
      color: #000;
      padding: 40px 113px;
    }

    /* ── Kop Surat ── */
    .kop {
      display: table;
      width: 100%;
      border-bottom: 4px double #000;
      padding-bottom: 10px;
      margin-bottom: 20px;
    }

    .kop-logo {
      display: table-cell;
      width: 90px;
      vertical-align: middle;
      text-align: center;
    }

    .kop-logo img {
      width: 80px;
      height: auto;
    }

    .kop-teks {
      display: table-cell;
      vertical-align: middle;
      text-align: center;
      line-height: 1.4;
    }

    .kop-teks .instansi {
      font-size: 1rem;
      font-weight: normal;
      text-transform: uppercase;
    }

    .kop-teks .universitas {
      font-size: 1rem;
      font-weight: normal;
      text-transform: uppercase;
    }

    .kop-teks .fakultas {
      font-size: 1rem;
      font-weight: bold;
      text-transform: uppercase;
    }

    .kop-teks .alamat {
      font-size: 9pt;
      font-weight: normal;
    }

    /* ── Surat ── */
    .surat-header {
      display: table;
      width: 100%;
      margin-bottom: 16px;
    }

    .surat-header-kiri {
      display: table-cell;
      width: 60%;
      vertical-align: top;
    }

    .surat-header-kanan {
      display: table-cell;
      width: 40%;
      vertical-align: top;
      text-align: right;
    }

    table.meta {
      border-collapse: collapse;
      font-size: 12pt;
    }

    table.meta td {
      padding: 1px 0;
      vertical-align: top;
    }

    table.meta td.label {
      width: 60px;
    }

    table.meta td.sep {
      width: 16px;
      text-align: center;
    }

    .pembuka {
      margin-bottom: 10px;
      line-height: 1.6;
    }

    table.detail {
      border-collapse: collapse;
      margin-bottom: 16px;
    }

    table.detail td {
      padding: 2px 0;
      vertical-align: top;
      font-size: 12pt;
    }

    table.detail td.label {
      width: 130px;
      font-weight: normal;
    }

    table.detail td.sep {
      width: 16px;
    }

    .section-title {
      margin-bottom: 6px;
    }

    table.panitia {
      border-collapse: collapse;
      margin-bottom: 20px;
    }

    table.panitia td {
      padding: 2px 0;
      vertical-align: top;
      font-size: 12pt;
    }

    table.panitia td.label {
      width: 130px;
    }

    table.panitia td.sep {
      width: 16px;
    }

    .penutup {
      line-height: 1.6;
      margin-bottom: 30px;
    }

    /* ── Tanda Tangan ── */
    .ttd {
      float: right;
      text-align: center;
      width: 260px;
      position: relative;
    }

    .ttd-img {
      position: absolute;
      top: 0;
      left: -40px;
      width: 250px;
      z-index: 10;
    }

    .ttd .jabatan {
      margin-bottom: 100px;
      /* ruang tanda tangan */
    }

    .ttd .nama {
      font-weight: bold;
      padding-top: 4px;
      z-index: 20;
    }

    .ttd .nip {
      font-size: 11pt;
      z-index: 20;
    }
  </style>
</head>

<body>

  {{-- Kop Surat --}}
  <div class="kop">
    <div class="kop-logo">
      {{-- Ganti src dengan path logo yang benar, misal: public_path('images/logo-uho.png') --}}
      <img src="{{ public_path('images/logo-uho.png') }}" alt="Logo UHO">
    </div>
    <div class="kop-teks">
      <div class="instansi">Kementerian Pendidikan Tinggi, Sains, dan Teknologi</div>
      <div class="universitas">Universitas Halu Oleo</div>
      <div class="fakultas">Fakultas Teknik</div>
      <div class="alamat">
        Kampus Hijau Bumi Tridharma Anduonohu, Jln. H.E.A. Mokodompit<br>
        Tlp. (0401) 3194163, 3194347, Fax (0401) 3190006, Kenadri 93232<br>
        Laman : www.uho.ac.id
      </div>
    </div>
  </div>

  {{-- Header Surat --}}
  <div class="surat-header">
    <div class="surat-header-kiri">
      <table class="meta">
        <tr>
          <td class="label">Nomor</td>
          <td class="sep">:</td>
          <td>{{ $nomor }}</td>
        </tr>
        <tr>
          <td class="label">Hal</td>
          <td class="sep">:</td>
          <td>{{ $hal }}</td>
        </tr>
        <tr>
          <td class="label">Kepada</td>
          <td class="sep">:</td>
          <td>Yth. Bapak / Ibu<br>Di -<br>&nbsp;&nbsp;&nbsp;&nbsp;T e m p a t</td>
        </tr>
      </table>
    </div>
    <div class="surat-header-kanan">
      Kendari, {{ $tanggal_surat }}
    </div>
  </div>

  {{-- Pembuka --}}
  <div class="pembuka">
    Dengan hormat,<br>
    Kami mengundang Bapak/Ibu untuk menghadiri Ujian {{ $jenis_ujian }} Mahasiswa tersebut di bawah ini :
  </div>

  {{-- Detail Mahasiswa --}}
  <table class="detail">
    <tr>
      <td class="label">Nama</td>
      <td class="sep">:</td>
      <td>{{ strtoupper($mahasiswa['nama']) }}</td>
    </tr>
    <tr>
      <td class="label">NIM</td>
      <td class="sep">:</td>
      <td>{{ $mahasiswa['nim'] }}</td>
    </tr>
    <tr>
      <td class="label">Judul Tugas Akhir</td>
      <td class="sep">:</td>
      <td style="line-height:1.5">{{ $mahasiswa['judul'] }}</td>
    </tr>
  </table>

  {{-- Waktu & Tempat --}}
  <div class="section-title">Yang dilaksanakan pada :</div>
  <table class="detail">
    <tr>
      <td class="label">Hari</td>
      <td class="sep">:</td>
      <td>{{ $jadwal['hari'] }}</td>
    </tr>
    <tr>
      <td class="label">Jam</td>
      <td class="sep">:</td>
      <td>{{ $jadwal['jam'] }}</td>
    </tr>
    <tr>
      <td class="label">Tempat</td>
      <td class="sep">:</td>
      <td>{{ $jadwal['tempat'] }}</td>
    </tr>
  </table>

  {{-- Panitia --}}
  <div class="section-title">Dengan susunan panitia penguji sebagai berikut :</div>
  <table class="panitia">
    <tr>
      <td class="label">Ketua Sidang</td>
      <td class="sep">:</td>
      <td>{{ $panitia['ketua_sidang'] }}</td>
    </tr>
    <tr>
      <td class="label">Sekertaris</td>
      <td class="sep">:</td>
      <td>{{ $panitia['sekretaris'] }}</td>
    </tr>
    <tr>
      <td colspan="3">&nbsp;</td>
    </tr>
    <tr>
      <td class="label">Penguji I</td>
      <td class="sep">:</td>
      <td>{{ $panitia['penguji'][0] }}</td>
    </tr>
    <tr>
      <td class="label">Penguji II</td>
      <td class="sep">:</td>
      <td>{{ $panitia['penguji'][1] }}</td>
    </tr>
    <tr>
      <td class="label">Penguji III</td>
      <td class="sep">:</td>
      <td>{{ $panitia['penguji'][2] }}</td>
    </tr>
    <tr>
      <td colspan="3">&nbsp;</td>
    </tr>
    <tr>
      <td class="label">Pembimbing I</td>
      <td class="sep">:</td>
      <td>{{ $panitia['pembimbing'][0] }}</td>
    </tr>
    <tr>
      <td class="label">Pembimbing II</td>
      <td class="sep">:</td>
      <td>{{ $panitia['pembimbing'][1] }}</td>
    </tr>
  </table>

  {{-- Penutup --}}
  <div class="penutup">
    Demikian undangan ini kami sampaikan kepada Bapak/Ibu, atas kerjasama yang baik diucapkan terima kasih.
  </div>

  {{-- Tanda Tangan --}}
  <div class="ttd">
    <div class="jabatan">
      An. Ketua Jurusan Informatika,<br>
      Sekretaris Jurusan Informatika,
    </div>
    <img class="ttd-img" src="{{ public_path('images/TTD.webp') }}" width="200" alt="">
    <div class="nama">{{ $penandatangan['nama'] }}</div>
    <div class="nip">NIP. {{ $penandatangan['nip'] }}</div>
  </div>

</body>

</html>
