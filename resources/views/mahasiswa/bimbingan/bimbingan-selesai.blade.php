@extends('layouts.app')

@section('title', 'Bimbingan Selesai')

@section('sidebar')
  @include('mahasiswa.sidebar')
@endsection

@section('content')
  @php
    $kajurStepLabel = $jenis === 'proposal' ? 'Minta Penguji' : 'Persetujuan Kajur';
  @endphp

  {{-- Page Banner --}}
  <div class="relative h-40 rounded-xl overflow-hidden mb-8 bg-gradient-to-br from-emerald-500 to-emerald-700">
    <div class="absolute inset-0 flex flex-col items-center justify-center text-white text-center px-4">
      <i class="fas fa-graduation-cap text-3xl sm:text-4xl mb-3"></i>
      <h1 class="text-xl sm:text-2xl md:text-[1.75rem] font-bold mb-1">Bimbingan {{ ucfirst($jenis) }} Selesai</h1>
      <p class="text-xs sm:text-sm md:text-base opacity-90">
        Seluruh rangkaian bimbingan dan ujian {{ ucfirst($jenis) }} telah selesai.
      </p>
    </div>
  </div>

  {{-- Progress Bar (semua selesai) --}}
  <div class="bg-white rounded-xl p-6 shadow-sm mb-8">
    <div class="flex justify-between relative">
      <div class="absolute top-5 left-[16%] right-[16%] h-0.5 bg-emerald-400 z-0"></div>

      {{-- Step 1: Bimbingan --}}
      <div class="flex flex-col items-center relative z-10 flex-1">
        <div class="w-10 h-10 rounded-full flex items-center justify-center text-base mb-2 bg-emerald-500 text-white shadow-[0_0_0_4px_rgba(16,185,129,0.2)]">
          <i class="fas fa-check"></i>
        </div>
        <span class="text-[10px] sm:text-xs font-semibold text-center text-emerald-600">Bimbingan</span>
      </div>

      {{-- Step 2: Kajur --}}
      <div class="flex flex-col items-center relative z-10 flex-1">
        <div class="w-10 h-10 rounded-full flex items-center justify-center text-base mb-2 bg-emerald-500 text-white shadow-[0_0_0_4px_rgba(16,185,129,0.2)]">
          <i class="fas fa-check"></i>
        </div>
        <span class="text-[10px] sm:text-xs font-semibold text-center text-emerald-600">{{ $kajurStepLabel }}</span>
      </div>

      {{-- Step 3: Selesai (aktif) --}}
      <div class="flex flex-col items-center relative z-10 flex-1">
        <div class="w-10 h-10 rounded-full flex items-center justify-center text-base mb-2 bg-emerald-500 text-white shadow-[0_0_0_4px_rgba(16,185,129,0.2)]">
          <i class="fas fa-flag-checkered"></i>
        </div>
        <span class="text-[10px] sm:text-xs font-semibold text-center text-emerald-600">Selesai</span>
      </div>
    </div>
  </div>

  {{-- Konten Utama --}}
  <div class="bg-white shadow-sm rounded-xl overflow-hidden p-8 text-center border-t-4 border-emerald-500">
    <div class="flex justify-center mb-6">
      <div class="w-24 h-24 bg-emerald-50 rounded-full flex items-center justify-center">
        <i class="fas fa-check-double text-5xl text-emerald-500"></i>
      </div>
    </div>

    <h2 class="text-xl sm:text-2xl font-bold text-gray-800 mb-3">Selamat, {{ $tugasAkhir->mahasiswa->nama_lengkap }}!</h2>
    <p class="text-sm sm:text-base text-gray-600 max-w-xl mx-auto mb-8 leading-relaxed">
      Anda telah menyelesaikan seluruh tahapan bimbingan dan ujian untuk
      <strong>{{ ucfirst($jenis) }}</strong>.
      Semua proses mulai dari bimbingan, persetujuan, hingga ujian telah berhasil diselesaikan.
    </p>

    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-center gap-3">
      <a href="{{ route('mahasiswa.bimbingan.bimbingan', ['jenis' => $jenis]) }}"
        class="w-full sm:w-auto px-6 py-3 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm font-semibold rounded-lg transition-colors flex justify-center items-center gap-2">
        <i class="fas fa-history"></i> Lihat Riwayat Bimbingan
      </a>
      <a href="{{ route('mahasiswa.dashboard') }}"
        class="w-full sm:w-auto px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition-colors flex justify-center items-center gap-2">
        <i class="fas fa-home"></i> Kembali ke Beranda
      </a>
    </div>
  </div>

@endsection
