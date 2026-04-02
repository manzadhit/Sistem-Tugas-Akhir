@extends('layouts.app')

@section('title', 'Persetujuan Ketua Jurusan - ' . ucfirst($jenis))

@section('sidebar')
  @include('mahasiswa.sidebar')
@endsection

@section('content')
  @php
    $completionTitle = 'Selamat! Bimbingan ' . ucfirst($jenis) . ' Anda Telah Selesai';
    $completionDescription =
        'Kedua pembimbing telah menyetujui bimbingan ' .
        $jenis .
        ' Anda. Silakan upload Laporan TA untuk mendapatkan persetujuan Ketua Jurusan.';
  @endphp

  <!-- Page Banner -->
  <div
    class="relative mb-8 h-40 overflow-hidden rounded-xl bg-gradient-to-br {{ $ujianSelesai ? 'from-emerald-500 to-emerald-700' : 'from-blue-600 to-blue-700' }}">
    <div class="absolute inset-0 flex flex-col items-center justify-center px-4 text-center text-white">
      <h1 class="mb-2 text-xl sm:text-[1.75rem] md:text-[2rem] font-bold">Persetujuan Ketua Jurusan</h1>
      <p class="text-xs sm:text-sm md:text-base opacity-90">
        Upload laporan Tugas Akhir untuk mendapatkan persetujuan Ketua Jurusan
      </p>
    </div>
  </div>

  <!-- Progress -->
  <div class="mb-8 rounded-xl bg-white p-6 shadow-sm">
    <div class="relative flex justify-between">
      {{-- Garis 1: Bimbingan → Kajur (selalu hijau) --}}
      <div class="absolute left-[16%] right-[50%] top-5 h-0.5 bg-emerald-400"></div>
      {{-- Garis 2: Kajur → Selesai --}}
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

      {{-- Step 2: Persetujuan Kajur --}}
      <div class="relative z-10 flex flex-1 flex-col items-center">
        <div
          class="mb-2 flex h-10 w-10 items-center justify-center rounded-full {{ $ujianSelesai ? 'bg-emerald-500 text-white shadow-[0_0_0_4px_rgba(16,185,129,0.2)]' : 'bg-blue-600 text-white shadow-[0_0_0_4px_rgba(37,99,235,0.2)]' }}">
          <i class="{{ $ujianSelesai ? 'fas fa-check' : 'fas fa-user-check' }} text-base"></i>
        </div>
        <span
          class="text-center text-[10px] sm:text-xs font-semibold {{ $ujianSelesai ? 'text-emerald-600' : 'text-blue-600' }}">Persetujuan
          Kajur</span>
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
    <!-- Alert bimbingan selesai -->
    <div
      class="mb-6 flex items-start gap-3 rounded-xl border border-[#28a745] bg-gradient-to-br from-[#d4edda] to-[#c3e6cb] p-5">
      <i class="fas fa-check-circle mt-0.5 text-2xl text-[#28a745]"></i>
      <div class="flex-1">
        <div class="mb-1 font-semibold text-[#155724]">{{ $completionTitle }}</div>
        <div class="text-sm text-[#155724]">{{ $completionDescription }}</div>
      </div>
    </div>
  @endif

  <!-- Flash Messages -->
  <x-alert type="success" />
  <x-alert type="error" />
  <x-alert type="warning" />

  @if (isset($kajurSubmission) && $kajurSubmission->status === 'pending')
    @include('mahasiswa.bimbingan.minta-penguji.menunggu-verifikasi')
  @elseif (isset($kajurSubmission) && $kajurSubmission->status === 'acc')
    {{-- Disetujui Kajur --}}
    <div class="mx-auto mb-8 max-w-3xl overflow-hidden rounded-xl bg-white shadow-sm">
      <div class="border-b border-slate-200 bg-slate-50 px-8 py-6 text-center text-slate-900">
        <h3 class="mb-2 text-xl font-bold">Persetujuan Diberikan</h3>
        <span
          class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-[0.85rem] py-[0.3rem] text-[0.8rem] font-semibold text-emerald-800">
          <i class="fas fa-check-circle"></i> Disetujui oleh Ketua Jurusan
        </span>
      </div>
      <div class="p-8 text-center">
        <div class="flex flex-col items-center gap-4">
          <div class="flex h-16 w-16 items-center justify-center rounded-full bg-emerald-100">
            <i class="fas fa-check-circle text-3xl text-emerald-500"></i>
          </div>
          <p class="text-sm text-slate-500">
            Laporan Anda telah disetujui oleh Ketua Jurusan.<br>
            Silakan lanjutkan ke tahap ujian {{ ucfirst($jenis) }}.
          </p>
          <a href="{{ route('mahasiswa.ujian', $jenis) }}"
            class="mt-2 inline-flex items-center gap-2 rounded-lg bg-blue-600 px-6 py-3 text-sm font-medium text-white hover:bg-blue-700 transition-all">
            <i class="fas fa-arrow-right"></i> Ke Halaman Ujian
          </a>
        </div>
      </div>
    </div>
  @else
    @include('mahasiswa.bimbingan.minta-penguji.upload-laporan')
  @endif

@endsection
