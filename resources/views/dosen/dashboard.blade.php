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
  <div class="grid grid-cols-2 sm:grid-cols-3 gap-6 mb-8">
    <x-stats label="Bimbingan Aktif" :total="$totalMahasiswaBimbingan" bg-color="bg-blue-100" text-color='text-blue-600'>
      <x-slot:icon>
        <i class="fas fa-users"></i>
      </x-slot:icon>
    </x-stats>

    <x-stats label="Pengujian Periode" :total="$totalPengujianPeriode" bg-color="bg-violet-100" text-color='text-violet-600'>
      <x-slot:icon>
        <i class="fas fa-gavel"></i>
      </x-slot:icon>
    </x-stats>

    <x-stats label="Pengujian Aktif" :total="$totalPengujianAktif" bg-color="bg-orange-100" text-color='text-orange-600'>
      <x-slot:icon>
        <i class="fas fa-user-clock"></i>
      </x-slot:icon>
    </x-stats>

    <x-stats label="Bimbingan Lulus" :total="$totalMahasiswaLulus" bg-color="bg-emerald-100" text-color='text-emerald-600'>
      <x-slot:icon>
        <i class="fas fa-graduation-cap"></i>
      </x-slot:icon>
    </x-stats>

    <x-stats label="Total Publikasi" :total="$totalPublikasi" bg-color="bg-amber-100" text-color='text-amber-600'>
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
        <h3 class="font-semibold text-slate-900">Bimbingan Aktif</h3>
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

    {{-- Daftar Pengujian --}}
    <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
      <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
        <div>
          <h3 class="font-semibold text-slate-900">Daftar Pengujian</h3>
          @if ($periodeAktif)
            <p class="text-xs text-slate-400 mt-0.5">{{ $periodeAktif->tahun_ajaran }} — Semester {{ $periodeAktif->semester }}</p>
          @endif
        </div>
        <a href="{{ route('dosen.pengujian.index') }}" class="text-sm text-violet-600 font-medium hover:underline">Lihat
          Semua →</a>
      </div>
      <div class="divide-y divide-slate-100">
        @forelse ($mahasiswaPengujian as $item)
          @php($ujians = $item->mahasiswa?->tugasAkhir?->ujian ?? collect())
          <div class="flex items-center gap-3 px-6 py-3">
            <div class="w-8 h-8 rounded-full bg-violet-100 text-violet-600 flex items-center justify-center text-sm shrink-0">
              <i class="fas fa-gavel"></i>
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-sm font-medium text-slate-900 truncate">{{ $item->mahasiswa->nama_lengkap }}</p>
              <div class="mt-1 flex flex-wrap items-center gap-2 text-xs text-slate-500">
                <span>{{ $item->mahasiswa->nim }}</span>
                @if ($ujians->isNotEmpty())
                  @foreach ($ujians as $ujian)
                    <span class="inline-flex rounded-full bg-blue-100 px-2 py-0.5 font-semibold text-blue-800">
                      {{ ucfirst($ujian->jenis_ujian) }}
                    </span>
                  @endforeach
                @endif
              </div>
            </div>
            <span class="text-xs text-slate-400 shrink-0">{{ $item->getJenisPenguji() }}</span>
          </div>
        @empty
          <div class="px-6 py-8 text-center text-sm text-slate-500">Belum ada data pengujian.</div>
        @endforelse
      </div>
    </div>

    {{-- Mahasiswa Lulus --}}
    <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
      <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
        <h3 class="font-semibold text-slate-900">Bimbingan Lulus</h3>
        <a href="{{ route('dosen.bimbingan.mahasiswa-lulus') }}" class="text-sm text-emerald-600 font-medium hover:underline">Lihat
          Semua →</a>
      </div>
      <div class="divide-y divide-slate-100">
        @forelse ($mahasiswaLulus as $dp)
          <div class="flex items-center gap-3 px-6 py-3">
            <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-sm shrink-0">
              <i class="fas fa-graduation-cap"></i>
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-sm font-medium text-slate-900 truncate">{{ $dp->mahasiswa->nama_lengkap }}</p>
              <p class="text-xs text-slate-500">{{ $dp->mahasiswa->nim }}</p>
            </div>
            <span class="text-xs text-slate-400 shrink-0">{{ $dp->getJenisPembimbing() }}</span>
          </div>
        @empty
          <div class="px-6 py-8 text-center text-sm text-slate-500">Belum ada mahasiswa lulus.</div>
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
