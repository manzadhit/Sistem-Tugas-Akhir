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
    <x-stats label="Mahasiswa Bimbingan" total=10 bg-color="bg-blue-100" text-color='text-blue-600'> 
      <x-slot:icon>
        <i class="fas fa-users"></i>
      </x-slot:icon>
    </x-stats>

    <x-stats label="Total Publikasi" total=10 bg-color="bg-emerald-100" text-color='text-emerald-600'> 
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
        <a href="#" class="text-sm text-blue-600 font-medium hover:underline">Lihat Semua →</a>
      </div>
      <div class="p-6">
        <p class="text-sm text-slate-500 text-center py-6">Belum ada mahasiswa bimbingan.</p>
      </div>
    </div>

    {{-- Publikasi Terbaru --}}
    <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
      <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
        <h3 class="font-semibold text-slate-900">Publikasi Terbaru</h3>
        <a href="#" class="text-sm text-blue-600 font-medium hover:underline">Kelola →</a>
      </div>
      <div class="p-6">
        <p class="text-sm text-slate-500 text-center py-6">Belum ada data publikasi.</p>
      </div>
    </div>
  </div>
@endsection
