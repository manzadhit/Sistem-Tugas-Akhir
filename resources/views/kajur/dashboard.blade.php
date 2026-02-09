@extends('layouts.app')

@section('title', 'Dashboard Ketua Jurusan')

@section('sidebar')
  @include('kajur.sidebar')
@endsection

@section('content')
  {{-- Banner --}}
  <div class="relative h-48 overflow-hidden rounded-xl bg-gradient-to-br from-blue-800 to-blue-500 mb-8">
    <div class="absolute inset-0 flex flex-col items-center justify-center text-white text-center px-4">
      <h1 class="text-2xl font-bold">Dashboard Ketua Jurusan</h1>
    </div>
  </div>

  {{-- Stats --}}
  <div class="flex flex-wrap gap-6 mb-8">
    <div class="flex flex-1 items-center gap-4 rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
      <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-blue-100 text-xl text-blue-600">
        <i class="fas fa-users"></i>
      </div>
      <div>
        <p class="text-sm text-slate-500">Total Mahasiswa</p>
        <p class="text-2xl font-bold text-slate-900">{{ $totalMahasiswa }}</p>
      </div>
    </div>
    <div class="flex flex-1 items-center gap-4 rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
      <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-orange-100 text-xl text-orange-600">
        <i class="fas fa-user-clock"></i>
      </div>
      <div>
        <p class="text-sm text-slate-500">Menunggu Pembimbing</p>
        <p class="text-2xl font-bold text-slate-900">{{ $menungguPembimbing }}</p>
      </div>
    </div>
    <div class="flex flex-1 items-center gap-4 rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
      <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-orange-100 text-xl text-orange-600">
        <i class="fas fa-clipboard-list"></i>
      </div>
      <div>
        <p class="text-sm text-slate-500">Menunggu Penguji</p>
        <p class="text-2xl font-bold text-slate-900">0</p>
      </div>
    </div>
  </div>

  {{-- Welcome Card --}}
  <div class="rounded-xl border border-slate-200 bg-white p-8 shadow-sm">
    <h2 class="text-xl font-semibold text-slate-900 mb-3">Selamat Datang di Dashboard Ketua Jurusan</h2>
    <p class="text-slate-500 leading-relaxed">
      Gunakan menu navigasi di sebelah kiri untuk mengelola permintaan dosen pembimbing dan penguji tugas akhir mahasiswa.
      Sistem akan memberikan rekomendasi dosen yang sesuai berdasarkan keahlian dan ketersediaan mereka.
    </p>
  </div>
@endsection
