@extends('layouts.app')

@section('title', 'Dashboard Dosen')

@section('sidebar')
  @include('dosen.sidebar')
@endsection

@section('content')
  {{-- Banner --}}
  <div class="relative h-48 overflow-hidden rounded-xl bg-gradient-to-br from-blue-800 to-blue-500 mb-8">
    <div class="absolute inset-0 flex flex-col items-center justify-center text-white text-center px-4">
      <h1 class="text-2xl font-bold mb-1">Selamat Datang, {{ auth()->user()->display_name }}</h1>
      <p class="text-sm opacity-90">Dashboard Dosen — Jurusan Informatika</p>
    </div>
  </div>

  {{-- Stats --}}
  <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">
    <x-stats label="Mahasiswa Bimbingan" :total="$totalMahasiswaBimbingan" bg-color="bg-blue-100" text-color='text-blue-600'>
      <x-slot:icon>
        <i class="fas fa-users"></i>
      </x-slot:icon>
    </x-stats>

    <x-stats label="Total Publikasi" :total="$totalPublikasi" bg-color="bg-emerald-100" text-color='text-emerald-600'>
      <x-slot:icon>
        <i class="fas fa-book"></i>
      </x-slot:icon>
    </x-stats>
  </div>

  {{-- Content Grid --}}
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Mahasiswa Bimbingan Aktif --}}
    <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
      <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
        <h3 class="font-semibold text-slate-900">Mahasiswa Bimbingan Aktif</h3>
        <a href="{{ route('dosen.bimbingan.mahasiswa') }}" class="text-sm text-blue-600 font-medium hover:underline">Lihat
          Semua →</a>
      </div>
      <div class="divide-y divide-slate-100">
        @forelse ($mahasiswaBimbingan as $dp)
          <div class="flex items-center gap-3 px-6 py-3">
            <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-sm shrink-0">
              <i class="fas fa-user-graduate"></i>
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-sm font-medium text-slate-900 truncate">{{ $dp->mahasiswa->nama_lengkap }}</p>
              <p class="text-xs text-slate-500">{{ $dp->mahasiswa->nim }} ·
                {{ $dp->mahasiswa->tugasAkhir?->tahapan ?? '-' }}</p>
            </div>
            <span class="text-xs text-slate-400 shrink-0">{{ $dp->getJenisPembimbing() }}</span>
          </div>
        @empty
          <div class="px-6 py-8 text-center text-sm text-slate-500">Belum ada mahasiswa bimbingan.</div>
        @endforelse
      </div>
    </div>

    {{-- Publikasi Terbaru --}}
    <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
      <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
        <h3 class="font-semibold text-slate-900">Publikasi Terbaru</h3>
        <a href="{{ route('dosen.publikasi.index') }}" class="text-sm text-blue-600 font-medium hover:underline">Kelola
          →</a>
      </div>
      <div class="divide-y divide-slate-100">
        @forelse ($publikasiTerbaru as $pub)
          <div class="flex items-start gap-3 px-6 py-3">
            <div
              class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-sm shrink-0 mt-0.5">
              <i class="fas fa-file-alt"></i>
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-sm font-medium text-slate-900 line-clamp-1">{{ $pub->judul }}</p>
              <p class="text-xs text-slate-500">{{ $pub->tahun }} · {{ $pub->jenis_publikasi }}</p>
            </div>
          </div>
        @empty
          <div class="px-6 py-8 text-center text-sm text-slate-500">Belum ada data publikasi.</div>
        @endforelse
      </div>
    </div>
  </div>
@endsection
