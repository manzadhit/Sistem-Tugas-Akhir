@extends('layouts.app')

@section('title', 'Edit Periode - ' . $periode->tahun_ajaran . ' ' . ucfirst($periode->semester))

@section('sidebar')
  @include('admin.sidebar')
@endsection

@section('content')

  <!-- Breadcrumb -->
  <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
    <a href="{{ route('admin.periode.index') }}" class="hover:text-blue-600 transition-colors">Kelola Periode Akademik</a>
    <i class="fas fa-chevron-right text-xs text-gray-400"></i>
    <span class="text-gray-800 font-medium">Edit Periode</span>
  </div>

  <!-- Banner -->
  <div class="bg-gradient-to-br from-amber-600 to-amber-400 rounded-2xl p-5 sm:p-8 mb-6 text-white">
    <div class="flex items-center gap-4">
      <div class="w-14 h-14 rounded-2xl bg-white/20 border-2 border-white/30 flex items-center justify-center text-xl font-bold shrink-0">
        <i class="fas fa-calendar-pen"></i>
      </div>
      <div>
        <h1 class="text-xl sm:text-2xl font-bold mb-0.5">Edit Periode Akademik</h1>
        <p class="opacity-80 text-sm">{{ $periode->tahun_ajaran }} &mdash; Semester {{ ucfirst($periode->semester) }}</p>
      </div>
    </div>
  </div>

  <!-- Flash Messages -->
  <x-alert type="success" />
  <x-alert type="error" />
  <x-alert type="warning" />

  <form method="POST" action="{{ route('admin.periode.update', $periode->id) }}">
    @csrf
    @method('PUT')

    <div class="space-y-5">

      <!-- Form Fields -->
      <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-5 py-3.5 border-b border-gray-100 flex items-center gap-2">
          <i class="fas fa-calendar-alt text-amber-500 text-sm"></i>
          <h2 class="text-sm font-semibold text-gray-800">Data Periode Akademik</h2>
        </div>
        <div class="px-5 py-5 grid grid-cols-1 sm:grid-cols-2 gap-5">

          <!-- Tahun Ajaran — hanya bisa diubah jika draft -->
          <div>
            <label class="block text-xs font-medium text-gray-600 mb-1.5">
              Tahun Ajaran <span class="text-red-500">*</span>
            </label>
            <input
              type="text"
              name="tahun_ajaran"
              value="{{ old('tahun_ajaran', $periode->tahun_ajaran) }}"
              placeholder="Contoh: 2025/2026"
              @if ($periode->status !== 'draft') disabled @endif
              required
              class="w-full px-4 py-2.5 border rounded-lg text-sm text-gray-800 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 transition-all
                @error('tahun_ajaran') border-red-400 bg-red-50 @else border-gray-300 @enderror
                {{ $periode->status !== 'draft' ? 'bg-gray-100 text-gray-500 cursor-not-allowed' : '' }}"
            />
            @error('tahun_ajaran')
              <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
          </div>

          <!-- Semester — hanya bisa diubah jika draft -->
          <div>
            <label class="block text-xs font-medium text-gray-600 mb-1.5">
              Semester <span class="text-red-500">*</span>
            </label>
            <select
              name="semester"
              @if ($periode->status !== 'draft') disabled @endif
              required
              class="w-full pl-3 pr-8 py-2.5 border rounded-lg text-sm text-gray-800 bg-white focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 transition-all
                @error('semester') border-red-400 bg-red-50 @else border-gray-300 @enderror
                {{ $periode->status !== 'draft' ? 'bg-gray-100 text-gray-500 cursor-not-allowed' : '' }}"
            >
              <option value="">-- Pilih Semester --</option>
              <option value="ganjil" {{ old('semester', $periode->semester) === 'ganjil' ? 'selected' : '' }}>Ganjil</option>
              <option value="genap"  {{ old('semester', $periode->semester) === 'genap'  ? 'selected' : '' }}>Genap</option>
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
              value="{{ old('mulai_at', optional($periode->mulai_at)->format('Y-m-d')) }}"
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
              value="{{ old('selesai_at', optional($periode->selesai_at)->format('Y-m-d')) }}"
              onclick="this.showPicker && this.showPicker()"
              class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-800 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 transition-all"
            />
            @error('selesai_at')
              <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
          </div>

        </div>

        <!-- Info jika bukan draft -->
        @if ($periode->status !== 'draft')
          <div class="mx-5 mb-5 rounded-lg border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700">
            <i class="fas fa-info-circle mr-1.5 text-slate-500"></i>
            Periode berstatus <span class="font-semibold capitalize">{{ $periode->status }}</span> —
            hanya tanggal mulai dan tanggal selesai yang dapat diperbarui.
          </div>
        @endif
      </div>

      <!-- Action Buttons -->
      <div class="flex items-center justify-end gap-3">
        <a href="{{ route('admin.periode.index') }}"
          class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-medium border border-gray-300 text-gray-600 hover:bg-gray-50 transition-all">
          <i class="fas fa-arrow-left text-xs"></i> Batal
        </a>
        <button type="submit"
          class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-semibold bg-amber-500 text-white hover:bg-amber-600 transition-all shadow-sm">
          <i class="fas fa-save text-xs"></i> Simpan Perubahan
        </button>
      </div>

    </div>
  </form>

@endsection
