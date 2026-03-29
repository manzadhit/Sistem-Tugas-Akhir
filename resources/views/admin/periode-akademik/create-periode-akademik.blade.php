@extends('layouts.app')

@section('title', 'Tambah Periode Akademik')

@section('sidebar')
  @include('admin.sidebar')
@endsection

@section('content')

  <!-- Breadcrumb -->
  <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
    <a href="{{ route('admin.periode.index') }}" class="hover:text-blue-600 transition-colors">Kelola Periode Akademik</a>
    <i class="fas fa-chevron-right text-xs text-gray-400"></i>
    <span class="text-gray-800 font-medium">Tambah Periode</span>
  </div>

  <!-- Banner -->
  <div class="bg-gradient-to-br from-blue-800 to-blue-500 rounded-2xl p-5 sm:p-8 mb-6 text-white">
    <h1 class="text-xl sm:text-2xl font-bold mb-1">
      <i class="fas fa-calendar-plus mr-2"></i>Tambah Periode Akademik
    </h1>
    <p class="opacity-90 text-sm">Isi data periode akademik, lalu pilih apakah ingin langsung diaktifkan atau disimpan sebagai draft terlebih dahulu.</p>
  </div>

  <!-- Validation Errors -->
  @if ($errors->any())
    <div class="mb-5 bg-red-50 border border-red-200 rounded-xl px-5 py-4">
      <div class="flex items-center gap-2 text-red-700 font-semibold text-sm mb-2">
        <i class="fas fa-circle-exclamation"></i> Terdapat kesalahan input
      </div>
      <ul class="list-disc list-inside space-y-1">
        @foreach ($errors->all() as $error)
          <li class="text-sm text-red-600">{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('admin.periode.store') }}">
    @csrf

    <div class="space-y-5">

      <!-- Form Fields -->
      <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-5 py-3.5 border-b border-gray-100 flex items-center gap-2">
          <i class="fas fa-calendar-alt text-blue-500 text-sm"></i>
          <h2 class="text-sm font-semibold text-gray-800">Data Periode Akademik</h2>
        </div>
        <div class="px-5 py-5 grid grid-cols-1 sm:grid-cols-2 gap-5">

          <!-- Tahun Ajaran -->
          <div>
            <label class="block text-xs font-medium text-gray-600 mb-1.5">
              Tahun Ajaran <span class="text-red-500">*</span>
            </label>
            <input
              type="text"
              name="tahun_ajaran"
              value="{{ old('tahun_ajaran') }}"
              placeholder="Contoh: 2025/2026"
              required
              class="w-full px-4 py-2.5 border rounded-lg text-sm text-gray-800 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 transition-all @error('tahun_ajaran') border-red-400 bg-red-50 @else border-gray-300 @enderror"
            />
            @error('tahun_ajaran')
              <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
          </div>

          <!-- Semester -->
          <div>
            <label class="block text-xs font-medium text-gray-600 mb-1.5">
              Semester <span class="text-red-500">*</span>
            </label>
            <select
              name="semester"
              required
              class="w-full pl-3 pr-8 py-2.5 border rounded-lg text-sm text-gray-800 bg-white focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 transition-all @error('semester') border-red-400 bg-red-50 @else border-gray-300 @enderror"
            >
              <option value="">-- Pilih Semester --</option>
              <option value="ganjil" {{ old('semester') === 'ganjil' ? 'selected' : '' }}>Ganjil</option>
              <option value="genap"  {{ old('semester') === 'genap'  ? 'selected' : '' }}>Genap</option>
            </select>
            @error('semester')
              <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
          </div>

          <!-- Tanggal Mulai -->
          <div>
            <label class="block text-xs font-medium text-gray-600 mb-1.5">
              Tanggal Mulai <span class="text-red-500">*</span>
            </label>
            <input
              type="date"
              name="mulai_at"
              value="{{ old('mulai_at') }}"
              onclick="this.showPicker && this.showPicker()"
              required
              class="w-full px-4 py-2.5 border rounded-lg text-sm text-gray-800 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 transition-all @error('mulai_at') border-red-400 bg-red-50 @else border-gray-300 @enderror"
            />
            @error('mulai_at')
              <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
          </div>

          <!-- Tanggal Selesai -->
          <div>
            <label class="block text-xs font-medium text-gray-600 mb-1.5">Tanggal Selesai</label>
            <input
              type="date"
              name="selesai_at"
              value="{{ old('selesai_at') }}"
              onclick="this.showPicker && this.showPicker()"
              class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-800 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 transition-all"
            />
            @error('selesai_at')
              <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
          </div>

        </div>

      </div>

      <!-- Opsi: Langsung Aktifkan -->
      <div>
        <label for="langsung_aktifkan" class="inline-flex items-center gap-2 cursor-pointer">
          <input
            type="checkbox"
            id="langsung_aktifkan"
            name="langsung_aktifkan"
            value="1"
            {{ old('langsung_aktifkan') ? 'checked' : '' }}
            class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer"
          />
          <span class="text-sm text-gray-700">Langsung aktifkan periode ini</span>
        </label>
      </div>

      <!-- Action Buttons -->
      <div class="flex items-center justify-end gap-3">
        <a href="{{ route('admin.periode.index') }}"
          class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-medium border border-gray-300 text-gray-600 hover:bg-gray-50 transition-all">
          <i class="fas fa-arrow-left text-xs"></i> Batal
        </a>
        <button type="submit"
          class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-semibold bg-blue-600 text-white hover:bg-blue-700 transition-all shadow-sm">
          <i class="fas fa-floppy-disk text-xs"></i> Simpan Periode
        </button>
      </div>

    </div>
  </form>

@endsection
