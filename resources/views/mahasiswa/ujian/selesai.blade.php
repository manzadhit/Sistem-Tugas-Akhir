@extends('layouts.app')

@section('title', 'Ujian Selesai')

@section('sidebar')
  @include('mahasiswa.sidebar')
@endsection

@section('content')

  {{-- Page Banner --}}
  <div class="relative h-40 rounded-xl overflow-hidden mb-8 bg-gradient-to-br from-emerald-600 to-emerald-800">
    <div class="absolute inset-0 flex flex-col items-center justify-center text-white text-center px-4">
      <i class="fas fa-graduation-cap text-3xl sm:text-4xl mb-3"></i>
      <h1 class="text-xl sm:text-2xl md:text-[1.75rem] font-bold mb-1">Ujian {{ ucfirst($jenis) }} Selesai</h1>
      <p class="text-xs sm:text-sm md:text-base opacity-90">
        Selamat! Seluruh rangkaian proses ujian {{ ucfirst($jenis) }} Anda telah berhasil diselesaikan.
      </p>
    </div>
  </div>

  {{-- Progress Bar --}}
  <div class="p-6 mb-8 bg-white shadow-sm rounded-xl">
    <div class="relative flex justify-between">
      {{-- Garis penghubung --}}
      <div class="absolute top-5 left-[10%] right-[10%] h-[3px] bg-emerald-500 z-0"></div>

      <!-- Step 1 (completed) -->
      <div class="relative z-10 flex-1 flex flex-col items-center">
        <div
          class="w-10 h-10 rounded-full flex items-center justify-center text-base mb-2 bg-emerald-500 text-white shadow-sm">
          <i class="fas fa-file-upload"></i>
        </div>
        <span class="text-[10px] sm:text-xs font-medium text-emerald-600 text-center">Upload Syarat</span>
      </div>

      <!-- Step 2 (completed) -->
      <div class="relative z-10 flex-1 flex flex-col items-center">
        <div
          class="w-10 h-10 rounded-full flex items-center justify-center text-base mb-2 bg-emerald-500 text-white shadow-sm">
          <i class="fas fa-calendar-check"></i>
        </div>
        <span class="text-[10px] sm:text-xs font-medium text-emerald-600 text-center">Jadwal</span>
      </div>

      <!-- Step 3 (completed) -->
      <div class="relative z-10 flex-1 flex flex-col items-center">
        <div
          class="w-10 h-10 rounded-full flex items-center justify-center text-base mb-2 bg-emerald-500 text-white shadow-sm">
          <i class="fas fa-envelope-open-text"></i>
        </div>
        <span class="text-[10px] sm:text-xs font-medium text-emerald-600 text-center">Undangan</span>
      </div>

      <!-- Step 4 (completed) -->
      <div class="relative z-10 flex-1 flex flex-col items-center">
        <div
          class="w-10 h-10 rounded-full flex items-center justify-center text-base mb-2 bg-emerald-500 text-white shadow-sm">
          <i class="fas fa-upload"></i>
        </div>
        <span class="text-[10px] sm:text-xs font-medium text-emerald-600 text-center">Upload Hasil</span>
      </div>

      <!-- Step 5 (active/done) -->
      <div class="relative z-10 flex-1 flex flex-col items-center">
        <div
          class="w-10 h-10 rounded-full flex items-center justify-center text-base mb-2 bg-emerald-600 text-white ring-4 ring-emerald-600/20 shadow-sm">
          <i class="fas fa-check-circle"></i>
        </div>
        <span class="text-[10px] sm:text-xs font-bold text-emerald-700 text-center">Selesai</span>
      </div>
    </div>
  </div>

  {{-- Konten Utama Selesai --}}
  <div class="bg-white shadow-sm rounded-xl overflow-hidden p-8 text-center border-t-4 border-emerald-500">
    <div class="flex justify-center mb-6">
      <div class="w-24 h-24 bg-emerald-50 rounded-full flex items-center justify-center">
        <i class="fas fa-check-double text-5xl text-emerald-500"></i>
      </div>
    </div>

    <h2 class="text-xl sm:text-2xl font-bold text-gray-800 mb-3">Selamat,
      {{ $ujian->tugasAkhir->mahasiswa->nama_lengkap }}!</h2>
    <p class="text-sm sm:text-base text-gray-600 max-w-xl mx-auto mb-8 leading-relaxed">
      Anda telah menyelesaikan seluruh tahapan mulai dari pengajuan persyaratan, pelaksanaan jadwal, hingga pelaporan
      hasil untuk <strong>Ujian {{ ucfirst($jenis) }}</strong>.
      Semua berkas kelengkapan telah diverifikasi dan disetujui (di-ACC) oleh staf akademik.
    </p>

    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-center gap-3 sm:gap-4">
      <a href="{{ route('mahasiswa.dashboard') }}"
        class="w-full sm:w-auto px-4 py-2 sm:px-6 sm:py-3 bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-semibold rounded-lg transition-colors shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none focus:ring-offset-2 flex justify-center items-center">
        <i class="fas fa-home mr-1.5 sm:mr-2"></i>Kembali ke Beranda
      </a>

      @if ($jenis === 'proposal')
        <a href="{{ route('mahasiswa.ujian', 'hasil') }}"
          class="w-full sm:w-auto px-4 py-2 sm:px-6 sm:py-3 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-xs sm:text-sm font-semibold rounded-lg transition-colors shadow-sm focus:ring-2 focus:ring-gray-300 focus:outline-none focus:ring-offset-2 flex justify-center items-center">
          Lanjut Ujian Hasil <i class="fas fa-arrow-right ml-1.5 sm:ml-2 text-gray-400"></i>
        </a>
      @elseif($jenis === 'hasil')
        <a href="{{ route('mahasiswa.ujian', 'skripsi') }}"
          class="w-full sm:w-auto px-4 py-2 sm:px-6 sm:py-3 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-xs sm:text-sm font-semibold rounded-lg transition-colors shadow-sm focus:ring-2 focus:ring-gray-300 focus:outline-none focus:ring-offset-2 flex justify-center items-center">
          Lanjut Ujian Skripsi <i class="fas fa-arrow-right ml-1.5 sm:ml-2 text-gray-400"></i>
        </a>
      @endif
    </div>
  </div>

@endsection
