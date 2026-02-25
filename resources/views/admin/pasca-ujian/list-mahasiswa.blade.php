@extends('layouts.app')

@section('title', 'Pasca Ujian')

@section('sidebar')
  @include('admin.sidebar')
@endsection

@section('content')
  {{-- Banner --}}
  <div class="relative h-48 mb-8 overflow-hidden bg-gradient-to-br from-blue-800 to-blue-500 rounded-xl rounded-2xl">
    <div class="absolute inset-0 flex items-center justify-center bg-black/10">
      <h1 class="px-4 text-3xl font-bold text-center text-white">Verifikasi Hasil Ujian</h1>
    </div>
  </div>

  {{-- Table Container --}}
  <div class="overflow-hidden bg-white shadow-sm rounded-xl">
    {{-- Table Header --}}
    <div
      class="flex flex-col flex-wrap items-start justify-between gap-4 p-6 border-b border-gray-200 md:flex-row md:items-center">
      <div class="flex-1">
        <h2 class="mb-2 text-xl font-semibold text-gray-900">Daftar Pengajuan Hasil Ujian</h2>
        <p class="text-sm text-gray-500">Mahasiswa yang mengajukan verifikasi berkas pasca ujian</p>
      </div>
      <div class="flex items-center gap-3">
        <div class="relative">
          <i class="absolute text-gray-400 transform -translate-y-1/2 fas fa-search left-3 top-1/2"></i>
          <input type="text" placeholder="Cari mahasiswa/judul..."
            class="w-[250px] py-2 pl-10 pr-4 text-sm transition border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10">
        </div>
        <button
          class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 transition bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:border-gray-400">
          <i class="fas fa-filter"></i>
          Filter
        </button>
      </div>
    </div>

    {{-- Table Wrapper --}}
    <div class="overflow-x-auto">
      <table class="w-full text-left border-collapse">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-xs font-semibold tracking-wider text-gray-500 uppercase whitespace-nowrap">No</th>
            <th class="px-6 py-3 text-xs font-semibold tracking-wider text-gray-500 uppercase whitespace-nowrap">Mahasiswa
            </th>
            <th class="px-6 py-3 text-xs font-semibold tracking-wider text-gray-500 uppercase whitespace-nowrap">Jenis
              Ujian</th>
            <th class="px-6 py-3 text-xs font-semibold tracking-wider text-gray-500 uppercase whitespace-nowrap">Berkas
            </th>
            <th class="px-6 py-3 text-xs font-semibold tracking-wider text-gray-500 uppercase whitespace-nowrap">Tanggal
              Pengajuan</th>
            <th class="px-6 py-3 text-xs font-semibold tracking-wider text-gray-500 uppercase whitespace-nowrap">Status
            </th>
            <th class="px-6 py-3 text-xs font-semibold tracking-wider text-gray-500 uppercase whitespace-nowrap">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          @forelse ($ujians as $item)
            <tr class="transition hover:bg-gray-50">
              <td class="px-6 py-4 text-sm text-gray-700 align-middle">{{ $loop->iteration }}</td>
              <td class="px-6 py-4 text-sm text-gray-700 align-middle">
                <div class="flex flex-col gap-1">
                  <span class="font-semibold text-gray-900">{{ $item->tugasAkhir->mahasiswa->nama_lengkap }}</span>
                  <span class="text-xs text-gray-500">{{ $item->tugasAkhir->mahasiswa->nim }}</span>
                </div>
              </td>
              <td class="px-6 py-4 text-sm text-gray-700 align-middle">
                <span
                  class="inline-block px-3 py-1 text-xs font-semibold text-blue-800 whitespace-nowrap bg-blue-100 rounded-full">{{ ucfirst($item->jenis_ujian) }}</span>
              </td>
              <td class="px-6 py-4 text-sm text-gray-700 align-middle">
                <span>{{ $item->dokumenUjian->count() }} berkas</span>
              </td>
              <td class="px-6 py-4 text-sm text-gray-700 align-middle">
                <span class="text-xs text-gray-500 whitespace-nowrap">{{ $item->created_at->format('d M Y') }}</span>
              </td>
              <td class="px-6 py-4 text-sm text-gray-700 align-middle">
                <span
                  class="inline-block px-3 py-1 text-xs font-semibold text-amber-800 whitespace-nowrap bg-amber-100 rounded-full">{{ ucwords(str_replace('_', ' ', $item->status)) }}</span>
              </td>
              <td class="px-6 py-4 text-sm text-gray-700 align-middle">
                <a class="inline-flex items-center justify-center px-5 py-2 text-xs font-medium text-white transition rounded-lg whitespace-nowrap bg-gradient-to-br from-blue-500 to-blue-600 hover:-translate-y-px hover:shadow-md hover:shadow-blue-500/30"
                  href="{{ route('admin.ujian.hasil-ujian.detail', ['jenis' => $item->jenis_ujian, 'id' => $item->id]) }}">Verifikasi</a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="px-6 py-10 text-sm text-center text-gray-400">
                <i class="mb-2 text-2xl fas fa-inbox block"></i>
                Belum ada pengajuan hasil ujian {{ $jenis }} yang menunggu verifikasi.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- Pagination --}}
    <div class="flex items-center justify-between p-6 border-t border-gray-200">
      <div class="text-sm text-gray-500">
        Menampilkan 1-4 dari 4 mahasiswa
      </div>
      <div class="flex gap-2">
        <button
          class="px-3 py-2 text-sm text-gray-500 transition bg-white border border-gray-300 rounded-md cursor-not-allowed opacity-50"
          disabled>
          <i class="fas fa-chevron-left"></i>
        </button>
        <button class="px-3 py-2 text-sm text-white transition bg-blue-600 border border-blue-600 rounded-md">1</button>
        <button
          class="px-3 py-2 text-sm text-gray-500 transition bg-white border border-gray-300 rounded-md cursor-not-allowed opacity-50"
          disabled>
          <i class="fas fa-chevron-right"></i>
        </button>
      </div>
    </div>
  </div>
@endsection
