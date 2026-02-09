@extends('layouts.app')

@section('title', 'Dashboard Dosen')

@section('sidebar')
  <x-role-sidebar title="Portal Dosen" :items="[
      [
          'href' => route('dosen.dashboard'),
          'icon' => 'fas fa-chart-line',
          'label' => 'Dashboard',
          'active' => request()->routeIs('dosen.dashboard'),
      ],
  
      ['section' => 'Pembimbingan'],
      ['href' => '#', 'icon' => 'fas fa-users', 'label' => 'Mahasiswa Bimbingan'],
  
      ['section' => 'Pengujian'],
      ['href' => '#', 'icon' => 'fas fa-envelope-open-text', 'label' => 'Undangan'],
      ['href' => '#', 'icon' => 'fas fa-clipboard-list', 'label' => 'Jadwal Ujian'],
      ['href' => '#', 'icon' => 'fas fa-edit', 'label' => 'Input Nilai'],
  
      ['section' => 'Publikasi'],
      ['href' => '#', 'icon' => 'fas fa-book', 'label' => 'Publikasi Saya'],
  ]" />
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
    <div class="flex items-center gap-4 rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
      <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-blue-100 text-xl text-blue-600">
        <i class="fas fa-users"></i>
      </div>
      <div>
        <p class="text-sm text-slate-500">Mahasiswa Bimbingan</p>
        <p class="text-2xl font-bold text-slate-900">0</p>
      </div>
    </div>
    <div class="flex items-center gap-4 rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
      <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-emerald-100 text-xl text-emerald-600">
        <i class="fas fa-book"></i>
      </div>
      <div>
        <p class="text-sm text-slate-500">Total Publikasi</p>
        <p class="text-2xl font-bold text-slate-900">0</p>
      </div>
    </div>
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
