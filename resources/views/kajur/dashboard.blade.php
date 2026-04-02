@extends('layouts.app')

@section('title', 'Dashboard Ketua Jurusan')

@section('sidebar')
  @include('kajur.sidebar')
@endsection

@section('content')
  {{-- Banner --}}
  <div class="relative h-40 overflow-hidden rounded-xl bg-gradient-to-br from-blue-800 to-blue-500 mb-6">
    <div class="absolute inset-0 flex flex-col items-center justify-center text-white text-center px-4">
      <h1 class="text-2xl font-bold mb-1">Selamat Datang, {{ auth()->user()->display_name }}</h1>
      <p class="text-sm opacity-80">Dashboard Ketua Jurusan — Informatika</p>
    </div>
  </div>

  {{-- Stat Jurusan --}}
  <p class="text-xs font-semibold uppercase tracking-widest text-slate-400 mb-2">Ringkasan Jurusan</p>
  <div class="grid grid-cols-2 gap-3 mb-5">

    <div class="flex items-center gap-3 rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
      <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-100 text-blue-600">
        <i class="fas fa-users text-sm"></i>
      </div>
      <div>
        <p class="text-xs text-slate-500">Total Mahasiswa</p>
        <p class="text-xl font-bold text-slate-900">{{ $totalMahasiswa }}</p>
      </div>
    </div>

    <div class="flex items-center gap-3 rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
      <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-orange-100 text-orange-600">
        <i class="fas fa-user-clock text-sm"></i>
      </div>
      <div>
        <p class="text-xs text-slate-500">Menunggu Pembimbing</p>
        <p class="text-xl font-bold text-slate-900">{{ $menungguPembimbing }}</p>
      </div>
    </div>

    <div class="flex items-center gap-3 rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
      <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-orange-100 text-orange-600">
        <i class="fas fa-clipboard-list text-sm"></i>
      </div>
      <div>
        <p class="text-xs text-slate-500">Menunggu Penguji</p>
        <p class="text-xl font-bold text-slate-900">{{ $menungguPenguji }}</p>
      </div>
    </div>

    <div class="flex items-center gap-3 rounded-xl border border-slate-200 bg-white p-4 shadow-sm">
      <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-orange-100 text-orange-600">
        <i class="fas fa-file-signature text-sm"></i>
      </div>
      <div>
        <p class="text-xs text-slate-500">Menunggu Persetujuan Hasil & Skripsi</p>
        <p class="text-xl font-bold text-slate-900">{{ $menungguPersetujuan }}</p>
      </div>
    </div>
  </div>

  {{-- Stat Dosen Pribadi --}}
  <p class="text-xs font-semibold uppercase tracking-widest text-slate-400 mb-2">Aktivitas Saya sebagai Dosen</p>
  <div class="grid grid-cols-2 gap-3 mb-6">

    <a href="{{ route('dosen.bimbingan.index') }}"
      class="flex items-center gap-3 rounded-xl border border-slate-200 bg-white p-4 shadow-sm hover:-translate-y-0.5 hover:shadow-md transition-all">
      <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-blue-100 text-blue-600">
        <i class="fas fa-chalkboard-teacher text-sm"></i>
      </div>
      <div>
        <p class="text-xs text-slate-500">Mahasiswa Bimbingan</p>
        <p class="text-xl font-bold text-slate-900">{{ $totalMahasiswaBimbingan }}</p>
      </div>
    </a>

    <a href="{{ route('dosen.publikasi.index') }}"
      class="flex items-center gap-3 rounded-xl border border-slate-200 bg-white p-4 shadow-sm hover:-translate-y-0.5 hover:shadow-md transition-all">
      <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600">
        <i class="fas fa-book text-sm"></i>
      </div>
      <div>
        <p class="text-xs text-slate-500">Total Publikasi</p>
        <p class="text-xl font-bold text-slate-900">{{ $totalPublikasi }}</p>
      </div>
    </a>
  </div>

  {{-- Card Grid --}}
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- Card: Mahasiswa Bimbingan Aktif --}}
    <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
      <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
        <h3 class="font-semibold text-slate-900">Mahasiswa Bimbingan Aktif</h3>
        <a href="{{ route('dosen.bimbingan.mahasiswa') }}" class="text-sm text-blue-600 font-medium hover:underline">
          Lihat Semua →
        </a>
      </div>
      <div class="divide-y divide-slate-100">
        @forelse ($mahasiswaBimbingan as $dp)
          <div class="flex items-center gap-3 px-6 py-3">
            <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-sm shrink-0">
              <i class="fas fa-user-graduate"></i>
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-sm font-medium text-slate-900 truncate">{{ $dp->mahasiswa->nama_lengkap }}</p>
              <p class="text-xs text-slate-500">
                {{ $dp->mahasiswa->nim }} · {{ $dp->mahasiswa->tugasAkhir?->tahapan ?? '-' }}
              </p>
            </div>
            <span class="text-xs text-slate-400 shrink-0">{{ $dp->getJenisPembimbing() }}</span>
          </div>
        @empty
          <div class="px-6 py-8 text-center text-sm text-slate-500">Belum ada mahasiswa bimbingan.</div>
        @endforelse
      </div>
    </div>

    {{-- Card: Publikasi Terbaru --}}
    <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
      <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
        <h3 class="font-semibold text-slate-900">Publikasi Terbaru</h3>
        <a href="{{ route('dosen.publikasi.index') }}" class="text-sm text-blue-600 font-medium hover:underline">
          Kelola →
        </a>
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
              <p class="text-xs text-slate-500">{{ $pub->tahun }} · {{ ucfirst($pub->jenis_publikasi) }}</p>
            </div>
          </div>
        @empty
          <div class="px-6 py-8 text-center text-sm text-slate-500">Belum ada data publikasi.</div>
        @endforelse
      </div>
    </div>

  </div>
@endsection
