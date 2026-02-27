@extends('layouts.app')

@section('title', 'Manajemen Dosen')

@section('sidebar')
  @include('admin.sidebar')
@endsection

@section('content')

  <!-- Flash Messages -->
  <x-alert type="success" />
  <x-alert type="error" />
  <x-alert type="warning" />

  <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
      <h1 class="text-3xl font-bold text-gray-900 mb-2">Kelola Dosen</h1>
      <p class="text-gray-500">Kelola data dosen Informatika</p>
    </div>
    <a href="{{ route('admin.dosen.create') }}"
      class="inline-flex items-center gap-2 px-5 py-3 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-all shadow-sm">
      <i class="fas fa-plus"></i> Tambah Dosen
    </a>
  </div>

  <!-- Stats Summary -->
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-6 rounded-xl shadow-sm flex items-center gap-4">
      <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center text-xl">
        <i class="fas fa-chalkboard-teacher"></i>
      </div>
      <div>
        <div class="text-sm text-gray-500 mb-1">Total Dosen</div>
        <div class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</div>
      </div>
    </div>
    <div class="bg-white p-6 rounded-xl shadow-sm flex items-center gap-4">
      <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-lg flex items-center justify-center text-xl">
        <i class="fas fa-user-tie"></i>
      </div>
      <div>
        <div class="text-sm text-gray-500 mb-1">Dosen Aktif</div>
        <div class="text-2xl font-bold text-gray-900">{{ $stats['aktif'] }}</div>
      </div>
    </div>
    <div class="bg-white p-6 rounded-xl shadow-sm flex items-center gap-4 opacity-60">
      <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-lg flex items-center justify-center text-xl">
        <i class="fas fa-book-open"></i>
      </div>
      <div>
        <div class="text-sm text-gray-500 mb-1">Total Publikasi</div>
        <div class="text-2xl font-bold text-gray-400">—</div>
        <div class="text-xs text-gray-400">Segera hadir</div>
      </div>
    </div>
  </div>

  <!-- Data Table Card -->
  <div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="p-5 border-b border-gray-200 flex flex-wrap items-center justify-between gap-4">
      <h3 class="text-lg font-semibold text-gray-900">Daftar Dosen</h3>
      <form action="{{ route('admin.dosen.index') }}" method="GET" class="flex flex-wrap items-center gap-3">

        {{-- Search --}}
        <div class="relative">
          <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
          <input type="text" placeholder="Cari nama atau NIDN..." name="search" value="{{ request('search') }}"
            class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm w-full md:w-56 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600/10 transition-colors" />
        </div>

        {{-- Filter Jabatan Fungsional --}}
        <div class="relative">
          <select name="jabatan" onchange="this.form.submit()"
            class="appearance-none pl-4 pr-9 py-2 border border-gray-300 rounded-lg text-sm bg-white cursor-pointer focus:outline-none focus:border-blue-600">
            <option value="">Semua Jabatan</option>
            @foreach (['Asisten Ahli', 'Lektor', 'Lektor Kepala', 'Guru Besar'] as $jab)
              <option value="{{ $jab }}" {{ request('jabatan') === $jab ? 'selected' : '' }}>
                {{ $jab }}
              </option>
            @endforeach
          </select>
          <i
            class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
        </div>

        {{-- Filter Status --}}
        <div class="relative">
          <select name="status" onchange="this.form.submit()"
            class="appearance-none pl-4 pr-9 py-2 border border-gray-300 rounded-lg text-sm bg-white cursor-pointer focus:outline-none focus:border-blue-600">
            <option value="">Semua Status</option>
            @foreach (['aktif' => 'Aktif', 'cuti' => 'Cuti', 'nonaktif' => 'Nonaktif', 'pensiun' => 'Pensiun'] as $val => $lbl)
              <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>{{ $lbl }}
              </option>
            @endforeach
          </select>
          <i
            class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
        </div>

      </form>
    </div>

    <div class="overflow-x-auto">
      <table class="w-full border-collapse">
        <thead>
          <tr class="bg-gray-50 border-b border-gray-200">
            <th class="px-5 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No</th>
            <th class="px-5 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Dosen</th>
            <th class="px-5 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Jabatan
              Fungsional</th>
            <th class="px-5 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
            <th class="px-5 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Jumlah Publikasi
            </th>
            <th class="px-5 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          @forelse ($daftarDosen as $dosen)
            <tr class="hover:bg-gray-50 transition-colors">
              <td class="px-5 py-4 text-sm text-gray-700">
                {{ ($daftarDosen->currentPage() - 1) * $daftarDosen->perPage() + $loop->iteration }}
              </td>

              {{-- Dosen --}}
              <td class="px-5 py-4">
                <div class="flex items-center gap-3">
                  <div
                    class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-semibold text-sm shrink-0">
                    {{ strtoupper($dosen->nama_lengkap[0]) }}
                  </div>
                  <div>
                    <div class="font-medium text-gray-900">{{ $dosen->nama_lengkap }}</div>
                    <div class="text-xs text-gray-500">NIDN {{ $dosen->nidn }}</div>
                  </div>
                </div>
              </td>

              {{-- Jabatan Fungsional --}}
              <td class="px-5 py-4">
                @php
                  $jabatanCfg = [
                      'Asisten Ahli' => 'bg-sky-100 text-sky-700',
                      'Lektor' => 'bg-violet-100 text-violet-700',
                      'Lektor Kepala' => 'bg-indigo-100 text-indigo-700',
                      'Guru Besar' => 'bg-amber-100 text-amber-700',
                  ];
                  $jabCls = $jabatanCfg[$dosen->jabatan_fungsional] ?? 'bg-gray-100 text-gray-600';
                @endphp
                @if ($dosen->jabatan_fungsional)
                  <span
                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $jabCls }}">
                    {{ $dosen->jabatan_fungsional }}
                  </span>
                @else
                  <span class="text-gray-400 text-sm">—</span>
                @endif
              </td>

              {{-- Status Dosen --}}
              <td class="px-5 py-4">
                @php
                  $statusCfg = [
                      'aktif' => 'bg-emerald-100 text-emerald-700',
                      'cuti' => 'bg-amber-100 text-amber-700',
                      'nonaktif' => 'bg-gray-100 text-gray-600',
                      'pensiun' => 'bg-red-100 text-red-600',
                  ];
                  $cls = $statusCfg[$dosen->status] ?? 'bg-gray-100 text-gray-600';
                @endphp
                <span
                  class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $cls }}">
                  {{ ucfirst($dosen->status) }}
                </span>
              </td>

              {{-- Jumlah Publikasi --}}
              <td class="px-5 py-4 text-sm text-gray-400 italic">—</td>

              {{-- Aksi --}}
              <td class="px-5 py-4">
                <div class="flex gap-2">
                  <a href="{{ route('admin.dosen.show', $dosen->id) }}"
                    class="w-8 h-8 rounded-md bg-blue-100 text-blue-600 flex items-center justify-center hover:bg-blue-200 transition-colors"
                    title="Lihat Detail">
                    <i class="fas fa-eye text-xs"></i>
                  </a>
                  <a href="{{ route('admin.dosen.edit', $dosen->id) }}"
                    class="w-8 h-8 rounded-md bg-amber-100 text-amber-700 flex items-center justify-center hover:bg-amber-200 transition-colors"
                    title="Edit">
                    <i class="fas fa-edit text-xs"></i>
                  </a>
                  <button type="button"
                    onclick="window.dispatchEvent(new CustomEvent('open-delete-modal', { detail: { id: {{ $dosen->id }}, nama: '{{ addslashes($dosen->nama_lengkap) }}', nidn: '{{ $dosen->nidn }}' } }))"
                    class="w-8 h-8 rounded-md bg-red-100 text-red-600 flex items-center justify-center hover:bg-red-200 transition-colors"
                    title="Hapus">
                    <i class="fas fa-trash text-xs"></i>
                  </button>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="px-5 py-12 text-center text-gray-400">
                <i class="fas fa-chalkboard-teacher text-3xl mb-3 block opacity-30"></i>
                Tidak ada data dosen.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <div class="mt-4">
    {{ $daftarDosen->links() }}
  </div>

  {{-- Modal Konfirmasi Hapus --}}
  <div x-data="{ open: false, id: null, nama: '', nidn: '' }"
    x-on:open-delete-modal.window="open = true; id = $event.detail.id; nama = $event.detail.nama; nidn = $event.detail.nidn"
    x-show="open" x-transition.opacity
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm" style="display: none;">

    <div x-show="open" x-transition:enter="transition ease-out duration-200"
      x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
      @click.outside="open = false" class="bg-white rounded-2xl shadow-xl p-6 w-full max-w-sm mx-4">

      <div class="flex items-center gap-3 mb-4">
        <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center shrink-0">
          <i class="fas fa-triangle-exclamation text-red-600"></i>
        </div>
        <div>
          <h3 class="text-sm font-bold text-gray-900">Hapus Dosen</h3>
          <p class="text-xs text-gray-500">Tindakan ini tidak dapat dibatalkan</p>
        </div>
      </div>

      <p class="text-sm text-gray-600 mb-5">
        Yakin ingin menghapus akun dosen
        <span class="font-semibold text-gray-900" x-text="nama"></span>
        (NIDN: <span class="font-semibold" x-text="nidn"></span>)?
        Seluruh data terkait juga akan ikut terhapus.
      </p>

      <div class="flex gap-3">
        <button @click="open = false" type="button"
          class="flex-1 px-4 py-2.5 rounded-lg text-sm font-medium border border-gray-300 text-gray-600 hover:bg-gray-50 transition-all">
          Batal
        </button>
        <form method="POST" :action="`/admin/dosen/${id}`" class="flex-1">
          @csrf
          @method('DELETE')
          <button type="submit"
            class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg text-sm font-semibold bg-red-600 text-white hover:bg-red-700 transition-all">
            <i class="fas fa-trash text-xs"></i> Ya, Hapus
          </button>
        </form>
      </div>
    </div>
  </div>

@endsection
