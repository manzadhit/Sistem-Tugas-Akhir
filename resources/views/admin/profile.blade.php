@extends('layouts.app')

@section('title', 'Profil Admin')

@section('sidebar')
  @include('admin.sidebar')
@endsection

@section('content')
  <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
    <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600 transition-colors">Dashboard</a>
    <i class="fas fa-chevron-right text-xs text-gray-400"></i>
    <span class="text-gray-800 font-medium">Profil Saya</span>
  </div>

  <div class="bg-gradient-to-br from-slate-800 via-slate-700 to-blue-700 rounded-2xl p-5 sm:p-8 mb-6 text-white">
    <div class="flex items-start gap-4">
      <div
        class="w-14 h-14 rounded-2xl bg-white/15 border border-white/20 flex items-center justify-center text-xl shrink-0">
        <i class="fas fa-user-shield"></i>
      </div>
      <div>
        <h1 class="text-xl sm:text-2xl font-bold mb-1">Profil Admin</h1>
        <p class="text-sm text-white/80">Kelola email login dan keamanan akun admin dari satu halaman.</p>
      </div>
    </div>
  </div>

  <x-alert type="success" />
  <x-alert type="error" />
  <x-alert type="warning" />

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

  <form method="POST" action="{{ route('admin.profile.update') }}">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
      <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
          <div class="px-5 py-3.5 border-b border-gray-100 flex items-center gap-2">
            <i class="fas fa-circle-info text-blue-500 text-sm"></i>
            <h2 class="text-sm font-semibold text-gray-800">Ringkasan Akun</h2>
          </div>
          <div class="px-5 py-5 space-y-4">
            <div>
              <p class="text-xs font-medium uppercase tracking-wide text-gray-400">Username</p>
              <p class="mt-1 text-sm font-semibold text-gray-800">{{ $user->username }}</p>
            </div>
            <div>
              <p class="text-xs font-medium uppercase tracking-wide text-gray-400">Role</p>
              <p class="mt-1 text-sm font-semibold text-gray-800">Administrator</p>
            </div>
            <div>
              <p class="text-xs font-medium uppercase tracking-wide text-gray-400">Email Aktif</p>
              <p class="mt-1 text-sm font-semibold text-gray-800 break-words">{{ $user->email }}</p>
            </div>
          </div>
        </div>

        <div class="bg-amber-50 border border-amber-200 rounded-xl px-5 py-4">
          <div class="flex items-start gap-3">
            <div class="w-9 h-9 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center shrink-0">
              <i class="fas fa-shield-halved text-sm"></i>
            </div>
            <div>
              <p class="text-sm font-semibold text-amber-900">Catatan Keamanan</p>
              <p class="mt-1 text-sm leading-relaxed text-amber-800">
                Isi password saat ini ketika mengganti email atau password agar perubahan akun admin tetap terverifikasi.
              </p>
            </div>
          </div>
        </div>
      </div>

      <div class="xl:col-span-2 space-y-6">
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
          <div class="px-5 py-3.5 border-b border-gray-100 flex items-center gap-2">
            <i class="fas fa-envelope text-blue-500 text-sm"></i>
            <h2 class="text-sm font-semibold text-gray-800">Informasi Login</h2>
          </div>
          <div class="px-5 py-5 grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
              <label class="block text-xs font-medium text-gray-600 mb-1.5">Username</label>
              <div class="relative">
                <input type="text" value="{{ $user->username }}" disabled
                  class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm text-gray-400 bg-gray-50 cursor-not-allowed" />
                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-400">
                  <i class="fas fa-lock"></i>
                </span>
              </div>
              <p class="text-xs text-gray-400 mt-1">Username admin tetap dan tidak dapat diubah.</p>
            </div>

            <div>
              <label class="block text-xs font-medium text-gray-600 mb-1.5">
                Email <span class="text-red-500">*</span>
              </label>
              <input type="email" name="email" value="{{ old('email', $user->email) }}" autocomplete="email"
                class="w-full px-4 py-2.5 border rounded-lg text-sm text-gray-800 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 transition-all @error('email') border-red-400 bg-red-50 @else border-gray-300 @enderror" />
              @error('email')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
              @enderror
            </div>
          </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
          <div class="px-5 py-3.5 border-b border-gray-100 flex items-center gap-2">
            <i class="fas fa-lock text-blue-500 text-sm"></i>
            <h2 class="text-sm font-semibold text-gray-800">Keamanan Akun</h2>
          </div>
          <div class="px-5 py-5 grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div class="sm:col-span-2">
              <label class="block text-xs font-medium text-gray-600 mb-1.5">
                Password Saat Ini
              </label>
              <x-password-input name="current_password" autocomplete="current-password"
                placeholder="Wajib diisi saat mengganti email atau password"
                class="w-full px-4 py-2.5 border rounded-lg text-sm text-gray-800 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 transition-all @error('current_password') border-red-400 bg-red-50 @else border-gray-300 @enderror" />
              @error('current_password')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
              @enderror
            </div>

            <div>
              <label class="block text-xs font-medium text-gray-600 mb-1.5">Password Baru</label>
              <x-password-input name="password" autocomplete="new-password"
                placeholder="Kosongkan jika tidak ingin diubah"
                class="w-full px-4 py-2.5 border rounded-lg text-sm text-gray-800 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 transition-all @error('password') border-red-400 bg-red-50 @else border-gray-300 @enderror" />
              @error('password')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
              @enderror
            </div>

            <div>
              <label class="block text-xs font-medium text-gray-600 mb-1.5">Konfirmasi Password Baru</label>
              <x-password-input name="password_confirmation" autocomplete="new-password"
                placeholder="Ulangi password baru"
                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-800 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 transition-all" />
            </div>
          </div>
        </div>

        <div class="flex justify-end">
          <button type="submit"
            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-semibold bg-blue-600 text-white hover:bg-blue-700 transition-all shadow-sm">
            <i class="fas fa-save text-xs"></i> Simpan Perubahan
          </button>
        </div>
      </div>
    </div>
  </form>
@endsection
