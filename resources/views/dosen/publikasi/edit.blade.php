@extends('layouts.app')

@section('title', 'Edit Publikasi - ' . Str::limit($publikasi->judul, 50))

@section('sidebar')
  @include('dosen.sidebar')
@endsection

@section('content')

  {{-- Breadcrumb --}}
  <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
    <a href="{{ route('dosen.publikasi.index') }}" class="hover:text-blue-600 transition-colors">Publikasi Saya</a>
    <i class="fas fa-chevron-right text-xs text-gray-400"></i>
    <a href="{{ route('dosen.publikasi.show', $publikasi->id) }}"
      class="hover:text-blue-600 transition-colors truncate max-w-[160px]">{{ Str::limit($publikasi->judul, 50) }}</a>
    <i class="fas fa-chevron-right text-xs text-gray-400"></i>
    <span class="text-gray-800 font-medium">Edit</span>
  </div>

  {{-- Banner --}}
  <div class="bg-gradient-to-br from-blue-800 to-blue-500 rounded-2xl p-5 sm:p-8 mb-6 text-white">
    <div class="flex items-center gap-4">
      @php
        $initials = collect(explode(' ', $publikasi->judul))
            ->filter()
            ->take(2)
            ->map(fn($w) => strtoupper($w[0]))
            ->implode('');
      @endphp
      <div
        class="w-14 h-14 rounded-2xl bg-white/20 border-2 border-white/30 flex items-center justify-center text-xl font-bold shrink-0">
        {{ $initials }}
      </div>
      <div>
        <h1 class="text-xl sm:text-2xl font-bold mb-0.5">Edit Publikasi</h1>
        <p class="opacity-80 text-sm">{{ Str::limit($publikasi->judul, 60) }}</p>
      </div>
    </div>
  </div>

  <!-- Flash Messages -->
  <x-alert type="success" />
  <x-alert type="error" />
  <x-alert type="warning" />

  <form method="POST" action="{{ route('dosen.publikasi.update', $publikasi->id) }}">
    @csrf
    @method('PUT')

    <div class="space-y-5">

      {{-- ██ DATA PUBLIKASI --}}
      <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-5 py-3.5 border-b border-gray-100 flex items-center gap-2">
          <i class="fas fa-book text-blue-500 text-sm"></i>
          <h2 class="text-sm font-semibold text-gray-800">Data Publikasi</h2>
        </div>
        <div class="px-5 py-5 grid grid-cols-1 sm:grid-cols-2 gap-5">

          {{-- Judul --}}
          <div class="sm:col-span-2">
            <label class="block text-xs font-medium text-gray-600 mb-1.5">
              Judul <span class="text-red-500">*</span>
            </label>
            <input type="text" name="judul" value="{{ old('judul', $publikasi->judul) }}" required
              placeholder="Masukkan judul publikasi"
              class="w-full px-4 py-2.5 border rounded-lg text-sm text-gray-800 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 transition-all @error('judul') border-red-400 bg-red-50 @else border-gray-300 @enderror" />
            @error('judul')
              <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
          </div>

          {{-- Jenis Publikasi --}}
          <div>
            <label class="block text-xs font-medium text-gray-600 mb-1.5">
              Jenis Publikasi <span class="text-red-500">*</span>
            </label>
            <select name="jenis_publikasi" required
              class="w-full pl-3 pr-8 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-800 bg-white focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 transition-all @error('jenis_publikasi') border-red-400 bg-red-50 @enderror">
              <option value="">-- Pilih Jenis --</option>
              @foreach (['jurnal' => 'Jurnal', 'buku' => 'Buku', 'haki' => 'HaKI'] as $val => $lbl)
                <option value="{{ $val }}"
                  {{ old('jenis_publikasi', $publikasi->jenis_publikasi) === $val ? 'selected' : '' }}>
                  {{ $lbl }}
                </option>
              @endforeach
            </select>
            @error('jenis_publikasi')
              <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
          </div>

          {{-- Tahun --}}
          <div>
            <label class="block text-xs font-medium text-gray-600 mb-1.5">
              Tahun Terbit <span class="text-red-500">*</span>
            </label>
            <input type="number" name="tahun" value="{{ old('tahun', $publikasi->tahun) }}" required min="1900"
              max="{{ date('Y') }}"
              class="w-full px-4 py-2.5 border rounded-lg text-sm text-gray-800 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 transition-all @error('tahun') border-red-400 bg-red-50 @else border-gray-300 @enderror" />
            @error('tahun')
              <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
          </div>

          {{-- Penerbit --}}
          <div class="sm:col-span-2">
            <label class="block text-xs font-medium text-gray-600 mb-1.5">Penerbit / Nama Jurnal</label>
            <input type="text" name="penerbit" value="{{ old('penerbit', $publikasi->penerbit) }}"
              placeholder="Nama penerbit atau jurnal (opsional)"
              class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-800 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 transition-all" />
          </div>

          {{-- URL --}}
          <div class="sm:col-span-2">
            <label class="block text-xs font-medium text-gray-600 mb-1.5">URL / Tautan</label>
            <div class="relative">
              <i class="fas fa-link absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
              <input type="url" name="url" value="{{ old('url', $publikasi->url) }}"
                placeholder="https://... (opsional)"
                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-800 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 transition-all" />
            </div>
            @error('url')
              <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
          </div>

        </div>
      </div>

      {{-- ██ Action Buttons --}}
      <div class="flex items-center justify-between gap-3">
        <a href="{{ route('dosen.publikasi.show', $publikasi->id) }}"
          class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-medium border border-gray-300 text-gray-600 hover:bg-gray-50 transition-all">
          <i class="fas fa-arrow-left text-xs"></i> Batal
        </a>
        <div class="flex items-center gap-2">
          <button type="button" x-data @click="$dispatch('open-delete-modal')"
            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-semibold border border-red-300 text-red-600 hover:bg-red-50 transition-all">
            <i class="fas fa-trash text-xs"></i> Hapus
          </button>
          <button type="submit"
            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-semibold bg-blue-600 text-white hover:bg-blue-700 transition-all shadow-sm">
            <i class="fas fa-save text-xs"></i> Simpan Perubahan
          </button>
        </div>
      </div>

    </div>
  </form>

  {{-- Modal Konfirmasi Hapus --}}
  <div x-data="{ open: false }" x-on:open-delete-modal.window="open = true" x-show="open" x-transition.opacity
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm" style="display: none;">

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
        <span class="font-semibold text-gray-900">{{ Str::limit($publikasi->judul, 60) }}</span>?
        Data ini akan dihapus secara permanen.
      </p>

      <div class="flex gap-3">
        <button @click="open = false" type="button"
          class="flex-1 px-4 py-2.5 rounded-lg text-sm font-medium border border-gray-300 text-gray-600 hover:bg-gray-50 transition-all">
          Batal
        </button>
        <form method="POST" action="{{ route('dosen.publikasi.destroy', $publikasi->id) }}" class="flex-1">
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
