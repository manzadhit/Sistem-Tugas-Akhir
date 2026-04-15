@extends('layouts.app')

@section('title', 'Dashboard Mahasiswa')

@section('sidebar')
  @include('mahasiswa.sidebar')
@endsection

@section('content')
  {{-- Banner --}}
  <div class="relative h-40 overflow-hidden rounded-xl mb-8
    {{ auth()->user()->profileMahasiswa?->status_akademik === 'lulus' ? 'bg-gradient-to-br from-emerald-500 to-emerald-700' : 'bg-gradient-to-br from-blue-600 to-blue-800' }}">
    <div class="absolute inset-0 flex flex-col items-center justify-center text-white text-center px-4">
      <h1 class="text-xl sm:text-2xl md:text-3xl font-bold mb-1">Dashboard Mahasiswa</h1>
      <p class="text-xs sm:text-sm opacity-90">
        {{ auth()->user()->profileMahasiswa?->status_akademik === 'lulus' ? 'Selamat! Anda telah berhasil menyelesaikan studi.' : 'Ringkasan informasi tugas akhir dan bimbingan' }}
      </p>
    </div>
  </div>

  {{-- Banner Kelulusan --}}
  @if (auth()->user()->profileMahasiswa?->status_akademik === 'lulus')
    <div class="mb-8 flex items-center gap-4 rounded-xl border border-emerald-300 bg-gradient-to-br from-emerald-50 to-emerald-100 p-5 shadow-sm">
      <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-emerald-500 text-white shadow">
        <i class="fas fa-graduation-cap text-xl"></i>
      </div>
      <div class="flex-1">
        <div class="text-base font-bold text-emerald-800">Selamat, Anda Telah Lulus! 🎉</div>
        <div class="mt-0.5 text-sm text-emerald-700">
          Seluruh tahapan Tugas Akhir telah selesai. Status akademik Anda kini tercatat sebagai <span class="font-semibold">Lulus</span>.
        </div>
      </div>
      <span class="hidden sm:inline-flex items-center gap-1.5 rounded-full bg-emerald-500 px-4 py-1.5 text-xs font-bold text-white shadow">
        <i class="fas fa-check-circle"></i> LULUS
      </span>
    </div>
  @endif

  {{-- Profil Singkat --}}
  <div class="rounded-xl border border-slate-200 bg-white shadow-sm mb-8 overflow-hidden">
    <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
      <h3 class="flex items-center gap-2 font-semibold text-slate-900">
        <i class="fas fa-user-graduate text-blue-600"></i> Profil Mahasiswa
      </h3>
      @if (auth()->user()->profileMahasiswa?->status_akademik === 'lulus')
        <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
          <i class="fas fa-graduation-cap mr-1"></i>Lulus
        </span>
      @else
        <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">Tahap:
          {{ ucfirst($tugasAkhir->tahapan) }}</span>
      @endif
    </div>
    <div class="grid grid-cols-2 gap-4 p-6">
      <div>
        <p class="text-xs text-slate-500">Nama Lengkap</p>
        <p class="font-semibold text-slate-900">{{ auth()->user()->display_name }}</p>
      </div>
      <div>
        <p class="text-xs text-slate-500">NIM</p>
        <p class="font-semibold text-slate-900">{{ auth()->user()->display_subtitle }}</p>
      </div>
      <div>
        <p class="text-xs text-slate-500">Jurusan</p>
        <p class="font-semibold text-slate-900">{{ auth()->user()->profileMahasiswa?->jurusan ?? '-' }}</p>
      </div>
      <div>
        <p class="text-xs text-slate-500">Angkatan</p>
        <p class="font-semibold text-slate-900">{{ auth()->user()->profileMahasiswa?->angkatan ?? '-' }}</p>
      </div>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Kolom Kiri (2/3) --}}
    <div class="lg:col-span-2 space-y-6">

      {{-- Judul TA --}}
      <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
        <div class="border-b border-slate-200 px-6 py-4">
          <h3 class="flex items-center gap-2 font-semibold text-slate-900">
            <i class="fas fa-book text-blue-600"></i> Judul Tugas Akhir
          </h3>
        </div>
        <div class="p-6">
          <p class="italic text-slate-600 leading-relaxed">{{ $tugasAkhir->judul }}</p>
        </div>
      </div>

      {{-- Riwayat Bimbingan --}}
      <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
        <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
          <h3 class="flex items-center gap-2 font-semibold text-slate-900">
            <i class="fas fa-history text-blue-600"></i> Riwayat Bimbingan
          </h3>
          @php
            $bimbinganRoute = route('mahasiswa.bimbingan.riwayat');
          @endphp
          <a href="{{ $bimbinganRoute }}" class="text-sm text-blue-600 hover:underline">Lihat Semua →</a>
        </div>
        <div class="divide-y divide-slate-100">
          @forelse($riwayatBimbingan as $submission)
            <div class="px-6 py-4 flex items-start justify-between gap-4">
              <div class="min-w-0">
                <p class="text-sm font-semibold text-slate-800 truncate">
                  {{ $submission->dosenPembimbing->getJenisPembimbing() }} -
                  {{ $submission->dosenPembimbing->dosen->nama_lengkap ?? '-' }}
                </p>
                @if ($submission->catatan)
                  <p class="text-xs text-slate-500 mt-0.5 line-clamp-1">{{ $submission->catatan }}</p>
                @endif
                <p class="text-xs text-slate-400 mt-1">{{ $submission->created_at->translatedFormat('d M Y') }}</p>
              </div>
              <span @class([
                  'shrink-0 rounded-full px-2.5 py-0.5 text-xs font-semibold',
                  'bg-yellow-50 text-yellow-700' => $submission->status === 'pending',
                  'bg-emerald-50 text-emerald-700' => $submission->status === 'acc',
                  'bg-red-50 text-red-700' => $submission->status === 'revision',
              ])>
                {{ match ($submission->status) {
                    'pending' => 'Menunggu',
                    'acc' => 'ACC',
                    'revision' => 'Revisi',
                    default => ucfirst($submission->status),
                } }}
              </span>
            </div>
          @empty
            <p class="text-sm text-slate-500 text-center py-6">Belum ada riwayat bimbingan.</p>
          @endforelse
        </div>
      </div>
    </div>

    {{-- Kolom Kanan (1/3) --}}
    <div class="space-y-6">

      {{-- Pembimbing --}}
      <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
        <div class="border-b border-slate-200 px-6 py-4">
          <h3 class="flex items-center gap-2 font-semibold text-slate-900">
            <i class="fas fa-user-tie text-blue-600"></i> Pembimbing
          </h3>
        </div>
        <div class="p-6 space-y-3">
          @forelse($dosenPembimbing as $pembimbing)
            <div class="flex items-center justify-between rounded-lg border border-slate-200 bg-slate-50 px-4 py-3">
              <div>
                <p class="text-sm font-semibold text-slate-900">Pembimbing {{ $loop->iteration }}</p>
                <p class="text-xs text-slate-500">{{ $pembimbing->dosen->nama_lengkap ?? '-' }}</p>
              </div>
              @if ($pembimbing->status_aktif)
                <span class="rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-semibold text-emerald-700">Aktif</span>
              @else
                <span class="rounded-full bg-slate-50 px-2.5 py-0.5 text-xs font-semibold text-slate-700">Selesai</span>
              @endif
            </div>
          @empty
            <p class="text-sm text-slate-500 text-center py-2">Belum ada pembimbing.</p>
          @endforelse
        </div>
      </div>

      {{-- Penguji --}}
      <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
        <div class="border-b border-slate-200 px-6 py-4">
          <h3 class="flex items-center gap-2 font-semibold text-slate-900">
            <i class="fas fa-users text-blue-600"></i> Penguji
          </h3>
        </div>
        <div class='p-6 space-y-3'>
          @forelse ($dosenPenguji as $penguji)
            <div class="flex items-center justify-between rounded-lg border border-slate-200 bg-slate-50 px-4 py-3">
              <div>
                <p class="text-sm font-semibold text-slate-900">Penguji {{ $loop->iteration }}</p>
                <p class="text-xs text-slate-500">{{ $penguji->dosen->nama_lengkap ?? '-' }}</p>
              </div>
            </div>
          @empty
            <div class="p-6 space-y-3">
              <p class="text-sm text-slate-500 text-center py-2">Belum ditetapkan.</p>
            </div>
          @endforelse
        </div>
      </div>
    </div>
  </div>
@endsection
