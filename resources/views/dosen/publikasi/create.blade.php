@extends('layouts.app')

@section('title', 'Tambah Publikasi')

@section('sidebar')
  @if (auth()->user()->role === 'kajur')
    @include('kajur.sidebar')
  @else
    @include('dosen.sidebar')
  @endif
@endsection

@section('content')

  {{-- Breadcrumb --}}
  <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
    <a href="{{ route('dosen.publikasi.index') }}" class="hover:text-blue-600 transition-colors">Publikasi Saya</a>
    <i class="fas fa-chevron-right text-xs text-gray-400"></i>
    <span class="text-gray-800 font-medium">Tambah Publikasi</span>
  </div>

  {{-- Banner --}}
  <div class="bg-gradient-to-br from-blue-800 to-blue-500 rounded-2xl p-5 sm:p-8 mb-6 text-white">
    <h1 class="text-xl sm:text-2xl font-bold mb-1">
      <i class="fas fa-book-medical mr-2"></i>Tambah Publikasi
    </h1>
    <p class="opacity-90 text-sm">Tambahkan data publikasi ilmiah Anda</p>
  </div>

  {{-- Validation errors --}}
  @if ($errors->any())
    <div class="mb-5 bg-red-50 border border-red-200 rounded-xl px-5 py-4">
      <div class="flex items-center gap-2 text-red-700 font-semibold text-sm mb-2">
        <i class="fas fa-circle-exclamation"></i> Terdapat kesalahan input
      </div>
      <ul class="list-disc list-inside space-y-1">
        @foreach ($errors->all() as $e)
          <li class="text-sm text-red-600">{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('dosen.publikasi.store') }}">
    @csrf

    <div class="space-y-5">

      <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-5 py-3.5 border-b border-gray-100 flex items-center gap-2">
          <i class="fas fa-book text-blue-500 text-sm"></i>
          <h2 class="text-sm font-semibold text-gray-800">Data Publikasi</h2>
        </div>
        <div class="px-5 py-5 grid grid-cols-1 sm:grid-cols-2 gap-5">

          {{-- Judul --}}
          <div class="sm:col-span-2">
            <label class="block text-xs font-medium text-gray-600 mb-1.5">
              Judul Publikasi <span class="text-red-500">*</span>
            </label>
            <input type="text" name="judul" value="{{ old('judul') }}" required
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
              class="w-full pl-3 pr-8 py-2.5 border rounded-lg text-sm text-gray-800 bg-white focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 transition-all @error('jenis_publikasi') border-red-400 bg-red-50 @else border-gray-300 @enderror">
              <option value="">-- Pilih Jenis --</option>
              @foreach (['jurnal' => 'Jurnal', 'buku' => 'Buku', 'haki' => 'HaKI'] as $val => $lbl)
                <option value="{{ $val }}" {{ old('jenis_publikasi') === $val ? 'selected' : '' }}>
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
            <input type="number" name="tahun" value="{{ old('tahun', date('Y')) }}" required min="1900"
              max="{{ date('Y') + 1 }}" placeholder="{{ date('Y') }}"
              class="w-full px-4 py-2.5 border rounded-lg text-sm text-gray-800 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 transition-all @error('tahun') border-red-400 bg-red-50 @else border-gray-300 @enderror" />
            @error('tahun')
              <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
          </div>

          {{-- Penerbit --}}
          <div class="sm:col-span-2">
            <label class="block text-xs font-medium text-gray-600 mb-1.5">Penerbit / Nama Jurnal</label>
            <input type="text" name="penerbit" value="{{ old('penerbit') }}"
              placeholder="Contoh: Jurnal Informatika Vol. 15 No. 2 / UHO Press"
              class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-800 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 transition-all" />
            @error('penerbit')
              <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
          </div>

          {{-- URL --}}
          <div class="sm:col-span-2">
            <label class="block text-xs font-medium text-gray-600 mb-1.5">URL / Tautan</label>
            <div class="relative">
              <i class="fas fa-link absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
              <input type="url" name="url" value="{{ old('url') }}" placeholder="https://doi.org/..."
                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-800 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 transition-all" />
            </div>
            @error('url')
              <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
          </div>

        </div>
      </div>

      {{-- Action Buttons --}}
      <div class="flex items-center justify-end gap-3">
        <a href="{{ route('dosen.publikasi.index') }}"
          class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-medium border border-gray-300 text-gray-600 hover:bg-gray-50 transition-all">
          <i class="fas fa-arrow-left text-xs"></i> Batal
        </a>
        <button type="submit"
          class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-semibold bg-blue-600 text-white hover:bg-blue-700 transition-all shadow-sm">
          <i class="fas fa-floppy-disk text-xs"></i> Simpan Publikasi
        </button>
      </div>

    </div>
  </form>

@endsection
