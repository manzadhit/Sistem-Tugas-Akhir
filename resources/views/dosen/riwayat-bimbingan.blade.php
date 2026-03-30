@extends('layouts.app')

@section('title', 'Riwayat Bimbingan')

@section('sidebar')
  @if (auth()->user()->role === 'kajur')
    @include('kajur.sidebar')
  @else
    @include('dosen.sidebar')
  @endif
@endsection

@section('content')
  @php
    $mahasiswa = $dosenPembimbing->mahasiswa;
    $tugasAkhir = $mahasiswa->tugasAkhir;
  @endphp

  <!-- Page Header -->
  <div class="mb-8">
    <nav class="flex items-center gap-2 text-sm text-gray-500 mb-3">
      <a href="{{ route('dosen.bimbingan.index') }}" class="hover:text-gray-700 transition-colors">Bimbingan</a>
      <i class="fas fa-chevron-right text-xs text-gray-400"></i>
      <a href="{{ route('dosen.bimbingan.mahasiswa') }}" class="hover:text-gray-700 transition-colors">Daftar Mahasiswa</a>
      <i class="fas fa-chevron-right text-xs text-gray-400"></i>
      <span class="text-gray-900 font-medium">Riwayat</span>
    </nav>
    <div class="flex items-center gap-3 mb-4">
      <a href="{{ route('dosen.bimbingan.index') }}" class="text-gray-500 hover:text-gray-700 transition-colors">
        <i class="fas fa-arrow-left"></i>
      </a>
      <h1 class="text-3xl font-bold text-gray-900">Riwayat Bimbingan</h1>
    </div>
    <p class="text-base text-gray-500">Daftar seluruh submission dari mahasiswa ini</p>
  </div>

  <!-- Mahasiswa Info Card -->
  <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
    <div class="border-b border-gray-200 px-6 py-4">
      <h3 class="font-semibold text-gray-900">Informasi Mahasiswa</h3>
    </div>
    <div class="p-6">
      <div class="flex items-start gap-4">
        <div
          class="w-14 h-14 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-2xl flex-shrink-0">
          <i class="fas fa-user-graduate"></i>
        </div>
        <div class="flex-1">
          <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
            <div>
              <h4 class="text-lg font-semibold text-gray-900">{{ $mahasiswa->nama_lengkap }}</h4>
              <p class="text-sm text-gray-500">NIM: {{ $mahasiswa->nim }}</p>
            </div>
            <span
              class="self-start inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">
              {{ $dosenPembimbing->getJenisPembimbing() }}
            </span>
          </div>
          @if ($tugasAkhir)
            <div class="mt-3 bg-gray-50 rounded-lg p-4 space-y-2">
              <div>
                <p class="text-xs text-gray-500 mb-0.5">Judul Tugas Akhir</p>
                <p class="text-sm font-medium text-gray-900">{{ $tugasAkhir->judul }}</p>
              </div>
              <div class="flex gap-6 text-xs text-gray-500">
                <span>Tahapan: <span class="font-medium text-gray-700">{{ $tugasAkhir->tahapan }}</span></span>
                <span>Mulai: <span
                    class="font-medium text-gray-700">{{ $dosenPembimbing->tanggal_mulai?->format('d M Y') ?? '-' }}</span></span>
              </div>
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>

  <!-- Riwayat Submission -->
  <div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="border-b border-gray-200 px-6 py-4 flex items-center justify-between">
      <h3 class="font-semibold text-gray-900">Riwayat Submission</h3>
      <span class="text-xs text-gray-500">{{ $dosenPembimbing->submissions->count() }} submission</span>
    </div>

    @if ($dosenPembimbing->submissions->isEmpty())
      <div class="p-10 text-center text-sm text-gray-500">
        <i class="fas fa-inbox text-3xl text-gray-300 mb-3 block"></i>
        Belum ada riwayat submission.
      </div>
    @else
      <div class="divide-y divide-gray-100">
        @foreach ($dosenPembimbing->submissions as $submission)
          @php
            $statusConfig = match ($submission->status) {
                'acc' => ['color' => 'green', 'icon' => 'fa-check-circle', 'label' => 'ACC'],
                'revisi' => ['color' => 'yellow', 'icon' => 'fa-edit', 'label' => 'Revisi'],
                'reject' => ['color' => 'red', 'icon' => 'fa-times-circle', 'label' => 'Ditolak'],
                default => ['color' => 'orange', 'icon' => 'fa-clock', 'label' => 'Pending'],
            };
          @endphp
          <div class="p-5 hover:bg-gray-50 transition-colors">
            <div class="flex items-start justify-between gap-4">
              <div class="flex items-start gap-3 min-w-0">
                <div
                  class="w-8 h-8 rounded-full bg-{{ $statusConfig['color'] }}-100 text-{{ $statusConfig['color'] }}-600 flex items-center justify-center text-sm shrink-0 mt-0.5">
                  <i class="fas {{ $statusConfig['icon'] }}"></i>
                </div>
                <div class="min-w-0">
                  <div class="flex items-center gap-2 mb-1">
                    <span
                      class="inline-block px-2 py-0.5 rounded-full text-xs font-semibold bg-{{ $statusConfig['color'] }}-100 text-{{ $statusConfig['color'] }}-700">
                      {{ $statusConfig['label'] }}
                    </span>
                    <span class="text-xs text-gray-400">{{ $submission->created_at->format('d M Y, H:i') }}</span>
                  </div>
                  @if ($submission->catatan)
                    <p class="text-sm text-gray-600 mb-2">
                      <span class="text-xs text-gray-400 font-medium">Catatan mahasiswa:
                      </span>{{ $submission->catatan }}
                    </p>
                  @endif
                  @if ($submission->review)
                    <div class="bg-blue-50 border border-blue-100 rounded-lg px-3 py-2 text-sm text-gray-700">
                      <span class="text-xs text-blue-500 font-medium">Review dosen: </span>{{ $submission->review }}
                    </div>
                  @endif
                </div>
              </div>
              <a href="{{ route('dosen.bimbingan.detail', $submission->id) }}"
                class="shrink-0 w-8 h-8 rounded-md flex items-center justify-center bg-blue-100 text-blue-600 hover:bg-blue-200 transition-all text-sm"
                title="Lihat Detail">
                <i class="fas fa-eye"></i>
              </a>
            </div>
          </div>
        @endforeach
      </div>
    @endif
  </div>
@endsection
