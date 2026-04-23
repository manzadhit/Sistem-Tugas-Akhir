@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('sidebar')
  @include('admin.sidebar')
@endsection

@section('content')
  {{-- Page Header --}}
  <div class="mb-8">
    <h1 class="text-2xl font-bold text-slate-900">Dashboard Admin</h1>
    <p class="text-slate-500 mt-1">Selamat datang kembali! Berikut ringkasan data Jurusan Teknik Informatika.</p>
  </div>

  {{-- Stats --}}
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    {{-- Card 1: Periode Aktif --}}
    <div
      class="flex items-center gap-4 rounded-xl border border-slate-200 bg-white p-5 shadow-sm hover:-translate-y-0.5 transition">
      <div
        class="flex h-14 w-14 items-center justify-center rounded-xl bg-gradient-to-br from-violet-500 to-violet-600 text-xl text-white">
        <i class="fas fa-calendar-check"></i>
      </div>
      <div>
        <p class="text-sm text-slate-500">Periode Aktif</p>
        <p class="text-xl font-bold text-slate-900">{{ $stats['periode_aktif'] }}</p>
      </div>
    </div>
    
    {{-- Card 2: Total Mahasiswa --}}
    <div
      class="flex items-center gap-4 rounded-xl border border-slate-200 bg-white p-5 shadow-sm hover:-translate-y-0.5 transition">
      <div
        class="flex h-14 w-14 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 text-xl text-white">
        <i class="fas fa-user-graduate"></i>
      </div>
      <div>
        <p class="text-sm text-slate-500">Total Mahasiswa</p>
        <p class="text-2xl font-bold text-slate-900">{{ $stats['total_mahasiswa'] }}</p>
      </div>
    </div>

    {{-- Card 3: Total Dosen --}}
    <div
      class="flex items-center gap-4 rounded-xl border border-slate-200 bg-white p-5 shadow-sm hover:-translate-y-0.5 transition">
      <div
        class="flex h-14 w-14 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 text-xl text-white">
        <i class="fas fa-chalkboard-teacher"></i>
      </div>
      <div>
        <p class="text-sm text-slate-500">Total Dosen</p>
        <p class="text-2xl font-bold text-slate-900">{{ $stats['total_dosen'] }}</p>
      </div>
    </div>

    {{-- Card 4: Menunggu Verifikasi Syarat --}}
    <div
      class="flex items-center gap-4 rounded-xl border border-slate-200 bg-white p-5 shadow-sm hover:-translate-y-0.5 transition">
      <div
        class="flex h-14 w-14 items-center justify-center rounded-xl bg-gradient-to-br from-rose-500 to-rose-600 text-xl text-white">
        <i class="fas fa-file-signature"></i>
      </div>
      <div>
        <p class="text-sm text-slate-500">Menunggu Verif Syarat</p>
        <p class="text-2xl font-bold text-slate-900">{{ $stats['verif_syarat'] }}</p>
      </div>
    </div>

    {{-- Card 5: Menunggu Verifikasi Hasil --}}
    <div
      class="flex items-center gap-4 rounded-xl border border-slate-200 bg-white p-5 shadow-sm hover:-translate-y-0.5 transition">
      <div
        class="flex h-14 w-14 items-center justify-center rounded-xl bg-gradient-to-br from-teal-500 to-teal-600 text-xl text-white">
        <i class="fas fa-clipboard-check"></i>
      </div>
      <div>
        <p class="text-sm text-slate-500">Menunggu Verif Hasil</p>
        <p class="text-2xl font-bold text-slate-900">{{ $stats['verif_hasil'] }}</p>
      </div>
    </div>

    {{-- Card 6: Total Publikasi --}}
    <div
      class="flex items-center gap-4 rounded-xl border border-slate-200 bg-white p-5 shadow-sm hover:-translate-y-0.5 transition">
      <div
        class="flex h-14 w-14 items-center justify-center rounded-xl bg-gradient-to-br from-amber-500 to-amber-600 text-xl text-white">
        <i class="fas fa-book"></i>
      </div>
      <div>
        <p class="text-sm text-slate-500">Total Publikasi</p>
        <p class="text-2xl font-bold text-slate-900">{{ $stats['total_publikasi'] }}</p>
      </div>
    </div>
  </div>

  {{-- Content Grid --}}
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Ringkasan Data (2/3) --}}
    <div class="lg:col-span-2 rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
      <div class="border-b border-slate-200 px-6 py-4">
        <h3 class="font-semibold text-slate-900">Ringkasan Data</h3>
      </div>
      <div class="p-6 space-y-3">
        <div class="flex justify-between items-center rounded-lg bg-slate-50 px-4 py-3">
          <span class="text-sm text-slate-600">Mahasiswa Aktif</span>
          <span class="font-semibold text-slate-900">{{ $stats['mhs_aktif'] }}</span>
        </div>
        <div class="flex justify-between items-center rounded-lg bg-slate-50 px-4 py-3">
          <span class="text-sm text-slate-600">Mahasiswa Cuti</span>
          <span class="font-semibold text-slate-900">{{ $stats['mhs_cuti'] }}</span>
        </div>
        <div class="flex justify-between items-center rounded-lg bg-slate-50 px-4 py-3">
          <span class="text-sm text-slate-600">Mahasiswa Lulus</span>
          <span class="font-semibold text-slate-900">{{ $stats['mhs_lulus'] }}</span>
        </div>
        <div class="flex justify-between items-center rounded-lg bg-slate-50 px-4 py-3">
          <span class="text-sm text-slate-600">Dosen Aktif</span>
          <span class="font-semibold text-slate-900">{{ $stats['dosen_aktif'] }}</span>
        </div>
      </div>
    </div>

    {{-- Dosen Publikasi Terbanyak (1/3) --}}
    <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
      <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
        <h3 class="font-semibold text-slate-900">Top Publikasi Dosen</h3>
        <a href="{{ route('admin.publikasi.index') }}" class="text-sm text-blue-600 font-medium hover:underline">Lihat
          Semua</a>
      </div>
      <div class="divide-y divide-slate-100">
        @forelse ($topPublikasi as $i => $dosen)
          <div class="flex items-center gap-3 px-6 py-3">
            <span class="w-5 text-xs font-bold text-slate-400">{{ $i + 1 }}</span>
              <x-avatar :src="$dosen->foto" :initials="$dosen->initials" size="sm" />
            <div class="flex-1 min-w-0">
              <div class="text-sm font-medium text-slate-900 truncate">{{ $dosen->nama_lengkap }}</div>
              <div class="text-xs text-slate-500">NIDN {{ $dosen->nidn }}</div>
            </div>
            <span
              class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold shrink-0">
              {{ $dosen->publikasi_count }}
            </span>
          </div>
        @empty
          <div class="p-6">
            <p class="text-sm text-slate-500 text-center py-4">Belum ada data publikasi.</p>
          </div>
        @endforelse
      </div>
    </div>
  </div>
@endsection
