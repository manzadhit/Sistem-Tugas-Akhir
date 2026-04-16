@extends('layouts.app')

@section('title', 'Manajemen Mata Kuliah')

@section('sidebar')
  @include('admin.sidebar')
@endsection

@section('content')

  <x-alert type="success" />
  <x-alert type="error" />
  <x-alert type="warning" />

  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 md:gap-4 mb-5 md:mb-8">
    <div>
      <h1 class="text-xl md:text-3xl font-bold text-gray-900 mb-0.5 md:mb-2">Kelola Mata Kuliah</h1>
      <p class="text-gray-500 text-sm">Kelola master data mata kuliah yang dipakai pada proses tugas akhir.</p>
    </div>
    <div class="flex flex-wrap items-center gap-2 shrink-0">
      <a href="{{ asset('templates/template-mata-kuliah.csv') }}" download
        class="inline-flex items-center gap-2 px-4 md:px-5 py-2 md:py-3 border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition-all shadow-sm">
        <i class="fas fa-file-arrow-down text-xs"></i>
        Unduh Template
      </a>
      <button type="button" x-data x-on:click="$dispatch('open-modal', 'import-mata-kuliah')"
        class="inline-flex items-center gap-2 px-4 md:px-5 py-2 md:py-3 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700 transition-all shadow-sm">
        <i class="fas fa-file-import text-xs"></i>
        Import Mata Kuliah
      </button>
      <button type="button" x-data x-on:click="$dispatch('open-modal', 'create-mata-kuliah')"
        class="inline-flex items-center gap-2 px-4 md:px-5 py-2 md:py-3 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-all shadow-sm">
        <i class="fas fa-plus text-xs"></i>
        Tambah Mata Kuliah
      </button>
    </div>
  </div>

  <div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="p-5 border-b border-gray-200 flex flex-wrap items-center justify-between gap-4">
      <h3 class="text-lg font-semibold text-gray-900">Daftar Mata Kuliah</h3>

      <form action="{{ route('admin.mata-kuliah.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
        <div class="relative">
          <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
          <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode atau nama..."
            class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm w-full md:w-64 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600/10 transition-colors" />
        </div>
      </form>
    </div>

    <div class="overflow-x-auto">
      <table class="w-full border-collapse">
        <thead>
          <tr class="bg-gray-50 border-b border-gray-200">
            <th class="hidden sm:table-cell px-5 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No</th>
            <th class="px-4 sm:px-5 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kode</th>
            <th class="px-4 sm:px-5 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Mata Kuliah</th>
            <th class="px-4 sm:px-5 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          @forelse ($mataKuliahs as $mataKuliah)
            @php
              $nomorUrut = ($mataKuliahs->currentPage() - 1) * $mataKuliahs->perPage() + $loop->iteration;
            @endphp
            <tr class="hover:bg-gray-50 transition-colors">
              <td class="hidden sm:table-cell px-5 py-4 text-sm text-gray-500">{{ $nomorUrut }}</td>
              <td class="px-4 sm:px-5 py-4">
                <span
                  class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
                  {{ $mataKuliah->kode }}
                </span>
              </td>
              <td class="px-4 sm:px-5 py-4 text-sm font-medium text-gray-900">{{ $mataKuliah->nama }}</td>
              <td class="px-4 sm:px-5 py-4">
                <div class="flex items-center gap-2">
                  <button type="button" x-data
                    x-on:click="$dispatch('open-edit-mata-kuliah', { id: '{{ $mataKuliah->id }}', kode: @js($mataKuliah->kode), nama: @js($mataKuliah->nama) }); $dispatch('open-modal', 'edit-mata-kuliah')"
                    class="w-8 h-8 rounded-md bg-amber-100 text-amber-700 flex items-center justify-center hover:bg-amber-200 transition-colors"
                    title="Edit">
                    <i class="fas fa-edit text-xs"></i>
                  </button>

                  <div x-data="{ open: false }" class="inline-block">
                    <button type="button" @click="open = true" title="Hapus"
                      class="w-8 h-8 rounded-md bg-red-100 text-red-600 flex items-center justify-center hover:bg-red-200 transition-colors">
                      <i class="fas fa-trash text-xs"></i>
                    </button>

                    <form method="POST" action="{{ route('admin.mata-kuliah.destroy', $mataKuliah) }}">
                      @csrf
                      @method('DELETE')
                      <x-modal-confirm model="open" title="Konfirmasi Hapus" theme="red" icon="fa-trash"
                        confirmText="Hapus">
                        Yakin ingin menghapus mata kuliah <strong>{{ $mataKuliah->kode }} - {{ $mataKuliah->nama }}</strong>?
                      </x-modal-confirm>
                    </form>
                  </div>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="4" class="px-4 sm:px-5 py-12 text-center text-gray-400">
                <i class="fas fa-book-open text-3xl mb-3 block opacity-30"></i>
                Tidak ada data mata kuliah.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <div class="mt-4">
    {{ $mataKuliahs->links() }}
  </div>

  @include('admin.mata-kuliah.partials.create-modal')
  @include('admin.mata-kuliah.partials.edit-modal')
  @include('admin.mata-kuliah.partials.import-modal')

@endsection
