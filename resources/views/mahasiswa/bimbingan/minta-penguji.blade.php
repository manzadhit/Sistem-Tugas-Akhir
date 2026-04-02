@extends('layouts.app')

@section('title', 'Tahap Ketua Jurusan')

@section('sidebar')
  @include('mahasiswa.sidebar')
@endsection

@section('content')
  @php
    $kajurStepLabel = $jenis === 'proposal' ? 'Minta Penguji' : 'Persetujuan Kajur';
    $pageTitle = $jenis === 'proposal' ? 'Pengajuan Penguji' : 'Persetujuan Ketua Jurusan';
    $pageDescription =
        $jenis === 'proposal'
            ? 'Upload laporan Tugas Akhir untuk pengajuan penguji ke Ketua Jurusan'
            : 'Upload laporan Tugas Akhir untuk mendapatkan persetujuan Ketua Jurusan';
    $completionTitle =
        $jenis === 'proposal'
            ? 'Selamat! Bimbingan Proposal Anda Telah Selesai'
            : 'Selamat! Bimbingan ' . ucfirst($jenis) . ' Anda Telah Selesai';
    $completionDescription =
        $jenis === 'proposal'
            ? 'Kedua pembimbing telah menyetujui proposal Anda. Silakan upload Laporan TA untuk mengajukan penguji.'
            : 'Kedua pembimbing telah menyetujui bimbingan ' .
                $jenis .
                ' Anda. Silakan upload Laporan TA untuk mendapatkan persetujuan Ketua Jurusan.';
  @endphp

  <!-- Page Banner -->
  <div
    class="relative mb-8 h-40 overflow-hidden rounded-xl bg-gradient-to-br {{ $ujianSelesai ? 'from-emerald-500 to-emerald-700' : 'from-blue-600 to-blue-700' }}">
    <div class="absolute inset-0 flex flex-col items-center justify-center px-4 text-center text-white">
      <h1 class="mb-2 text-xl sm:text-[1.75rem] md:text-[2rem] font-bold">{{ $pageTitle }}</h1>
      <p class="text-xs sm:text-sm md:text-base opacity-90">
        {{ $pageDescription }}
      </p>
    </div>
  </div>

  <!-- Progress -->
  <div class="mb-8 rounded-xl bg-white p-6 shadow-sm">
    <div class="relative flex justify-between">
      {{-- Garis 1: Bimbingan → Kajur (selalu hijau) --}}
      <div class="absolute left-[16%] right-[50%] top-5 h-0.5 bg-emerald-400"></div>
      {{-- Garis 2: Kajur → Selesai (hijau jika selesai, abu jika belum) --}}
      <div class="absolute left-[50%] right-[16%] top-5 h-0.5 {{ $ujianSelesai ? 'bg-emerald-400' : 'bg-gray-200' }}">
      </div>

      {{-- Step 1: Bimbingan (selesai) --}}
      <div class="relative z-10 flex flex-1 flex-col items-center">
        <div
          class="mb-2 flex h-10 w-10 items-center justify-center rounded-full bg-emerald-500 text-white shadow-[0_0_0_4px_rgba(16,185,129,0.2)]">
          <i class="fas fa-check text-base"></i>
        </div>
        <span class="text-center text-[10px] sm:text-xs font-semibold text-emerald-600">Bimbingan</span>
      </div>

      {{-- Step 2: Tahap Kajur --}}
      <div class="relative z-10 flex flex-1 flex-col items-center">
        <div
          class="mb-2 flex h-10 w-10 items-center justify-center rounded-full {{ $ujianSelesai ? 'bg-emerald-500 text-white shadow-[0_0_0_4px_rgba(16,185,129,0.2)]' : 'bg-blue-600 text-white shadow-[0_0_0_4px_rgba(37,99,235,0.2)]' }}">
          <i class="{{ $ujianSelesai ? 'fas fa-check' : 'fas fa-user-check' }} text-base"></i>
        </div>
        <span
          class="text-center text-[10px] sm:text-xs font-semibold {{ $ujianSelesai ? 'text-emerald-600' : 'text-blue-600' }}">{{ $kajurStepLabel }}</span>
      </div>

      {{-- Step 3: Selesai --}}
      <div class="relative z-10 flex flex-1 flex-col items-center">
        <div
          class="mb-2 flex h-10 w-10 items-center justify-center rounded-full {{ $ujianSelesai ? 'bg-emerald-500 text-white shadow-[0_0_0_4px_rgba(16,185,129,0.2)]' : 'bg-gray-200 text-gray-400' }}">
          <i class="{{ $ujianSelesai ? 'fas fa-check' : 'fas fa-flag-checkered' }} text-base"></i>
        </div>
        <span
          class="text-center text-[10px] sm:text-xs font-medium {{ $ujianSelesai ? 'text-emerald-600 font-semibold' : 'text-gray-500' }}">Selesai</span>
      </div>
    </div>
  </div>


  @if (!isset($kajurSubmission))
    <!-- Alert -->
    <div
      class="mb-6 flex items-start gap-3 rounded-xl border border-[#28a745] bg-gradient-to-br from-[#d4edda] to-[#c3e6cb] p-5">
      <i class="fas fa-check-circle mt-0.5 text-2xl text-[#28a745]"></i>
      <div class="flex-1">
        <div class="mb-1 font-semibold text-[#155724]">{{ $completionTitle }}</div>
        <div class="text-sm text-[#155724]">
          {{ $completionDescription }}
        </div>
      </div>
    </div>
  @endif

  <!-- Flash Messages -->
  <x-alert type="success" />
  <x-alert type="error" />
  <x-alert type="warning" />


  @if (isset($kajurSubmission) && $kajurSubmission->status === 'pending')
    @include('mahasiswa.bimbingan.minta-penguji.menunggu-verifikasi')
  @elseif (isset($kajurSubmission) && $kajurSubmission->status === 'acc' && $dosenPenguji->isNotEmpty())
    @include('mahasiswa.bimbingan.minta-penguji.penguji-ditetapkan')
  @elseif (isset($kajurSubmission) && $kajurSubmission->status === 'acc')
    @include('mahasiswa.bimbingan.minta-penguji.menunggu-penetapan-penguji')
  @else
    @include('mahasiswa.bimbingan.minta-penguji.upload-laporan')
  @endif
@endsection
