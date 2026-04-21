@extends('layouts.app')

@section('title', 'Ujian')

@section('sidebar')
  @include('admin.sidebar')
@endsection

@section('content')
  {{-- Page Header --}}
  <div class="mb-8">
    <h1 class="text-2xl font-bold text-slate-900">Dashboard Admin</h1>
    <p class="text-slate-500 mt-1">Selamat datang kembali! Berikut ringkasan data Jurusan Teknik Informatika.</p>
  </div>

  {{-- Stats --}}
  <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">
    <div
      class="flex items-center gap-4 rounded-xl border border-slate-200 bg-white p-5 shadow-sm hover:-translate-y-0.5 transition">
      <div
        class="flex h-14 w-14 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 text-xl text-white">
        <i class="fas fa-file-alt"></i>
      </div>
      <div>
        <p class="text-sm text-slate-500">Mahasiswa Ajukan Ujian</p>
        <p class="text-2xl font-bold text-slate-900">{{ $totalPengajuan ?? 0 }}</p>
      </div>
    </div>
    <div
      class="flex items-center gap-4 rounded-xl border border-slate-200 bg-white p-5 shadow-sm hover:-translate-y-0.5 transition">
      <div
        class="flex h-14 w-14 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 text-xl text-white">
        <i class="fas fa-check-circle"></i>
      </div>
      <div>
        <p class="text-sm text-slate-500">Sudah Verifikasi</p>
        <p class="text-2xl font-bold text-slate-900">{{ $totalTerverifikasi ?? 0 }}</p>
      </div>
    </div>
  </div>

  {{-- Table Container --}}
  <div class="overflow-hidden bg-white shadow-sm rounded-xl">
    {{-- Table Header --}}
    <div
      class="flex flex-col flex-wrap items-start justify-between gap-4 p-6 border-b border-gray-200 md:flex-row md:items-center">
      <div class="flex-1">
        <h2 class="mb-2 text-xl font-semibold text-gray-900">Daftar Pengajuan Ujian</h2>
        <p class="text-sm text-gray-500">Mahasiswa yang mengajukan verifikasi syarat ujian</p>
      </div>
      <form action="{{ route('admin.ujian.syarat.index') }}" method="GET" id="filterForm"
        class="flex w-full flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-center md:w-auto">
        <div class="relative w-full sm:w-auto">
          <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
          <input type="text" placeholder="Cari mahasiswa/NIM..." name="search" value="{{ request('search') }}"
            class="w-full rounded-lg border border-gray-300 py-2 pl-10 pr-4 text-sm transition-colors focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500/10 sm:w-56">
        </div>
        <div class="relative w-full sm:w-auto">
          <select name="jenis" onchange="this.form.submit()"
            class="w-full cursor-pointer appearance-none rounded-lg border border-gray-300 bg-white py-2 pl-4 pr-9 text-sm focus:border-blue-600 focus:outline-none sm:w-auto">
            <option value="">Semua Jenis</option>
            <option value="proposal" {{ request('jenis') === 'proposal' ? 'selected' : '' }}>Proposal</option>
            <option value="hasil" {{ request('jenis') === 'hasil' ? 'selected' : '' }}>Hasil</option>
            <option value="skripsi" {{ request('jenis') === 'skripsi' ? 'selected' : '' }}>Skripsi</option>
          </select>
          <i
            class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
        </div>
        @if (request()->hasAny(['search', 'jenis']))
          <a href="{{ route('admin.ujian.syarat.index') }}"
            class="px-3 py-2 text-sm text-gray-500 hover:text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
            <i class="fas fa-times"></i>
          </a>
        @endif
      </form>
    </div>

    @if ($ujians->isNotEmpty())
      {{-- Mobile / Tablet Cards --}}
      <div class="grid gap-4 p-4 lg:hidden sm:grid-cols-2">
        @foreach ($ujians as $item)
          <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
            <div class="flex items-start justify-between gap-3">
              <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-400">
                  #{{ ($ujians->currentPage() - 1) * $ujians->perPage() + $loop->iteration }}
                </p>
                <h3 class="mt-1 text-sm font-semibold text-gray-900">{{ $item->tugasAkhir->mahasiswa->nama_lengkap }}</h3>
                <p class="text-xs text-gray-500">{{ $item->tugasAkhir->mahasiswa->nim }}</p>
              </div>
              <span
                class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-800">{{ ucfirst($item->jenis_ujian) }}</span>
            </div>

            <div class="mt-4 space-y-3 text-sm text-gray-600">
              <div class="flex items-center justify-between gap-3">
                <span class="text-xs font-medium uppercase tracking-wide text-gray-400">Berkas</span>
                <span class="text-right font-medium text-gray-700">{{ $item->dokumenUjian->count() }} berkas</span>
              </div>
              <div class="flex items-center justify-between gap-3">
                <span class="text-xs font-medium uppercase tracking-wide text-gray-400">Pengajuan</span>
                <span class="text-right text-xs text-gray-500">{{ $item->created_at->format('d M Y') }}</span>
              </div>
              <div class="flex items-center justify-between gap-3">
                <span class="text-xs font-medium uppercase tracking-wide text-gray-400">Status</span>
                <span
                  class="inline-flex rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-800">{{ ucwords(str_replace('_', ' ', $item->status)) }}</span>
              </div>
            </div>

            <div class="mt-5">
              @if ($item->status === 'menunggu_undangan')
                <a class="inline-flex w-full items-center justify-center rounded-lg bg-gradient-to-br from-emerald-500 to-emerald-600 px-4 py-2 text-xs font-medium text-white transition hover:-translate-y-px hover:shadow-md hover:shadow-emerald-500/30"
                  href="{{ route('admin.ujian.syarat.undangan', $item->id) }}">Buat Undangan</a>
              @else
                <a class="inline-flex w-full items-center justify-center rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 px-4 py-2 text-xs font-medium text-white transition hover:-translate-y-px hover:shadow-md hover:shadow-blue-500/30"
                  href="{{ route('admin.ujian.syarat.detail', $item->id) }}">Verifikasi Berkas</a>
              @endif
            </div>
          </div>
        @endforeach
      </div>

      {{-- Desktop Table --}}
      <div class="hidden overflow-x-auto lg:block">
        <table class="w-full min-w-[920px] border-collapse text-left">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-xs font-semibold tracking-wider text-gray-500 uppercase whitespace-nowrap">No</th>
              <th class="px-6 py-3 text-xs font-semibold tracking-wider text-gray-500 uppercase whitespace-nowrap">
                Mahasiswa</th>
              <th class="px-6 py-3 text-xs font-semibold tracking-wider text-gray-500 uppercase whitespace-nowrap">Jenis
                Ujian</th>
              <th class="px-6 py-3 text-xs font-semibold tracking-wider text-gray-500 uppercase whitespace-nowrap">Berkas
              </th>
              <th class="px-6 py-3 text-xs font-semibold tracking-wider text-gray-500 uppercase whitespace-nowrap">Tanggal
                Pengajuan</th>
              <th class="px-6 py-3 text-xs font-semibold tracking-wider text-gray-500 uppercase whitespace-nowrap">Status
              </th>
              <th class="px-6 py-3 text-xs font-semibold tracking-wider text-gray-500 uppercase whitespace-nowrap">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            @foreach ($ujians as $item)
              <tr class="transition hover:bg-gray-50">
                <td class="px-6 py-4 text-xs text-gray-700 align-middle">
                  {{ ($ujians->currentPage() - 1) * $ujians->perPage() + $loop->iteration }}</td>
                <td class="px-6 py-4 text-xs text-gray-700 align-middle">
                  <div class="flex flex-col gap-1">
                    <span class="text-sm font-semibold text-gray-900">{{ $item->tugasAkhir->mahasiswa->nama_lengkap }}</span>
                    <span class="text-xs text-gray-500">{{ $item->tugasAkhir->mahasiswa->nim }}</span>
                  </div>
                </td>
                <td class="px-6 py-4 text-xs text-gray-700 align-middle">
                  <span
                    class="inline-block px-3 py-1 text-xs font-semibold text-blue-800 whitespace-nowrap bg-blue-100 rounded-full">{{ ucfirst($item->jenis_ujian) }}</span>
                </td>
                <td class="px-6 py-4 text-xs text-gray-700 align-middle">
                  <span>{{ $item->dokumenUjian->count() }} berkas</span>
                </td>
                <td class="px-6 py-4 text-xs text-gray-700 align-middle">
                  <span class="text-xs text-gray-500 whitespace-nowrap">{{ $item->created_at->format('d M Y') }}</span>
                </td>
                <td class="px-6 py-4 text-xs text-gray-700 align-middle">
                  <span
                    class="inline-block px-3 py-1 text-xs font-semibold text-amber-800 whitespace-nowrap bg-amber-100 rounded-full">{{ ucwords(str_replace('_', ' ', $item->status)) }}</span>
                </td>
                <td class="px-6 py-4 text-xs text-gray-700 align-middle">
                  @if ($item->status === 'menunggu_undangan')
                    <a class="inline-flex items-center justify-center px-5 py-2 text-xs font-medium text-white transition rounded-lg whitespace-nowrap bg-gradient-to-br from-emerald-500 to-emerald-600 hover:-translate-y-px hover:shadow-md hover:shadow-emerald-500/30"
                      href="{{ route('admin.ujian.syarat.undangan', $item->id) }}">Buat Undangan</a>
                  @else
                    <a class="inline-flex items-center justify-center px-5 py-2 text-xs font-medium text-white transition rounded-lg whitespace-nowrap bg-gradient-to-br from-blue-500 to-blue-600 hover:-translate-y-px hover:shadow-md hover:shadow-blue-500/30"
                      href="{{ route('admin.ujian.syarat.detail', $item->id) }}">Verifikasi</a>
                  @endif
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @else
      <div class="px-6 py-10 text-sm text-center text-gray-400 flex flex-col items-center">
        <i class="mb-2 text-2xl fas fa-inbox block"></i>
        Belum ada pengajuan ujian yang menunggu verifikasi.
      </div>
    @endif

  </div>
  {{-- Pagination --}}
  <div class="p-6 border-t border-gray-200">
    {{ $ujians->links() }}
  </div>
@endsection

@push('scripts')
  <script>
    document.querySelector('input[name="search"]')?.addEventListener('keydown', function(e) {
      if (e.key === 'Enter') this.closest('form').submit();
    });
  </script>
@endpush
