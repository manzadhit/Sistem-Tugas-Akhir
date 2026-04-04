@extends('layouts.app')

@section('title', 'Profil Saya')

@section('sidebar')
  @include('mahasiswa.sidebar')
@endsection

@section('content')
  {{-- Header Banner --}}
  <div class="relative h-40 overflow-hidden rounded-xl bg-gradient-to-br from-blue-600 to-blue-800 mb-8">
    <div class="absolute inset-0 flex flex-col items-center justify-center text-white text-center px-4">
      <h1 class="text-2xl font-bold mb-1">Profil Saya</h1>
      <p class="text-sm opacity-90">Kelola informasi pribadi dan keamanan akun</p>
    </div>
  </div>

  @if (session('success'))
    <div
      class="mb-6 flex items-center gap-3 rounded-xl border border-green-200 bg-green-50 px-5 py-4 text-green-800 shadow-sm">
      <i class="fas fa-check-circle text-green-500 text-lg"></i>
      <span class="text-sm font-medium">{{ session('success') }}</span>
    </div>
  @endif

  <form action="{{ route('mahasiswa.profile.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

      {{-- Kolom Kiri: Foto Profil --}}
      <div class="lg:col-span-1">
        <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
          <div class="border-b border-slate-200 px-6 py-4">
            <h3 class="flex items-center gap-2 font-semibold text-slate-900">
              <i class="fas fa-camera text-blue-600"></i> Foto Profil
            </h3>
          </div>
          <div class="p-6 flex flex-col items-center gap-4">
            {{-- Preview foto --}}
            <div class="relative">
              <img id="foto-preview"
                src="{{ $profile->foto ? Storage::url($profile->foto) : 'https://ui-avatars.com/api/?name=' . urlencode($profile->nama_lengkap) . '&background=2563eb&color=ffffff&size=128' }}"
                alt="Foto Profil" class="w-32 h-32 rounded-full object-cover border-4 border-blue-100 shadow">
              <label for="foto"
                class="absolute bottom-0 right-0 bg-blue-600 text-white rounded-full w-8 h-8 flex items-center justify-center cursor-pointer shadow hover:bg-blue-700 transition">
                <i class="fas fa-pen text-xs"></i>
              </label>
            </div>

            <input type="file" id="foto" name="foto" accept="image/*" class="hidden"
              onchange="previewFoto(this)">

            <p class="text-xs text-slate-500 text-center">Format: JPG, PNG, WEBP<br>Maks. 2MB</p>

            <p id="error-foto" class="text-xs text-red-600 hidden"></p>

            @error('foto')
              <p class="text-xs text-red-600">{{ $message }}</p>
            @enderror
          </div>
        </div>
      </div>

      {{-- Kolom Kanan: Data Profil + Keamanan --}}
      <div class="lg:col-span-2 space-y-6">

        {{-- Data Pribadi --}}
        <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
          <div class="border-b border-slate-200 px-6 py-4">
            <h3 class="flex items-center gap-2 font-semibold text-slate-900">
              <i class="fas fa-user text-blue-600"></i> Data Pribadi
            </h3>
          </div>
          <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Nama Lengkap <span
                  class="text-red-500">*</span></label>
              <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $profile->nama_lengkap) }}"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nama_lengkap') border-red-500 @enderror">
              @error('nama_lengkap')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
              @enderror
            </div>

            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">NIM <span class="text-red-500">*</span></label>
              <input type="text" name="nim" value="{{ old('nim', $profile->nim) }}"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nim') border-red-500 @enderror">
              @error('nim')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
              @enderror
            </div>

            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Jurusan <span
                  class="text-red-500">*</span></label>
              <input type="text" name="jurusan" value="{{ old('jurusan', $profile->jurusan) }}"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('jurusan') border-red-500 @enderror">
              @error('jurusan')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
              @enderror
            </div>

            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Angkatan <span
                  class="text-red-500">*</span></label>
              <input type="number" name="angkatan" value="{{ old('angkatan', $profile->angkatan) }}"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('angkatan') border-red-500 @enderror">
              @error('angkatan')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
              @enderror
            </div>

            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">IPK</label>
              <input type="number" name="ipk" value="{{ old('ipk', $profile->ipk) }}" step="0.01" min="0"
                max="4"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('ipk') border-red-500 @enderror">
              @error('ipk')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
              @enderror
            </div>

            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">No. Telepon</label>
              <input type="text" name="no_telp" value="{{ old('no_telp', $profile->no_telp) }}"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('no_telp') border-red-500 @enderror">
              @error('no_telp')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
              @enderror
            </div>
          </div>
        </div>

        {{-- Keamanan Akun --}}
        <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
          <div class="border-b border-slate-200 px-6 py-4">
            <h3 class="flex items-center gap-2 font-semibold text-slate-900">
              <i class="fas fa-lock text-blue-600"></i> Keamanan Akun
            </h3>
          </div>
          <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="sm:col-span-2">
              <label class="block text-sm font-medium text-slate-700 mb-1">Email <span
                  class="text-red-500">*</span></label>
              <input type="email" name="email" value="{{ old('email', $user->email) }}"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror">
              @error('email')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
              @enderror
            </div>

            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Password Baru</label>
              <input type="password" name="password" autocomplete="new-password"
                placeholder="Kosongkan jika tidak diubah"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border-red-500 @enderror">
              @error('password')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
              @enderror
            </div>

            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1">Konfirmasi Password</label>
              <input type="password" name="password_confirmation" autocomplete="new-password"
                placeholder="Ulangi password baru"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
          </div>
        </div>

        {{-- Tombol Simpan --}}
        <div class="flex justify-end">
          <button type="submit"
            class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-semibold text-white shadow hover:bg-blue-700 transition focus:outline-none focus:ring-2 focus:ring-blue-500">
            <i class="fas fa-save"></i> Simpan Perubahan
          </button>
        </div>

      </div>
    </div>
  </form>

  <script>
    function previewFoto(input) {
      const error = document.getElementById('error-foto');
      error.classList.add('hidden');
      if (input.files && input.files[0]) {
        const file = input.files[0];
        if (file.size > 2 * 1024 * 1024) {
          error.textContent = `Ukuran file ${file.name} melebihi 2MB.`;
          error.classList.remove('hidden');
          input.value = '';
          return;
        }
        const reader = new FileReader();
        reader.onload = function(e) {
          document.getElementById('foto-preview').src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
      }
    }
  </script>
@endsection
