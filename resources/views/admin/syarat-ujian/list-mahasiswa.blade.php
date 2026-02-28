@extends('layouts.app')

@section('title', 'Ujian')

@section('sidebar')
  @include('admin.sidebar')
@endsection

@section('content')
  {{-- Banner --}}
  <div class="relative h-48 mb-8 overflow-hidden bg-gradient-to-br from-blue-800 to-blue-500 rounded-xl rounded-2xl">
    <div class="absolute inset-0 flex items-center justify-center bg-black/10">
      <h1 class="px-4 text-3xl font-bold text-center text-white">Verifikasi Syarat Ujian</h1>
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
        class="flex flex-wrap items-center gap-3">
        <div class="relative">
          <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
          <input type="text" placeholder="Cari mahasiswa/NIM..." name="search" value="{{ request('search') }}"
            class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm w-full md:w-56 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 transition-colors">
        </div>
        <div class="relative">
          <select name="jenis" onchange="this.form.submit()"
            class="appearance-none pl-4 pr-9 py-2 border border-gray-300 rounded-lg text-sm bg-white cursor-pointer focus:outline-none focus:border-blue-600">
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

    {{-- Table Wrapper --}}
    <div class="overflow-x-auto">
      <table class="w-full text-left border-collapse">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-xs font-semibold tracking-wider text-gray-500 uppercase whitespace-nowrap">No</th>
            <th class="px-6 py-3 text-xs font-semibold tracking-wider text-gray-500 uppercase whitespace-nowrap">Mahasiswa
            </th>
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
          @forelse ($ujians as $item)
            <tr class="transition hover:bg-gray-50">
              <td class="px-6 py-4 text-sm text-gray-700 align-middle">
                {{ ($ujians->currentPage() - 1) * $ujians->perPage() + $loop->iteration }}</td>
              <td class="px-6 py-4 text-sm text-gray-700 align-middle">
                <div class="flex flex-col gap-1">
                  <span class="font-semibold text-gray-900">{{ $item->tugasAkhir->mahasiswa->nama_lengkap }}</span>
                  <span class="text-xs text-gray-500">{{ $item->tugasAkhir->mahasiswa->nim }}</span>
                </div>
              </td>
              <td class="px-6 py-4 text-sm text-gray-700 align-middle">
                <span
                  class="inline-block px-3 py-1 text-xs font-semibold text-blue-800 whitespace-nowrap bg-blue-100 rounded-full">{{ ucfirst($item->jenis_ujian) }}</span>
              </td>
              <td class="px-6 py-4 text-sm text-gray-700 align-middle">
                <span>{{ $item->dokumenUjian->count() }} berkas</span>
              </td>
              <td class="px-6 py-4 text-sm text-gray-700 align-middle">
                <span class="text-xs text-gray-500 whitespace-nowrap">{{ $item->created_at->format('d M Y') }}</span>
              </td>
              <td class="px-6 py-4 text-sm text-gray-700 align-middle">
                <span
                  class="inline-block px-3 py-1 text-xs font-semibold text-amber-800 whitespace-nowrap bg-amber-100 rounded-full">{{ ucwords(str_replace('_', ' ', $item->status)) }}</span>
              </td>
              <td class="px-6 py-4 text-sm text-gray-700 align-middle">
                @if ($item->status === 'menunggu_undangan')
                  <a class="inline-flex items-center justify-center px-5 py-2 text-xs font-medium text-white transition rounded-lg whitespace-nowrap bg-gradient-to-br from-emerald-500 to-emerald-600 hover:-translate-y-px hover:shadow-md hover:shadow-emerald-500/30"
                    href="{{ route('admin.ujian.syarat.undangan', $item->id) }}">Buat Undangan</a>
                @else
                  <a class="inline-flex items-center justify-center px-5 py-2 text-xs font-medium text-white transition rounded-lg whitespace-nowrap bg-gradient-to-br from-blue-500 to-blue-600 hover:-translate-y-px hover:shadow-md hover:shadow-blue-500/30"
                    href="{{ route('admin.ujian.syarat.detail', $item->id) }}">Verifikasi Berkas</a>
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="px-6 py-10 text-sm text-center text-gray-400">
                <i class="mb-2 text-2xl fas fa-inbox block"></i>
                Belum ada pengajuan ujian yang menunggu verifikasi.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

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
