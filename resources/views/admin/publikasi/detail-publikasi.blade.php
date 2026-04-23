@extends('layouts.app')

@section('title', 'Detail Publikasi')

@section('sidebar')
  @include('admin.sidebar')
@endsection

@section('content')

  <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
    <a href="{{ route('admin.publikasi.index') }}" class="hover:text-blue-600 transition-colors">
      Kelola Publikasi
    </a>
    <i class="fas fa-chevron-right text-xs text-gray-400"></i>
    <span class="text-gray-800 font-medium truncate">{{ Str::limit($publikasi->judul, 60) }}</span>
  </div>

  <div class="space-y-5">

    {{-- Informasi Publikasi --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
      <div class="px-5 py-3.5 border-b border-gray-100 flex items-center justify-between gap-4">
        <div class="flex items-center gap-2">
          <i class="fas fa-book text-blue-500 text-sm"></i>
          <h2 class="text-sm font-semibold text-gray-800">Informasi Publikasi</h2>
        </div>
        <div class="flex items-center gap-2 shrink-0">
          <a href="{{ route('admin.publikasi.edit', $publikasi->id) }}"
            class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs font-semibold bg-blue-600 text-white hover:bg-blue-700 transition-colors">
            <i class="fas fa-edit text-xs"></i> Edit
          </a>
          <button type="button"
            data-id="{{ $publikasi->id }}"
            data-judul="{{ Str::limit($publikasi->judul, 60) }}"
            onclick="window.dispatchEvent(new CustomEvent('open-delete-modal', { detail: { id: this.dataset.id, judul: this.dataset.judul } }))"
            class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs font-semibold bg-red-50 text-red-600 hover:bg-red-100 border border-red-200 transition-colors">
            <i class="fas fa-trash text-xs"></i> Hapus
          </button>
        </div>
      </div>
      @php
        $badgeCls = match ($publikasi->jenis_publikasi) {
            'jurnal' => 'bg-blue-100 text-blue-700 border-blue-200',
            'buku' => 'bg-purple-100 text-purple-700 border-purple-200',
            'haki' => 'bg-amber-100 text-amber-700 border-amber-200',
            default => 'bg-gray-100 text-gray-700 border-gray-200',
        };
      @endphp
      <div class="px-5 py-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

        <div class="flex items-start gap-3 sm:col-span-2 lg:col-span-3">
          <div
            class="w-8 h-8 rounded-lg bg-blue-50 text-blue-500 flex items-center justify-center text-xs shrink-0 mt-0.5">
            <i class="fas fa-heading"></i>
          </div>
          <div class="min-w-0">
            <div class="text-xs text-gray-400 mb-0.5">Judul</div>
            <div class="text-sm font-medium text-gray-800 break-words">{{ $publikasi->judul }}</div>
          </div>
        </div>

        <div class="flex items-start gap-3">
          <div
            class="w-8 h-8 rounded-lg bg-blue-50 text-blue-500 flex items-center justify-center text-xs shrink-0 mt-0.5">
            <i class="fas fa-tag"></i>
          </div>
          <div class="min-w-0">
            <div class="text-xs text-gray-400 mb-0.5">Jenis Publikasi</div>
            <div class="mt-0.5">
              <span
                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $badgeCls }}">
                {{ ucfirst($publikasi->jenis_publikasi) }}
              </span>
            </div>
          </div>
        </div>

        <div class="flex items-start gap-3">
          <div
            class="w-8 h-8 rounded-lg bg-blue-50 text-blue-500 flex items-center justify-center text-xs shrink-0 mt-0.5">
            <i class="fas fa-calendar"></i>
          </div>
          <div class="min-w-0">
            <div class="text-xs text-gray-400 mb-0.5">Tahun Terbit</div>
            <div class="text-sm font-medium text-gray-800 break-words">{{ $publikasi->tahun }}</div>
          </div>
        </div>

        <div class="flex items-start gap-3">
          <div
            class="w-8 h-8 rounded-lg bg-blue-50 text-blue-500 flex items-center justify-center text-xs shrink-0 mt-0.5">
            <i class="fas fa-building"></i>
          </div>
          <div class="min-w-0">
            <div class="text-xs text-gray-400 mb-0.5">Penerbit / Nama Jurnal</div>
            <div class="text-sm font-medium text-gray-800 break-words">{{ $publikasi->penerbit ?? '-' }}</div>
          </div>
        </div>

        <div class="flex items-start gap-3 sm:col-span-2 lg:col-span-3">
          <div
            class="w-8 h-8 rounded-lg bg-blue-50 text-blue-500 flex items-center justify-center text-xs shrink-0 mt-0.5">
            <i class="fas fa-link"></i>
          </div>
          <div class="min-w-0">
            <div class="text-xs text-gray-400 mb-0.5">URL / Tautan</div>
            @if ($publikasi->url)
              <a href="{{ $publikasi->url }}" target="_blank" rel="noopener noreferrer"
                class="text-sm font-medium text-blue-600 hover:underline break-all">
                {{ $publikasi->url }}
              </a>
            @else
              <div class="text-sm font-medium text-gray-800">-</div>
            @endif
          </div>
        </div>

      </div>
    </div>

    {{-- Dosen Penulis --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
      <div class="px-5 py-3.5 border-b border-gray-100 flex items-center gap-2">
        <i class="fas fa-chalkboard-teacher text-green-500 text-sm"></i>
        <h2 class="text-sm font-semibold text-gray-800">Dosen Penulis</h2>
      </div>
      @if ($publikasi->dosen)
        <div class="px-5 py-4 flex items-center justify-between gap-4">
          <div class="flex items-center gap-3 min-w-0">
            <x-avatar :src="$publikasi->dosen->foto" :initials="$publikasi->dosen->initials" />
            <div class="min-w-0">
              <div class="text-sm font-semibold text-gray-800 truncate">{{ $publikasi->dosen->nama_lengkap }}</div>
              <div class="text-xs text-gray-400">{{ $publikasi->dosen->nidn ?? '-' }}</div>
            </div>
          </div>
          <a href="{{ route('admin.dosen.show', $publikasi->dosen->id) }}"
            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium bg-white border border-gray-200 text-gray-600 hover:bg-gray-50 transition-colors shrink-0">
            <i class="fas fa-eye text-xs"></i> Lihat Profil
          </a>
        </div>
      @else
        <div class="px-5 py-4">
          <p class="text-sm text-gray-400 italic">Data dosen tidak tersedia.</p>
        </div>
      @endif
    </div>

  </div>

  {{-- Modal Konfirmasi Hapus --}}
  <div x-data="{ open: false, id: null, judul: '' }"
    x-on:open-delete-modal.window="open = true; id = $event.detail.id; judul = $event.detail.judul" x-show="open"
    x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm"
    style="display: none;">

    <div x-show="open" x-transition:enter="transition ease-out duration-200"
      x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
      @click.outside="open = false" class="bg-white rounded-2xl shadow-xl p-6 w-full max-w-sm mx-4">

      <div class="flex items-center gap-3 mb-4">
        <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center shrink-0">
          <i class="fas fa-triangle-exclamation text-red-600"></i>
        </div>
        <div>
          <h3 class="text-sm font-bold text-gray-900">Hapus Publikasi</h3>
          <p class="text-xs text-gray-500">Tindakan ini tidak dapat dibatalkan</p>
        </div>
      </div>

      <p class="text-sm text-gray-600 mb-5">
        Yakin ingin menghapus publikasi
        <span class="font-semibold text-gray-900" x-text='"&quot;" + judul + "&quot;"'></span>?
        Data ini akan dihapus secara permanen.
      </p>

      <div class="flex gap-3">
        <button @click="open = false" type="button"
          class="flex-1 px-4 py-2.5 rounded-lg text-sm font-medium border border-gray-300 text-gray-600 hover:bg-gray-50 transition-all">
          Batal
        </button>
        <form method="POST" :action="`/admin/publikasi/${id}`" class="flex-1">
          @csrf
          @method('DELETE')
          <button type="submit"
            class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg text-sm font-semibold bg-red-600 text-white hover:bg-red-700 transition-all">
            <i class="fas fa-trash text-xs"></i> Ya, Hapus
          </button>
        </form>
      </div>
    </div>
  </div>

@endsection
