@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('sidebar')
  <x-role-sidebar title="Panel Admin" subtitle="Teknik Informatika" :items="[
      [
          'href' => route('admin.dashboard'),
          'icon' => 'fas fa-chart-line',
          'label' => 'Dashboard',
          'active' => request()->routeIs('admin.dashboard'),
      ],
  
      ['section' => 'Kelola Data'],
      ['href' => '#', 'icon' => 'fas fa-user-graduate', 'label' => 'Kelola Mahasiswa'],
      ['href' => '#', 'icon' => 'fas fa-chalkboard-teacher', 'label' => 'Kelola Dosen'],
      ['href' => '#', 'icon' => 'fas fa-book', 'label' => 'Kelola Publikasi Dosen'],
  
      ['section' => 'Verifikasi Syarat'],
      ['href' => '#', 'icon' => 'fas fa-file-signature', 'label' => 'Proposal'],
      ['href' => '#', 'icon' => 'fas fa-chart-line', 'label' => 'Hasil'],
      ['href' => '#', 'icon' => 'fas fa-book-open', 'label' => 'Skripsi'],
  
      ['section' => 'Verifikasi Hasil'],
      ['href' => '#', 'icon' => 'fas fa-file-signature', 'label' => 'Proposal'],
      ['href' => '#', 'icon' => 'fas fa-chart-line', 'label' => 'Hasil'],
      ['href' => '#', 'icon' => 'fas fa-book-open', 'label' => 'Skripsi'],
  ]" />
@endsection

@section('content')
  {{-- Page Header --}}
  <div class="mb-8">
    <h1 class="text-2xl font-bold text-slate-900">Dashboard Admin</h1>
    <p class="text-slate-500 mt-1">Selamat datang kembali! Berikut ringkasan data Jurusan Teknik Informatika.</p>
  </div>

  {{-- Stats --}}
  <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
    <div
      class="flex items-center gap-4 rounded-xl border border-slate-200 bg-white p-5 shadow-sm hover:-translate-y-0.5 transition">
      <div
        class="flex h-14 w-14 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 text-xl text-white">
        <i class="fas fa-user-graduate"></i>
      </div>
      <div>
        <p class="text-sm text-slate-500">Total Mahasiswa</p>
        <p class="text-2xl font-bold text-slate-900">0</p>
      </div>
    </div>
    <div
      class="flex items-center gap-4 rounded-xl border border-slate-200 bg-white p-5 shadow-sm hover:-translate-y-0.5 transition">
      <div
        class="flex h-14 w-14 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 text-xl text-white">
        <i class="fas fa-chalkboard-teacher"></i>
      </div>
      <div>
        <p class="text-sm text-slate-500">Total Dosen</p>
        <p class="text-2xl font-bold text-slate-900">0</p>
      </div>
    </div>
    <div
      class="flex items-center gap-4 rounded-xl border border-slate-200 bg-white p-5 shadow-sm hover:-translate-y-0.5 transition">
      <div
        class="flex h-14 w-14 items-center justify-center rounded-xl bg-gradient-to-br from-amber-500 to-amber-600 text-xl text-white">
        <i class="fas fa-book"></i>
      </div>
      <div>
        <p class="text-sm text-slate-500">Total Publikasi</p>
        <p class="text-2xl font-bold text-slate-900">0</p>
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
          <span class="font-semibold text-slate-900">0</span>
        </div>
        <div class="flex justify-between items-center rounded-lg bg-slate-50 px-4 py-3">
          <span class="text-sm text-slate-600">Mahasiswa Cuti</span>
          <span class="font-semibold text-slate-900">0</span>
        </div>
        <div class="flex justify-between items-center rounded-lg bg-slate-50 px-4 py-3">
          <span class="text-sm text-slate-600">Mahasiswa Lulus</span>
          <span class="font-semibold text-slate-900">0</span>
        </div>
        <div class="flex justify-between items-center rounded-lg bg-slate-50 px-4 py-3">
          <span class="text-sm text-slate-600">Dosen Aktif</span>
          <span class="font-semibold text-slate-900">0</span>
        </div>
      </div>
    </div>

    {{-- Dosen Publikasi Terbanyak (1/3) --}}
    <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
      <div class="flex items-center justify-between border-b border-slate-200 px-6 py-4">
        <h3 class="font-semibold text-slate-900">Top Publikasi Dosen</h3>
        <a href="#" class="text-sm text-blue-600 font-medium hover:underline">Lihat Semua</a>
      </div>
      <div class="p-6">
        <p class="text-sm text-slate-500 text-center py-6">Belum ada data publikasi.</p>
      </div>
    </div>
  </div>
@endsection
