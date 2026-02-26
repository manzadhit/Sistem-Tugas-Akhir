@extends('layouts.app')

@section('title', 'Tambah Mahasiswa')

@section('sidebar')
  @include('admin.sidebar')
@endsection

@section('content')

  {{-- Breadcrumb --}}
  <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
    <a href="{{ route('admin.mahasiswa.index') }}" class="hover:text-blue-600 transition-colors">Kelola
      Mahasiswa</a>
    <i class="fas fa-chevron-right text-xs text-gray-400"></i>
    <span class="text-gray-800 font-medium">Tambah Mahasiswa</span>
  </div>

  {{-- Banner --}}
  <div class="bg-gradient-to-br from-blue-800 to-blue-500 rounded-2xl p-5 sm:p-8 mb-6 text-white">
    <h1 class="text-xl sm:text-2xl font-bold mb-1">
      <i class="fas fa-user-plus mr-2"></i>Tambah Mahasiswa
    </h1>
    <p class="opacity-90 text-sm">Isi data profil mahasiswa — akun login akan dibuat otomatis</p>
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

  <form method="POST" action="{{ route('admin.mahasiswa.store') }}" enctype="multipart/form-data">
    @csrf

    <div class="space-y-5">


      {{-- Info akun otomatis --}}
      <div class="flex items-start gap-3 bg-blue-50 border border-blue-200 rounded-xl px-5 py-4">
        <i class="fas fa-circle-info text-blue-500 mt-0.5"></i>
        <div class="text-sm text-blue-800">
          <p class="font-semibold mb-0.5">Akun login dibuat otomatis</p>
          <p class="text-xs text-blue-600">Username: <strong>NIM mahasiswa</strong> · Password default: <strong>NIM
              mahasiswa</strong> · Mahasiswa wajib ganti password setelah login pertama.</p>
        </div>
      </div>


      <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-5 py-3.5 border-b border-gray-100 flex items-center gap-2">
          <i class="fas fa-id-card text-purple-500 text-sm"></i>
          <h2 class="text-sm font-semibold text-gray-800">Data Pribadi</h2>
        </div>
        <div class="px-5 py-5 grid grid-cols-1 sm:grid-cols-2 gap-5">

          {{-- Nama Lengkap --}}
          <div class="sm:col-span-2">
            <label class="block text-xs font-medium text-gray-600 mb-1.5">
              Nama Lengkap <span class="text-red-500">*</span>
            </label>
            <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required
              placeholder="Nama sesuai KTP"
              class="w-full px-4 py-2.5 border rounded-lg text-sm text-gray-800 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 transition-all @error('nama_lengkap') border-red-400 bg-red-50 @else border-gray-300 @enderror" />
            @error('nama_lengkap')
              <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
          </div>

          {{-- NIM --}}
          <div>
            <label class="block text-xs font-medium text-gray-600 mb-1.5">
              NIM <span class="text-red-500">*</span>
            </label>
            <input type="text" name="nim" value="{{ old('nim') }}" required placeholder="Nomor Induk Mahasiswa"
              class="w-full px-4 py-2.5 border rounded-lg text-sm text-gray-800 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 transition-all @error('nim') border-red-400 bg-red-50 @else border-gray-300 @enderror" />
            @error('nim')
              <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
          </div>

          {{-- Angkatan --}}
          <div>
            <label class="block text-xs font-medium text-gray-600 mb-1.5">
              Angkatan <span class="text-red-500">*</span>
            </label>
            <input type="number" name="angkatan" value="{{ old('angkatan', date('Y')) }}" required min="2000"
              max="{{ date('Y') }}" placeholder="2022"
              class="w-full px-4 py-2.5 border rounded-lg text-sm text-gray-800 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 transition-all @error('angkatan') border-red-400 bg-red-50 @else border-gray-300 @enderror" />
            @error('angkatan')
              <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
          </div>

          {{-- Jurusan --}}
          <div>
            <label class="block text-xs font-medium text-gray-600 mb-1.5">
              Jurusan <span class="text-red-500">*</span>
            </label>
            <input type="text" name="jurusan" value="{{ old('jurusan', 'Teknik Informatika') }}" required
              placeholder="Teknik Informatika"
              class="w-full px-4 py-2.5 border rounded-lg text-sm text-gray-800 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 transition-all @error('jurusan') border-red-400 bg-red-50 @else border-gray-300 @enderror" />
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
              class="w-full px-4 py-2.5 border rounded-lg text-sm text-gray-800 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 transition-all @error('program_studi') border-red-400 bg-red-50 @else border-gray-300 @enderror" />
            @error('program_studi')
              <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
          </div>

          {{-- IPK --}}
          <div>
            <label class="block text-xs font-medium text-gray-600 mb-1.5">
              IPK <span class="text-red-500">*</span>
            </label>
            <input type="number" name="ipk" value="{{ old('ipk', '0.00') }}" required step="0.01" min="0"
              max="4.00" placeholder="0.00 – 4.00"
              class="w-full px-4 py-2.5 border rounded-lg text-sm text-gray-800 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 transition-all @error('ipk') border-red-400 bg-red-50 @else border-gray-300 @enderror" />
            @error('ipk')
              <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
          </div>

          {{-- No. Telp --}}
          <div>
            <label class="block text-xs font-medium text-gray-600 mb-1.5">No. Telepon</label>
            <div class="relative">
              <i class="fas fa-phone absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
              <input type="text" name="no_telp" value="{{ old('no_telp') }}" placeholder="08xxxxxxxxxx"
                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-800 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 transition-all" />
            </div>
          </div>

          {{-- Status Akademik --}}
          <div>
            <label class="block text-xs font-medium text-gray-600 mb-1.5">
              Status Akademik <span class="text-red-500">*</span>
            </label>
            <select name="status_akademik" required
              class="w-full pl-3 pr-8 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-800 bg-white focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 transition-all">
              @foreach (['aktif' => 'Aktif', 'cuti' => 'Cuti', 'nonaktif' => 'Non-aktif', 'lulus' => 'Lulus', 'dropout' => 'Dropout'] as $val => $lbl)
                <option value="{{ $val }}" {{ old('status_akademik', 'aktif') === $val ? 'selected' : '' }}>
                  {{ $lbl }}</option>
              @endforeach
            </select>
            @error('status_akademik')
              <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
          </div>

        </div>
      </div>

      {{-- ██ Action Buttons --}}
      <div class="flex items-center justify-end gap-3">
        <a href="{{ route('admin.mahasiswa.index') }}"
          class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-medium border border-gray-300 text-gray-600 hover:bg-gray-50 transition-all">
          <i class="fas fa-arrow-left text-xs"></i> Batal
        </a>
        <button type="submit"
          class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-semibold bg-blue-600 text-white hover:bg-blue-700 transition-all shadow-sm">
          <i class="fas fa-user-plus text-xs"></i> Simpan Mahasiswa
        </button>
      </div>

    </div>
  </form>

@endsection
