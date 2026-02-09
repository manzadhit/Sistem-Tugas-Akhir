@extends('layouts.app')

@section('title', 'Permintaan Pembimbing')

@section('sidebar')
  @include('kajur.sidebar')
@endsection

@section('content')
  {{-- Banner --}}
  <div class="relative h-48 overflow-hidden rounded-xl bg-gradient-to-br from-blue-800 to-blue-500 mb-8">
    <div class="absolute inset-0 flex flex-col items-center justify-center text-white text-center px-4">
      <h1 class="text-3xl font-bold">Permintaan Dosen Pembimbing</h1>
    </div>
  </div>

  <!-- Table -->
  <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
    <!-- Table Header -->
    <div class="flex flex-col gap-4 border-b border-slate-200 p-5 sm:flex-row sm:items-center sm:justify-between">
      <div>
        <h2 class="text-lg font-semibold text-slate-800">Daftar Mahasiswa</h2>
        <p class="mt-1 text-sm text-slate-500">Mahasiswa yang mengajukan permintaan dosen pembimbing tugas akhir</p>
      </div>
      <div class="flex items-center gap-3">
        <div class="relative">
          <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-sm text-slate-400"></i>
          <input type="text" placeholder="Cari mahasiswa..."
            class="rounded-lg border border-slate-200 py-2 pl-9 pr-4 text-sm text-slate-600 placeholder-slate-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
        </div>
        <button
          class="inline-flex items-center gap-2 rounded-lg border border-slate-200 px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">
          <i class="fas fa-filter text-xs"></i>
          Filter
        </button>
      </div>
    </div>

    <!-- Table Content -->
    <div class="overflow-x-auto">
      <table class="w-full text-left text-sm">
        <thead>
          <tr class="border-b border-slate-200 bg-slate-50">
            <th class="whitespace-nowrap px-5 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">No</th>
            <th class="whitespace-nowrap px-5 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">
              Mahasiswa</th>
            <th class="whitespace-nowrap px-5 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">Judul
              Tugas Akhir</th>
            <th class="whitespace-nowrap px-5 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">Tanggal
              Pengajuan</th>
            <th class="whitespace-nowrap px-5 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">Status
            </th>
            <th class="whitespace-nowrap px-5 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">Aksi
            </th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-200">
          @foreach ($permintaanPembimbing as $index => $permintaan)
            <tr class="hover:bg-slate-50">
              <td class="whitespace-nowrap px-5 py-4 text-slate-600">{{ $index + 1 }}</td>
              <td class="px-5 py-4">
                <div class="flex flex-col">
                  <span class="font-medium text-slate-800">{{ $permintaan->mahasiswa->nama_lengkap }}</span>
                  <span class="text-xs text-slate-500">{{ $permintaan->mahasiswa->nim }}</span>
                </div>
              </td>
              <td class="px-5 py-4">
                <div class="max-w-xs text-slate-600 leading-relaxed">
                  {{ $permintaan->judul_ta }}
                </div>
              </td>
              <td class="whitespace-nowrap px-5 py-4 text-slate-500">
                {{ $permintaan->created_at->translatedFormat('d M Y') }}</td>
              <td class="px-5 py-4">
                <span
                  class="inline-flex rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800">{{ $permintaan->status }}</span>
              </td> 
              <td class="px-5 py-4">
                <a href="{{ route('kajur.penetapan-pembimbing', ['permintaan' => $permintaan->id]) }}"
                  class="inline-flex items-center rounded-lg bg-blue-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-blue-700 transition">Tetapkan</a>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div class="flex flex-col items-center justify-between gap-3 border-t border-slate-200 px-5 py-4 sm:flex-row">
      <p class="text-sm text-slate-500">Menampilkan 1-8 dari 8 mahasiswa</p>
      <div class="flex items-center gap-1">
        <button disabled
          class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 text-slate-400 disabled:opacity-50">
          <i class="fas fa-chevron-left text-xs"></i>
        </button>
        <button
          class="inline-flex h-8 w-8 items-center justify-center rounded-lg bg-blue-600 text-sm font-medium text-white">1</button>
        <button disabled
          class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 text-slate-400 disabled:opacity-50">
          <i class="fas fa-chevron-right text-xs"></i>
        </button>
      </div>
    </div>
  </div>
@endsection
