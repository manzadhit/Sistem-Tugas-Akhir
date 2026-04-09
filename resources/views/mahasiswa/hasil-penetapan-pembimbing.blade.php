@extends('layouts.app')

@section('title', 'Hasil Penetapan Pembimbing')

@section('content')
  <div class="mx-auto max-w-2xl py-6">
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
        <div class="flex flex-col gap-4">
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
        <form method="POST" action="{{ route('mahasiswa.hasil-penetapan.konfirmasi') }}">
          @csrf
          <button type="submit"
            class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-100">
            <span>Lanjut ke Dashboard</span>
            <i class="fas fa-arrow-right"></i>
          </button>
        </form>
      </div>
    </div>
  </div>
@endsection
