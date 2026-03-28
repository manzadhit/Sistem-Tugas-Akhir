@extends('layouts.app')

@section('title', 'Tambah Dosen')

@section('sidebar')
  @include('admin.sidebar')
@endsection

@section('content')

  {{-- Breadcrumb --}}
  <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
    <a href="{{ route('admin.dosen.index') }}" class="hover:text-blue-600 transition-colors">Kelola Dosen</a>
    <i class="fas fa-chevron-right text-xs text-gray-400"></i>
    <span class="text-gray-800 font-medium">Tambah Dosen</span>
  </div>

  {{-- Banner --}}
  <div class="bg-gradient-to-br from-emerald-700 to-teal-500 rounded-2xl p-5 sm:p-8 mb-6 text-white">
    <h1 class="text-xl sm:text-2xl font-bold mb-1">
      <i class="fas fa-chalkboard-teacher mr-2"></i>Tambah Dosen
    </h1>
    <p class="opacity-90 text-sm">Isi data profil dosen — akun login akan dibuat otomatis berdasarkan NIDN</p>
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

  <form method="POST" action="{{ route('admin.dosen.store') }}" enctype="multipart/form-data">
    @csrf

    <div class="space-y-5">

      {{-- Info akun otomatis --}}
      <div class="flex items-start gap-3 bg-teal-50 border border-teal-200 rounded-xl px-5 py-4">
        <i class="fas fa-circle-info text-teal-500 mt-0.5"></i>
        <div class="text-sm text-teal-800">
          <p class="font-semibold mb-0.5">Akun login dibuat otomatis</p>
          <p class="text-xs text-teal-600">Username: <strong>NIDN dosen</strong> · Password default: <strong>NIDN
              dosen</strong> · Dosen wajib ganti password setelah login pertama.</p>
        </div>
      </div>

      {{-- ██ Data Pribadi --}}
      <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-5 py-3.5 border-b border-gray-100 flex items-center gap-2">
          <i class="fas fa-id-card text-teal-500 text-sm"></i>
          <h2 class="text-sm font-semibold text-gray-800">Data Pribadi</h2>
        </div>
        <div class="px-5 py-5 grid grid-cols-1 sm:grid-cols-2 gap-5">

          {{-- Nama Lengkap --}}
          <div class="sm:col-span-2">
            <label class="block text-xs font-medium text-gray-600 mb-1.5">
              Nama Lengkap <span class="text-red-500">*</span>
            </label>
            <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required
              placeholder="Nama sesuai SK / ijazah"
              class="w-full px-4 py-2.5 border rounded-lg text-sm text-gray-800 focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 transition-all @error('nama_lengkap') border-red-400 bg-red-50 @else border-gray-300 @enderror" />
            @error('nama_lengkap')
              <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
          </div>

          {{-- NIDN --}}
          <div>
            <label class="block text-xs font-medium text-gray-600 mb-1.5">
              NIDN <span class="text-red-500">*</span>
            </label>
            <input type="text" name="nidn" value="{{ old('nidn') }}" required
              placeholder="Nomor Induk Dosen Nasional"
              class="w-full px-4 py-2.5 border rounded-lg text-sm text-gray-800 focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 transition-all @error('nidn') border-red-400 bg-red-50 @else border-gray-300 @enderror" />
            @error('nidn')
              <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
          </div>

          {{-- No. Telepon --}}
          <div>
            <label class="block text-xs font-medium text-gray-600 mb-1.5">No. Telepon</label>
            <div class="relative">
              <i class="fas fa-phone absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
              <input type="text" name="no_telp" value="{{ old('no_telp') }}" placeholder="08xxxxxxxxxx"
                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-800 focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 transition-all @error('no_telp') border-red-400 bg-red-50 @else border-gray-300 @enderror" />
            </div>
            @error('no_telp')
              <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
          </div>

        </div>
      </div>

      {{-- ██ Data Akademik --}}
      <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-5 py-3.5 border-b border-gray-100 flex items-center gap-2">
          <i class="fas fa-graduation-cap text-teal-500 text-sm"></i>
          <h2 class="text-sm font-semibold text-gray-800">Data Akademik</h2>
        </div>
        <div class="px-5 py-5 grid grid-cols-1 sm:grid-cols-2 gap-5">

          {{-- Jurusan --}}
          <div>
            <label class="block text-xs font-medium text-gray-600 mb-1.5">
              Jurusan <span class="text-red-500">*</span>
            </label>
            <input type="text" name="jurusan" value="{{ old('jurusan', 'Teknik Informatika') }}" required
              placeholder="Teknik Informatika"
              class="w-full px-4 py-2.5 border rounded-lg text-sm text-gray-800 focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 transition-all @error('jurusan') border-red-400 bg-red-50 @else border-gray-300 @enderror" />
            @error('jurusan')
              <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
          </div>

          {{-- Program Studi --}}
          <div>
            <label class="block text-xs font-medium text-gray-600 mb-1.5">
              Program Studi <span class="text-red-500">*</span>
            </label>
            <input type="text" name="program_studi" value="{{ old('program_studi', 'S1 Teknik Informatika') }}"
              required placeholder="S1 Teknik Informatika"
              class="w-full px-4 py-2.5 border rounded-lg text-sm text-gray-800 focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 transition-all @error('program_studi') border-red-400 bg-red-50 @else border-gray-300 @enderror" />
            @error('program_studi')
              <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
          </div>

          {{-- Keahlian --}}
          <div class="sm:col-span-2">
            <label class="block text-xs font-medium text-gray-600 mb-1.5">
              Keahlian / Bidang Riset <span class="text-red-500">*</span>
            </label>
            <input type="text" name="keahlian" value="{{ old('keahlian') }}" required
              placeholder="cth. Kecerdasan Buatan, Jaringan Komputer, Rekayasa Perangkat Lunak"
              class="w-full px-4 py-2.5 border rounded-lg text-sm text-gray-800 focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 transition-all @error('keahlian') border-red-400 bg-red-50 @else border-gray-300 @enderror" />
            @error('keahlian')
              <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
          </div>

          <div class="sm:col-span-2">
            <label class="block text-xs font-medium text-gray-600 mb-1.5">
              Mata Kuliah yang Diampu
            </label>
            <x-multi-select name="mata_kuliah_ids" :options="$mataKuliahOptions" :selected="old('mata_kuliah_ids', [])"
              placeholder="Pilih mata kuliah yang diampu..." search-placeholder="Cari mata kuliah..."
              empty-text="Mata kuliah tidak ditemukan." />
            <p class="mt-2 text-xs text-slate-500">Bisa pilih lebih dari satu mata kuliah.</p>
            @error('mata_kuliah_ids')
              <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
            @enderror
            @error('mata_kuliah_ids.*')
              <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
            @enderror
          </div>

          {{-- Jabatan Fungsional --}}
          <div>
            <label class="block text-xs font-medium text-gray-600 mb-1.5">
              Jabatan Fungsional <span class="text-red-500">*</span>
            </label>
            <select name="jabatan_fungsional" required
              class="w-full pl-3 pr-8 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-800 bg-white focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 transition-all @error('jabatan_fungsional') border-red-400 bg-red-50 @enderror">
              <option value="" disabled {{ old('jabatan_fungsional') ? '' : 'selected' }}>-- Pilih jabatan --
              </option>
              @foreach (['Asisten Ahli', 'Lektor', 'Lektor Kepala', 'Guru Besar', 'Tenaga Pendidik'] as $jabatan)
                <option value="{{ $jabatan }}" {{ old('jabatan_fungsional') === $jabatan ? 'selected' : '' }}>
                  {{ $jabatan }}</option>
              @endforeach
            </select>
            @error('jabatan_fungsional')
              <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
          </div>

          {{-- Status --}}
          <div>
            <label class="block text-xs font-medium text-gray-600 mb-1.5">
              Status <span class="text-red-500">*</span>
            </label>
            <select name="status" required
              class="w-full pl-3 pr-8 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-800 bg-white focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/10 transition-all @error('status') border-red-400 bg-red-50 @enderror">
              @foreach (['aktif' => 'Aktif', 'cuti' => 'Cuti', 'nonaktif' => 'Non-aktif', 'pensiun' => 'Pensiun'] as $val => $lbl)
                <option value="{{ $val }}" {{ old('status', 'aktif') === $val ? 'selected' : '' }}>
                  {{ $lbl }}</option>
              @endforeach
            </select>
            @error('status')
              <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
          </div>

        </div>
      </div>

      {{-- ██ Action Buttons --}}
      <div class="flex items-center justify-end gap-3">
        <a href="{{ route('admin.dosen.index') }}"
          class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-medium border border-gray-300 text-gray-600 hover:bg-gray-50 transition-all">
          <i class="fas fa-arrow-left text-xs"></i> Batal
        </a>
        <button type="submit"
          class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-semibold bg-teal-600 text-white hover:bg-teal-700 transition-all shadow-sm">
          <i class="fas fa-chalkboard-teacher text-xs"></i> Simpan Dosen
        </button>
      </div>

    </div>
  </form>

@endsection
