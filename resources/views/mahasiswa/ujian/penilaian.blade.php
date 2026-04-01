@extends('layouts.app')

@section('title', 'Menunggu Penilaian Ujian Skripsi')

@section('sidebar')
  @include('mahasiswa.sidebar')
@endsection

@section('content')
  {{-- Banner --}}
  <div class="relative h-40 rounded-xl overflow-hidden mb-8 bg-gradient-to-br from-blue-600 to-blue-700">
    <div class="absolute inset-0 flex flex-col items-center justify-center text-center text-white p-4">
      <i class="fas fa-star text-3xl sm:text-[2.5rem] mb-3"></i>
      <h1 class="text-xl sm:text-[1.75rem] font-bold mb-2">Penilaian Ujian Skripsi</h1>
      <p class="text-sm sm:text-base opacity-90">
        {{ $ujian->status === 'menunggu_hasil' ? 'Semua penguji telah menginput nilai ujian Anda' : 'Menunggu penguji menginput nilai ujian Anda' }}
      </p>
    </div>
  </div>

  {{-- Progress Bar --}}
  @include('mahasiswa.ujian.partials.progress-bar', ['activeStep' => 'penilaian'])

  {{-- Status Card --}}
  <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
    <div class="px-8 py-6 border-b border-gray-200 text-center bg-slate-50">
      <div class="text-lg sm:text-xl font-bold text-slate-900 mb-1">Status Penilaian</div>
      <div class="text-xs sm:text-sm text-slate-500">
        Penguji sedang menginput nilai. Halaman ini akan otomatis berlanjut ke tahap berikutnya.
      </div>
    </div>

    <div class="p-6 sm:p-8">
      {{-- Progress nilai --}}
      @php
        $pengujiList = $ujian->tugasAkhir->mahasiswa->dosenPenguji;
        $sudahInput = $pengujiList->whereNotNull('nilai')->count();
        $total = $pengujiList->count();
      @endphp

      <div class="flex items-center justify-center gap-3 mb-6">
        <span class="text-3xl font-bold text-blue-600">{{ $sudahInput }}</span>
        <span class="text-slate-400 text-lg">/</span>
        <span class="text-3xl font-bold text-slate-300">{{ $total }}</span>
        <span class="text-sm text-slate-500 ml-1">penguji sudah input nilai</span>
      </div>

      {{-- Daftar penguji --}}
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-6">
        @forelse ($pengujiList as $penguji)
          @php $sudah = $penguji->nilai !== null; @endphp
          <div class="border {{ $sudah ? 'border-emerald-200 bg-emerald-50' : 'border-gray-200 bg-gray-50' }} rounded-xl p-4 text-center">
            <div class="w-10 h-10 rounded-full {{ $sudah ? 'bg-emerald-100 text-emerald-600' : 'bg-gray-200 text-gray-400' }} flex items-center justify-center mx-auto mb-2">
              <i class="fas {{ $sudah ? 'fa-check' : 'fa-hourglass-half' }} text-sm"></i>
            </div>
            <div class="text-sm font-semibold text-slate-800">{{ $penguji->dosen->nama_lengkap }}</div>
            <div class="text-xs text-slate-500 mt-0.5">Penguji {{ $loop->iteration }}</div>
            <div class="mt-2 text-xs font-medium {{ $sudah ? 'text-emerald-600' : 'text-gray-400' }}">
              {{ $sudah ? 'Sudah input' : 'Belum input' }}
            </div>
          </div>
        @empty
          <div class="col-span-3 text-center text-sm text-slate-400 py-4">Belum ada data penguji.</div>
        @endforelse
      </div>

      @if ($ujian->status === 'menunggu_hasil')
        <div class="flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 rounded-xl">
          <i class="fas fa-check-circle text-emerald-500 shrink-0"></i>
          <p class="text-sm text-emerald-700">
            Semua penguji telah menginput nilai. Anda dapat melanjutkan ke tahap upload hasil ujian.
          </p>
        </div>
      @else
        <div class="flex items-center gap-3 p-4 bg-blue-50 border border-blue-200 rounded-xl">
          <i class="fas fa-info-circle text-blue-500 shrink-0"></i>
          <p class="text-sm text-blue-700">
            Setelah semua penguji menginput nilai, Anda akan dapat melanjutkan ke tahap upload hasil ujian.
          </p>
        </div>
      @endif

      {{-- Action Buttons --}}
      <div class="flex justify-between gap-3 pt-6 mt-6 border-t border-gray-200">
        <a href="{{ route('mahasiswa.ujian.undangan', $jenis) }}"
          class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition cursor-pointer no-underline">
          <i class="fas fa-arrow-left text-gray-400"></i>
          Kembali
        </a>

        @if ($ujian->status === 'menunggu_hasil')
          <a href="{{ route('mahasiswa.ujian.hasil-ujian', $jenis) }}"
            class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-blue-700 rounded-xl hover:-translate-y-0.5 shadow-[0_4px_12px_rgba(37,99,235,0.3)] hover:shadow-[0_6px_20px_rgba(37,99,235,0.4)] transition cursor-pointer no-underline">
            <i class="fas fa-upload"></i>
            Upload Hasil Ujian
          </a>
        @else
          <button disabled
            class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-medium text-gray-400 bg-gray-100 rounded-xl border-none cursor-not-allowed" title="Menunggu semua penguji input nilai">
            <i class="fas fa-upload"></i>
            Upload Hasil Ujian
          </button>
        @endif
      </div>
    </div>
  </div>
@endsection
