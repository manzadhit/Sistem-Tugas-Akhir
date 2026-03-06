@extends('layouts.app')

@section('title', 'Permintaan Penguji')

@section('sidebar')
  @include('kajur.sidebar')
@endsection

@section('content')
  {{-- Banner --}}
  <div class="relative h-40 overflow-hidden rounded-xl bg-gradient-to-br from-blue-800 to-blue-500 mb-8">
    <div class="absolute inset-0 flex flex-col items-center justify-center text-white text-center px-4">
      <h1 class="text-2xl font-bold mb-1">Permintaan Dosen Penguji</h1>
      <p class="text-sm opacity-80">Tinjau pengajuan mahasiswa dan lanjutkan proses verifikasi penguji.</p>
    </div>
  </div>

  <!-- Table -->
  <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
    <!-- Table Header -->
    <div class="flex flex-col gap-4 border-b border-slate-200 p-5 sm:flex-row sm:items-center sm:justify-between">
      <div>
        <h2 class="text-lg font-semibold text-slate-800">Daftar Mahasiswa</h2>
        <p class="mt-1 text-sm text-slate-500">Mahasiswa yang mengajukan permintaan dosen penguji tugas akhir</p>
      </div>
      <form action="{{ route('kajur.permintaan-penguji.index') }}" method="GET" class="flex items-center gap-3">
        <div class="relative">
          <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-sm text-slate-400"></i>
          <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari nama atau NIM mahasiswa..."
            class="rounded-lg border border-slate-200 py-2 pl-9 pr-4 text-sm text-slate-600 placeholder-slate-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
        </div>
        <button type="submit"
          class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700">
          Cari
        </button>
        @if (!empty($search))
          <a href="{{ route('kajur.permintaan-penguji.index') }}"
            class="inline-flex items-center rounded-lg border border-slate-200 px-4 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-50">
            Reset
          </a>
        @endif
      </form>
    </div>

    <!-- Table Content -->
    <div class="overflow-x-auto">
      <table class="w-full text-left text-sm">
        <thead>
          <tr class="border-b border-slate-200 bg-slate-50">
            <th class="whitespace-nowrap px-5 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">No</th>
            <th class="whitespace-nowrap px-5 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">
              Mahasiswa</th>
            <th class="whitespace-nowrap px-5 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">Judul
              Tugas Akhir</th>
            <th class="whitespace-nowrap px-5 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">Pembimbing</th>
            <th class="whitespace-nowrap px-5 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">Tanggal
              Pengajuan</th>
            <th class="whitespace-nowrap px-5 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">Status Laporan
            </th>
            <th class="whitespace-nowrap px-5 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">Aksi
            </th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-200">
          @forelse ($permintaanPenguji as $index => $permintaan)
            <tr class="hover:bg-slate-50">
              <td class="whitespace-nowrap px-5 py-4 text-slate-600">{{ $permintaanPenguji->firstItem() + $index }}</td>
              <td class="px-5 py-4">
                <div class="flex flex-col">
                  <span class="font-medium text-slate-800">{{ $permintaan->tugasAkhir->mahasiswa->nama_lengkap }}</span>
                  <span class="text-xs text-slate-500">{{ $permintaan->tugasAkhir->mahasiswa->nim }}</span>
                </div>
              </td>
              <td class="px-5 py-4">
                <div class="max-w-xs text-slate-600 leading-relaxed">
                  {{ $permintaan->tugasAkhir->judul }}
                </div>
              </td>
              <td class="px-5 py-4">
                <div class="max-w-xs text-slate-600 leading-relaxed">
                  @foreach ($permintaan->tugasAkhir->mahasiswa->dosenPembimbing as $pembimbing)
                  <span>{{ $loop->iteration }}. {{ $pembimbing->dosen->nama_lengkap  }}</span> <br>
                  @endforeach
                </div>
              </td>
              <td class="whitespace-nowrap px-5 py-4 text-slate-500">
                {{ $permintaan->created_at->translatedFormat('d M Y') }}</td>
              <td class="px-5 py-4">
                <span
                  class="inline-flex rounded-full {{ $permintaan->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }} px-2.5 py-0.5 text-xs font-medium">{{ $permintaan->status }}</span>
              </td> 
              <td class="px-5 py-4">
                <a href="{{ route('kajur.penetapan-penguji', ['permintaan' => $permintaan->id]) }}"
                  class="inline-flex items-center rounded-lg bg-blue-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-blue-700 transition">Tetapkan</a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="px-5 py-10 text-center">
                <div class="flex flex-col items-center gap-2 text-slate-500">
                  <i class="fas fa-inbox text-3xl text-slate-300"></i>
                  <p class="text-sm font-medium text-slate-600">
                    {{ !empty($search) ? 'Mahasiswa tidak ditemukan.' : 'Belum ada permintaan penguji.' }}
                  </p>
                  <p class="text-sm text-slate-400">
                    {{ !empty($search) ? 'Coba gunakan kata kunci nama atau NIM yang lain.' : 'Permintaan yang masuk akan tampil di tabel ini.' }}
                  </p>
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div class="flex flex-col items-center justify-between gap-3 border-t border-slate-200 px-5 py-4 sm:flex-row">
      {{ $permintaanPenguji->links() }}
    </div>
  </div>
@endsection
