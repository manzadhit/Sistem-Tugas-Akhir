@extends('layouts.app')

@section('title', 'Hasil Penetapan Pembimbing')

@section('content')
  <div class="mx-auto max-w-6xl space-y-4">
    <div class="relative overflow-hidden rounded-xl bg-gradient-to-br from-blue-600 to-blue-800">
      <div class="flex min-h-28 items-center justify-center px-5 py-5 text-center text-white">
        <div class="max-w-3xl">
          <h1 class="text-lg font-bold sm:text-xl md:text-2xl">Rekomendasi pembimbing tersedia</h1>
          <p class="mt-1 text-xs opacity-90 sm:text-sm">
            Pembimbing sudah ditetapkan. Konfirmasi untuk lanjut ke dashboard.
          </p>
        </div>
      </div>
    </div>

    <div class="rounded-xl bg-white p-4 shadow-sm">
      <div class="relative flex justify-between gap-4">
        <div class="absolute left-[20%] right-[20%] top-[18px] h-0.5 bg-emerald-500"></div>

        <div class="relative z-10 flex flex-1 flex-col items-center">
          <div class="mb-2 flex h-9 w-9 items-center justify-center rounded-full bg-emerald-500 text-sm text-white">
            <i class="fas fa-check"></i>
          </div>
          <span class="text-center text-[10px] font-semibold text-emerald-600 sm:text-xs">Pengajuan</span>
        </div>

        <div class="relative z-10 flex flex-1 flex-col items-center">
          <div class="mb-2 flex h-9 w-9 items-center justify-center rounded-full bg-emerald-500 text-sm text-white">
            <i class="fas fa-check"></i>
          </div>
          <span class="text-center text-[10px] font-semibold text-emerald-600 sm:text-xs">Rekomendasi</span>
        </div>
      </div>
    </div>

    <div>
      <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-100 px-6 py-5">
          <div class="flex items-center gap-3">
            <div class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 text-blue-600">
              <i class="fas fa-user-check text-base"></i>
            </div>
            <h1 class="text-lg font-bold text-slate-900 sm:text-xl">Pembimbing Telah Ditetapkan</h1>
          </div>
        </div>

        <div class="px-6 py-5">
          <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
            @foreach ($dosenPembimbing as $pembimbing)
              <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3.5">
                <div class="flex items-start justify-between gap-3">
                  <div class="min-w-0">
                    <p class="text-[10px] font-semibold uppercase tracking-[0.16em] text-slate-500">
                      {{ $pembimbing->getJenisPembimbing() }}
                    </p>
                    <p class="mt-0.5 text-sm font-semibold text-slate-900">
                      {{ $pembimbing->dosen->nama_lengkap ?? '-' }}
                    </p>
                  </div>
                  <span
                    class="inline-flex shrink-0 items-center gap-1 rounded-full bg-emerald-50 px-2 py-0.5 text-[10px] font-semibold text-emerald-700">
                    <i class="fas fa-check-circle text-[10px]"></i> Aktif
                  </span>
                </div>

                <div class="mt-3 grid grid-cols-1 gap-x-4 gap-y-1.5 text-[11px] text-slate-600 sm:grid-cols-2">
                  <div class="min-w-0">
                    <span class="font-semibold text-slate-500">NIDN:</span>
                    <span>{{ $pembimbing->dosen->nidn ?? '-' }}</span>
                  </div>
                  <div class="min-w-0">
                    <span class="font-semibold text-slate-500">Jurusan:</span>
                    <span>{{ $pembimbing->dosen->jurusan ?? '-' }}</span>
                  </div>
                  <div class="min-w-0">
                    <span class="font-semibold text-slate-500">Jabatan:</span>
                    <span>{{ $pembimbing->dosen->jabatan_fungsional ?? '-' }}</span>
                  </div>
                  @if ($pembimbing->dosen?->no_telp)
                    <div class="min-w-0">
                      <span class="font-semibold text-slate-500">No. Telp:</span>
                      <span>{{ $pembimbing->dosen->no_telp }}</span>
                    </div>
                  @endif
                </div>
              </div>
            @endforeach
          </div>
        </div>

        <div class="border-t border-slate-100 px-6 py-4">
          <form method="POST" action="{{ route('mahasiswa.hasil-penetapan.konfirmasi') }}" class="flex justify-end">
            @csrf
            <button type="submit"
              class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-100">
              <span>Lanjut ke Dashboard</span>
              <i class="fas fa-arrow-right"></i>
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
