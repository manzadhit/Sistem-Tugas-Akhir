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
  @include('mahasiswa.ujian.partials.progress-bar', ['activeStep' => 2])

  <!-- Status Undangan -->
  @if (optional($ujian->undanganUjian)->status === 'terkirim')
    <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-8">
      <div class="px-8 py-6 border-b border-gray-200 text-center bg-slate-50">
        <div class="text-lg sm:text-xl font-bold text-slate-900 mb-1">Status Undangan</div>
        <div class="text-xs sm:text-sm text-slate-500">
          Undangan resmi sudah dikirim ke seluruh dosen penguji.
        </div>
      </div>
      <div class="p-6 sm:p-8 text-center">
        <span
          class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full text-[0.8rem] font-semibold bg-green-100 text-green-800 mb-4">
          <i class="fas fa-check-circle"></i>
          Terkirim ke Penguji
        </span>
        <div class="text-xs sm:text-sm text-slate-500 mb-5">
          Silakan persiapkan presentasi dan berkas pendukung.
        </div>

        <div class="border border-gray-200 rounded-xl p-4 sm:p-5 bg-gray-50 text-left mb-5 w-full">
          <div class="text-[0.9rem] font-bold text-slate-900 mb-3">Jadwal Ujian</div>
          <div class="flex flex-col gap-2">
            <div class="flex items-center gap-2.5 text-[0.85rem] text-slate-600">
              <i class="fas fa-calendar-alt text-blue-600 w-4 text-center"></i>
              {{ optional($ujian->jadwalUjian)->tanggal_ujian ? $ujian->jadwalUjian->tanggal_ujian->translatedFormat('d F Y') : '-' }}
            </div>
            <div class="flex items-center gap-2.5 text-[0.85rem] text-slate-600">
              <i class="fas fa-clock text-blue-600 w-4 text-center"></i>
              {{ optional($ujian->jadwalUjian)->jam_mulai ? \Carbon\Carbon::parse($ujian->jadwalUjian->jam_mulai)->format('H:i') . ' - ' . \Carbon\Carbon::parse($ujian->jadwalUjian->jam_selesai)->format('H:i') . ' WITA' : '-' }}
            </div>
            <div class="flex items-center gap-2.5 text-[0.85rem] text-slate-600">
              <i class="fas fa-location-dot text-blue-600 w-4 text-center"></i>
              {{ optional($ujian->jadwalUjian)->ruangan ?? '-' }}
            </div>
          </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 mb-6">
          @forelse ($ujian->tugasAkhir->mahasiswa->dosenPenguji as $penguji)
            <div class="border border-gray-200 rounded-xl p-3 sm:p-4 bg-gray-50 text-left">
              <div class="text-[0.9rem] font-semibold text-gray-900 mb-0.5">{{ $penguji->dosen->nama_lengkap }}</div>
              <div class="text-[0.8rem] text-slate-500">Penguji {{ $loop->iteration }}</div>
            </div>
          @empty
            <div
              class="col-span-full border border-gray-200 rounded-xl p-4 bg-gray-50 text-center text-sm text-slate-500">
              Belum ada data penguji.
            </div>
          @endforelse
        </div>

        <div
          class="flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-4 p-4 bg-gray-50 mb-5 rounded-xl border border-gray-200 text-left">
          <div class="flex items-center gap-3 sm:gap-4 w-full sm:w-auto sm:flex-1 min-w-0">
            <div
              class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-gradient-to-br from-red-500 to-red-600 text-white flex items-center justify-center text-base sm:text-lg shrink-0">
              <i class="fas fa-file-pdf"></i>
            </div>
            <div class="flex-1 min-w-0">
              <div class="text-[0.85rem] sm:text-[0.9rem] font-semibold text-gray-900 break-all">
                {{ basename($ujian->undanganUjian->file_path) }}</div>
              <div class="text-[0.75rem] sm:text-[0.8rem] text-slate-500 mt-0.5">PDF • Terbit
                {{ $ujian->undanganUjian->updated_at->translatedFormat('d M Y') }}
              </div>
            </div>
          </div>
          <div class="flex gap-2 w-full sm:w-auto mt-1 sm:mt-0">
            <a href="{{ Storage::url($ujian->undanganUjian->file_path) }}"
              class="flex-1 sm:flex-none flex items-center justify-center px-3 py-2 sm:py-1.5 rounded-md border border-gray-200 bg-white text-xs font-medium text-gray-700 hover:border-blue-500 hover:text-blue-600 transition-all cursor-pointer">
              <i class="fas fa-eye mr-1.5 sm:mr-0 text-blue-600"></i> <span class="sm:hidden">Lihat</span>
            </a>
            <a href="{{ Storage::url($ujian->undanganUjian->file_path) }}" download
              class="flex-1 sm:flex-none flex items-center justify-center px-3 py-2 sm:py-1.5 rounded-md border border-gray-200 bg-white text-xs font-medium text-gray-700 hover:border-blue-500 hover:text-blue-600 transition-all cursor-pointer">
              <i class="fas fa-download mr-1.5 sm:mr-0 text-green-600"></i> <span class="sm:hidden">Unduh</span>
            </a>
          </div>
        </div>

        <div class="mt-8 flex justify-center">
          <a class="inline-flex items-center gap-2 px-6 py-3 rounded-lg text-sm font-medium bg-blue-600 text-white hover:bg-blue-700 transition-all cursor-pointer border-none sm:w-auto w-full justify-center shadow-[0_2px_8px_rgba(37,99,235,0.3)] hover:shadow-[0_4px_12px_rgba(37,99,235,0.4)]"
            href="{{ route('mahasiswa.ujian.hasil-ujian', $jenis) }}">
            <i class="fas fa-upload"></i>
            Upload Hasil Ujian
          </a>
        </div>
      </div>
    </div>
  @else
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

        <!-- Loader ring -->
        <div class="w-[52px] h-[52px] border-4 border-slate-200 border-t-blue-600 rounded-full animate-spin mx-auto mb-3"
          aria-hidden="true"></div>

        <div class="text-xs sm:text-sm text-slate-500">Mohon tunggu, undangan sedang disiapkan.</div>
      </div>
    </div>
  @endif
@endsection
