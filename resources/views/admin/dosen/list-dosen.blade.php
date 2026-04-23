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

  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 md:gap-4 mb-5 md:mb-8">
    <div>
      <h1 class="text-xl md:text-3xl font-bold text-gray-900 mb-0.5 md:mb-2">Kelola Dosen</h1>
      <p class="text-gray-500 text-sm">Kelola data dosen Informatika</p>
    </div>
    <div class="flex flex-wrap items-center gap-2 shrink-0">
      <a href="{{ asset('templates/template-import-dosen.csv') }}" download
        class="inline-flex items-center gap-2 px-4 md:px-5 py-2 md:py-3 border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition-all shadow-sm">
        <i class="fas fa-file-arrow-down text-xs"></i>
        Unduh Template
      </a>
      <button type="button" x-data x-on:click="$dispatch('open-modal', 'import-dosen')"
        class="inline-flex items-center gap-2 px-4 md:px-5 py-2 md:py-3 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700 transition-all shadow-sm">
        <i class="fas fa-file-import text-xs"></i>
        Import Dosen
      </button>
      <a href="{{ route('admin.dosen.create') }}"
        class="inline-flex items-center gap-2 px-4 md:px-5 py-2 md:py-3 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-all shadow-sm">
        <i class="fas fa-plus text-xs"></i> Tambah Dosen
      </a>
    </div>
  </div>

  <!-- Stats Summary -->
  <div class="grid grid-cols-3 gap-3 md:gap-6 mb-5 md:mb-8">
    <div class="bg-white p-3 md:p-6 rounded-xl shadow-sm flex items-center gap-2 md:gap-4">
      <div
        class="w-8 h-8 md:w-12 md:h-12 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center text-sm md:text-xl shrink-0">
        <i class="fas fa-chalkboard-teacher"></i>
      </div>
      <div>
        <div class="text-xs md:text-sm text-gray-500 mb-0.5 md:mb-1">Total Dosen</div>
        <div class="text-base md:text-2xl font-bold text-gray-900">{{ $stats['total'] }}</div>
      </div>
    </div>
    <div class="bg-white p-3 md:p-6 rounded-xl shadow-sm flex items-center gap-2 md:gap-4">
      <div
        class="w-8 h-8 md:w-12 md:h-12 bg-emerald-100 text-emerald-600 rounded-lg flex items-center justify-center text-sm md:text-xl shrink-0">
        <i class="fas fa-user-tie"></i>
      </div>
      <div>
        <div class="text-xs md:text-sm text-gray-500 mb-0.5 md:mb-1">Dosen Aktif</div>
        <div class="text-base md:text-2xl font-bold text-gray-900">{{ $stats['aktif'] }}</div>
      </div>
    </div>
    <div class="bg-white p-3 md:p-6 rounded-xl shadow-sm flex items-center gap-2 md:gap-4">
      <div
        class="w-8 h-8 md:w-12 md:h-12 bg-purple-100 text-purple-600 rounded-lg flex items-center justify-center text-sm md:text-xl shrink-0">
        <i class="fas fa-book-open"></i>
      </div>
      <div>
        <div class="text-xs md:text-sm text-gray-500 mb-0.5 md:mb-1">Total Publikasi</div>
        <div class="text-base md:text-2xl font-bold text-gray-900">{{ $stats['total_publikasi'] }}</div>
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
              <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>
                {{ $lbl }}
              </option>
            @endforeach
          </select>
          <i
            class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
        </div>

      </form>
    </div>

    {{-- ░░ TABEL — tampil di sm ke atas ░░ --}}
    <div class="hidden lg:block overflow-x-auto">
      <table class="w-full border-collapse">
        <thead>
          <tr class="bg-gray-50 border-b border-gray-200">
            <th class="px-5 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No</th>
            <th class="px-5 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Dosen</th>
            <th class="px-5 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Jabatan
              Fungsional</th>
            <th class="px-5 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Keahlian</th>
            <th class="px-5 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
            <th class="px-5 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Publikasi</th>
            <th class="px-5 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">SINTA 3Yr</th>
            <th class="px-5 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          @forelse ($daftarDosen as $dosen)
            @php
              $jabatanCfg = [
                  'Asisten Ahli' => 'bg-sky-100 text-sky-700',
                  'Lektor' => 'bg-violet-100 text-violet-700',
                  'Lektor Kepala' => 'bg-indigo-100 text-indigo-700',
                  'Guru Besar' => 'bg-amber-100 text-amber-700',
              ];
              $jabCls = $jabatanCfg[$dosen->jabatan_fungsional] ?? 'bg-gray-100 text-gray-600';
              $statusCfg = [
                  'aktif' => 'bg-emerald-100 text-emerald-700',
                  'cuti' => 'bg-amber-100 text-amber-700',
                  'nonaktif' => 'bg-gray-100 text-gray-600',
                  'pensiun' => 'bg-red-100 text-red-600',
              ];
              $cls = $statusCfg[$dosen->status] ?? 'bg-gray-100 text-gray-600';
            @endphp
            <tr class="hover:bg-gray-50 transition-colors">
              <td class="px-5 py-4 text-sm text-gray-500">
                {{ ($daftarDosen->currentPage() - 1) * $daftarDosen->perPage() + $loop->iteration }}
              </td>
              <td class="px-5 py-4">
                <div class="flex items-center gap-3">
                  <x-avatar :src="$dosen->foto" :initials="$dosen->initials" />
                  <div>
                    <div class="font-medium text-gray-900 text-sm">{{ $dosen->nama_lengkap }}</div>
                    <div class="text-xs text-gray-500">NIDN {{ $dosen->nidn }}</div>
                  </div>
                </div>
              </td>
              <td class="px-5 py-4">
                @if ($dosen->jabatan_fungsional)
                  <span
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $jabCls }}">
                    {{ $dosen->jabatan_fungsional }}
                  </span>
                @else
                  <span class="text-gray-400 text-sm">—</span>
                @endif
              </td>
              <td class="px-5 py-4 text-sm text-gray-600 max-w-[180px] truncate">{{ $dosen->keahlian ?? '—' }}</td>
              <td class="px-5 py-4">
                <span
                  class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $cls }}">
                  {{ ucfirst($dosen->status) }}
                </span>
              </td>
              <td class="px-5 py-4 text-sm">
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold ">
                  {{ $dosen->publikasi_count }}
                </span>
              </td>
              <td class="px-5 py-4 text-sm text-gray-700">
                {{ number_format((float) ($dosen->sinta_score_3y ?? 0), 2) }}
              </td>
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
                  <form method="POST" action="{{ route('admin.dosen.reset-password', $dosen->id) }}"
                    onsubmit="return confirm('Reset password dosen {{ addslashes($dosen->nama_lengkap) }} ke 12345@#?')">
                    @csrf
                    <button type="submit"
                      class="w-8 h-8 rounded-md bg-violet-100 text-violet-700 flex items-center justify-center hover:bg-violet-200 transition-colors"
                      title="Reset Password ke 12345@#">
                      <i class="fas fa-key text-xs"></i>
                    </button>
                  </form>
                  <button type="button" data-id="{{ $dosen->id }}" data-nama="{{ $dosen->nama_lengkap }}"
                    data-nidn="{{ $dosen->nidn }}"
                    onclick="window.dispatchEvent(new CustomEvent('open-delete-modal', { detail: { id: this.dataset.id, nama: this.dataset.nama, nidn: this.dataset.nidn } }))"
                    class="w-8 h-8 rounded-md bg-red-100 text-red-600 flex items-center justify-center hover:bg-red-200 transition-colors"
                    title="Hapus">
                    <i class="fas fa-trash text-xs"></i>
                  </button>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="8" class="px-5 py-12 text-center text-gray-400">
                <i class="fas fa-chalkboard-teacher text-3xl mb-3 block opacity-30"></i>
                Tidak ada data dosen.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- ░░ CARD LIST — tampil di mobile saja ░░ --}}
    <div class="block lg:hidden divide-y divide-gray-100">
      @forelse ($daftarDosen as $dosen)
        @php
          $jabatanCfg = [
              'Asisten Ahli' => 'bg-sky-100 text-sky-700',
              'Lektor' => 'bg-violet-100 text-violet-700',
              'Lektor Kepala' => 'bg-indigo-100 text-indigo-700',
              'Guru Besar' => 'bg-amber-100 text-amber-700',
          ];
          $jabCls = $jabatanCfg[$dosen->jabatan_fungsional] ?? 'bg-gray-100 text-gray-600';
          $statusCfg = [
              'aktif' => 'bg-emerald-100 text-emerald-700',
              'cuti' => 'bg-amber-100 text-amber-700',
              'nonaktif' => 'bg-gray-100 text-gray-600',
              'pensiun' => 'bg-red-100 text-red-600',
          ];
          $cls = $statusCfg[$dosen->status] ?? 'bg-gray-100 text-gray-600';
        @endphp
        <div class="px-4 py-3.5 flex items-center gap-3">
          {{-- Avatar --}}
          <x-avatar :src="$dosen->foto" :initials="$dosen->initials" size="lg" />
          {{-- Info --}}
          <div class="flex-1 min-w-0">
            <div class="font-medium text-gray-900 text-sm truncate">{{ $dosen->nama_lengkap }}</div>
            <div class="text-xs text-gray-500">NIDN {{ $dosen->nidn }}</div>
            <div class="mt-1 flex flex-wrap gap-1.5">
              @if ($dosen->jabatan_fungsional)
                <span
                  class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold {{ $jabCls }}">
                  {{ $dosen->jabatan_fungsional }}
                </span>
              @endif
              <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold {{ $cls }}">
                {{ ucfirst($dosen->status) }}
              </span>
              <span
                class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-purple-100 text-purple-700">
                <i class="fas fa-book-open text-[10px]"></i>
                {{ $dosen->publikasi_count }} publikasi
              </span>
              <span
                class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-sky-100 text-sky-700">
                <i class="fas fa-chart-line text-[10px]"></i>
                SINTA 3Yr {{ number_format((float) ($dosen->sinta_score_3y ?? 0), 2) }}
              </span>
            </div>
          </div>
          {{-- Aksi --}}
          <div class="flex items-center gap-1.5 shrink-0">
            <a href="{{ route('admin.dosen.show', $dosen->id) }}"
              class="w-8 h-8 rounded-md bg-blue-100 text-blue-600 flex items-center justify-center hover:bg-blue-200 transition-colors">
              <i class="fas fa-eye text-xs"></i>
            </a>
            <a href="{{ route('admin.dosen.edit', $dosen->id) }}"
              class="w-8 h-8 rounded-md bg-amber-100 text-amber-700 flex items-center justify-center hover:bg-amber-200 transition-colors">
              <i class="fas fa-edit text-xs"></i>
            </a>
            <form method="POST" action="{{ route('admin.dosen.reset-password', $dosen->id) }}"
              onsubmit="return confirm('Reset password dosen {{ addslashes($dosen->nama_lengkap) }} ke 12345@#?')">
              @csrf
              <button type="submit"
                class="w-8 h-8 rounded-md bg-violet-100 text-violet-700 flex items-center justify-center hover:bg-violet-200 transition-colors">
                <i class="fas fa-key text-xs"></i>
              </button>
            </form>
            <button type="button" data-id="{{ $dosen->id }}" data-nama="{{ $dosen->nama_lengkap }}"
              data-nidn="{{ $dosen->nidn }}"
              onclick="window.dispatchEvent(new CustomEvent('open-delete-modal', { detail: { id: this.dataset.id, nama: this.dataset.nama, nidn: this.dataset.nidn } }))"
              class="w-8 h-8 rounded-md bg-red-100 text-red-600 flex items-center justify-center hover:bg-red-200 transition-colors">
              <i class="fas fa-trash text-xs"></i>
            </button>
          </div>
        </div>
      @empty
        <div class="px-5 py-12 text-center">
          <i class="fas fa-chalkboard-teacher text-3xl mb-3 block text-gray-200"></i>
          <p class="text-sm text-gray-400">Tidak ada data dosen.</p>
        </div>
      @endforelse
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

  @include('admin.dosen.partials.import-modal')

@endsection
