@extends('layouts.app')

@section('title', 'Undangan Ujian ' . ucfirst($jenis))

@section('sidebar')
  @include('mahasiswa.sidebar')
@endsection

@section('content')
  <div class="relative h-40 rounded-xl overflow-hidden mb-8 bg-gradient-to-br from-blue-600 to-blue-700">
    <div class="absolute inset-0 flex flex-col items-center justify-center text-center text-white p-4">
      <i class="fas fa-envelope-open-text text-3xl sm:text-[2.5rem] mb-3"></i>
      <h1 class="text-xl sm:text-[1.75rem] md:text-[2rem] font-bold mb-2">Undangan Ujian</h1>
      <p class="text-sm sm:text-base opacity-90">Surat undangan sedang disiapkan oleh admin</p>
    </div>
  </div>

  <!-- Progress Bar -->
  <div class="bg-white rounded-xl p-6 shadow-sm mb-8">
    <div class="relative flex justify-between">
      <!-- line -->
      <div class="absolute top-5 left-[15%] right-[15%] h-[3px] bg-gray-200 z-0"></div>

      <!-- Step 1 -->
      <div class="relative z-10 flex-1 flex flex-col items-center">
        <div class="w-10 h-10 rounded-full flex items-center justify-center text-base mb-2 bg-emerald-500 text-white">
          <i class="fas fa-file-upload"></i>
        </div>
        <span class="text-[10px] sm:text-xs font-medium text-emerald-500 text-center">Upload Syarat</span>
      </div>

      <!-- Step 2 -->
      <div class="relative z-10 flex-1 flex flex-col items-center">
        <div class="w-10 h-10 rounded-full flex items-center justify-center text-base mb-2 bg-emerald-500 text-white">
          <i class="fas fa-calendar-check"></i>
        </div>
        <span class="text-[10px] sm:text-xs font-medium text-emerald-500 text-center">Jadwal</span>
      </div>

      <!-- Step 3 (active) -->
      <div class="relative z-10 flex-1 flex flex-col items-center">
        <div
          class="w-10 h-10 rounded-full flex items-center justify-center text-base mb-2 bg-blue-600 text-white ring-4 ring-blue-600/20">
          <i class="fas fa-envelope-open-text"></i>
        </div>
        <span class="text-[10px] sm:text-xs font-semibold text-blue-600 text-center">Undangan</span>
      </div>

      <!-- Step 4 (pending) -->
      <div class="relative z-10 flex-1 flex flex-col items-center">
        <div class="w-10 h-10 rounded-full flex items-center justify-center text-base mb-2 bg-gray-200 text-gray-400">
          <i class="fas fa-check-circle"></i>
        </div>
        <span class="text-[10px] sm:text-xs font-medium text-gray-500 text-center">Selesai</span>
      </div>
    </div>
  </div>

  <!-- Status Undangan -->
  <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-8">
    <div class="px-8 py-6 border-b border-gray-200 text-center bg-slate-50">
      <div class="text-lg sm:text-xl font-bold text-slate-900 mb-1">Status Undangan</div>
      <div class="text-xs sm:text-sm text-slate-500">
        Admin sedang memproses surat undangan untuk dosen penguji.
      </div>
    </div>

    <div class="p-8 text-center">
      <span
        class="inline-flex items-center gap-1.5 px-3 sm:px-3.5 py-1 sm:py-1.5 rounded-full text-[0.75rem] sm:text-[0.8rem] font-semibold bg-blue-100 text-blue-700 mb-4">
        <i class="fas fa-clock"></i>
        Diproses Admin
      </span>

      <!-- Loader ring (tanpa CSS keyframes custom) -->
      <div class="w-[52px] h-[52px] border-4 border-slate-200 border-t-blue-600 rounded-full animate-spin mx-auto mb-3"
        aria-hidden="true"></div>

      <div class="text-xs sm:text-sm text-slate-500">Mohon tunggu, undangan sedang disiapkan.</div>
    </div>
  </div>
@endsection
