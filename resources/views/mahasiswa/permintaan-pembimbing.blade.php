@extends('layouts.app')

@section('title', 'Menunggu Penetapan Pembimbing')

@section('content')
  <div class="mx-auto max-w-2xl py-10">
    @if (session('success'))
      <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
        {{ session('success') }}
      </div>
    @endif

    <div class="rounded-2xl border border-slate-200 bg-white p-8 text-center shadow-sm">
      <div class="mx-auto mb-4 h-14 w-14 animate-spin rounded-full border-4 border-blue-200 border-t-blue-700"></div>

      <h1 class="text-lg sm:text-xl font-bold text-slate-900">Menunggu Penetapan Pembimbing</h1>
      <p class="mt-2 text-xs sm:text-sm text-slate-600">
        Permintaan pembimbing Anda sudah diterima. Sistem sedang menunggu penetapan pembimbing oleh ketua jurusan.
      </p>
      <p class="mt-1 text-[10px] sm:text-xs text-slate-500">
        Halaman ini akan mengecek status secara berkala.
      </p>

      <div class="mt-6">
        <a href="{{ route('mahasiswa.permintaan-pembimbing.create') }}"
          class="inline-flex items-center rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">
          Cek Sekarang
        </a>
      </div>
    </div>
  </div>

  <script>
    setTimeout(() => {
      window.location.reload();
    }, 10000);
  </script>
@endsection
