@extends('layouts.app')

@section('title', 'Publikasi Saya')

@section('sidebar')
  @if (auth()->user()->role === 'kajur')
    @include('kajur.sidebar')
  @else
    @include('dosen.sidebar')
  @endif
@endsection

@section('content')

  <!-- Flash Messages -->
  <x-alert type="success" />
  <x-alert type="error" />
  <x-alert type="warning" />

  <!-- Page Header -->
  <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
      <h1 class="text-3xl font-bold text-gray-900 mb-2">Publikasi Saya</h1>
      <p class="text-gray-500">Kelola data publikasi ilmiah Anda</p>
    </div>
    <div class="flex flex-wrap items-center gap-2">
      <a href="{{ asset('templates/template-publikasi-dosen.csv') }}" download
        class="inline-flex items-center gap-2 px-5 py-3 border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition-all shadow-sm">
        <i class="fas fa-file-arrow-down"></i>
        Unduh Template
      </a>
      <button type="button" x-data x-on:click="$dispatch('open-modal', 'import-publikasi-dosen')"
        class="inline-flex items-center gap-2 px-5 py-3 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700 transition-all shadow-sm">
        <i class="fas fa-file-import"></i>
        Import Publikasi
      </button>
      <a href="{{ route('dosen.publikasi.create') }}"
        class="inline-flex items-center gap-2 px-5 py-3 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-all shadow-sm">
        <i class="fas fa-plus"></i>
        Tambah Publikasi
      </a>
    </div>
  </div>

  <!-- Stats Summary -->
  <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-xl shadow-sm flex items-center gap-4">
      <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center text-xl">
        <i class="fas fa-book"></i>
      </div>
      <div>
        <div class="text-sm text-gray-500 mb-1">Total Publikasi</div>
        <div class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</div>
      </div>
    </div>
    <div class="bg-white p-6 rounded-xl shadow-sm flex items-center gap-4">
      <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-lg flex items-center justify-center text-xl">
        <i class="fas fa-newspaper"></i>
      </div>
      <div>
        <div class="text-sm text-gray-500 mb-1">Jurnal</div>
        <div class="text-2xl font-bold text-gray-900">{{ $stats['jurnal'] }}</div>
      </div>
    </div>
    <div class="bg-white p-6 rounded-xl shadow-sm flex items-center gap-4">
      <div class="w-12 h-12 bg-orange-100 text-orange-600 rounded-lg flex items-center justify-center text-xl">
        <i class="fas fa-book-open"></i>
      </div>
      <div>
        <div class="text-sm text-gray-500 mb-1">Buku</div>
        <div class="text-2xl font-bold text-gray-900">{{ $stats['buku'] }}</div>
      </div>
    </div>
    <div class="bg-white p-6 rounded-xl shadow-sm flex items-center gap-4">
      <div class="w-12 h-12 bg-amber-100 text-amber-600 rounded-lg flex items-center justify-center text-xl">
        <i class="fas fa-certificate"></i>
      </div>
      <div>
        <div class="text-sm text-gray-500 mb-1">HaKI</div>
        <div class="text-2xl font-bold text-gray-900">{{ $stats['haki'] }}</div>
      </div>
    </div>
  </div>

  <!-- Data Table Card -->
  <div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="p-5 border-b border-gray-200 flex flex-wrap items-center justify-between gap-4">
      <h3 class="text-lg font-semibold text-gray-900">Daftar Publikasi</h3>
      <form action="{{ route('dosen.publikasi.index') }}" method="GET" id="filterForm"
        class="flex flex-wrap items-center gap-3">
        <div class="relative">
          <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
          <input type="text" placeholder="Cari judul..." name="search" value="{{ request('search') }}"
            class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm w-full md:w-60 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600/10 transition-colors" />
        </div>
        <select name="kategori" onchange="this.form.submit()"
          class="appearance-none pl-4 pr-9 py-2 border border-gray-300 rounded-lg text-sm bg-white cursor-pointer focus:outline-none focus:border-blue-600">
          <option value="">Semua Kategori</option>
          <option value="jurnal" {{ request('kategori') === 'jurnal' ? 'selected' : '' }}>Jurnal</option>
          <option value="buku" {{ request('kategori') === 'buku' ? 'selected' : '' }}>Buku</option>
          <option value="haki" {{ request('kategori') === 'haki' ? 'selected' : '' }}>HaKI</option>
        </select>
        <select name="tahun" onchange="this.form.submit()"
          class="appearance-none pl-4 pr-9 py-2 border border-gray-300 rounded-lg text-sm bg-white cursor-pointer focus:outline-none focus:border-blue-600">
          <option value="">Semua Tahun</option>
          @foreach (range(date('Y'), 2000) as $yr)
            <option value="{{ $yr }}" {{ request('tahun') == $yr ? 'selected' : '' }}>{{ $yr }}
            </option>
          @endforeach
        </select>
        @if (request()->hasAny(['search', 'kategori', 'tahun']))
          <a href="{{ route('dosen.publikasi.index') }}"
            class="px-3 py-2 text-sm text-gray-500 hover:text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
            <i class="fas fa-times"></i>
          </a>
        @endif
      </form>
    </div>

    <!-- Table -->
    <div class="hidden lg:block overflow-x-auto">
      <table class="w-full border-collapse" id="publikasiTable">
        <thead>
          <tr class="bg-gray-50 border-b border-gray-200">
            <th class="px-5 py-4 text-left font-semibold text-xs text-gray-500 uppercase tracking-wider w-12">No</th>
            <th class="px-5 py-4 text-left font-semibold text-xs text-gray-500 uppercase tracking-wider">Judul Publikasi
            </th>
            <th class="px-5 py-4 text-left font-semibold text-xs text-gray-500 uppercase tracking-wider">Kategori</th>
            <th class="px-5 py-4 text-left font-semibold text-xs text-gray-500 uppercase tracking-wider">Tahun</th>
            <th class="px-5 py-4 text-left font-semibold text-xs text-gray-500 uppercase tracking-wider">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          @forelse ($publikasi as $pub)
            @php
              $badgeCls = match ($pub->jenis_publikasi) {
                  'jurnal' => 'bg-blue-100 text-blue-700',
                  'buku' => 'bg-purple-100 text-purple-700',
                  'haki' => 'bg-amber-100 text-amber-700',
                  default => 'bg-gray-100 text-gray-700',
              };
            @endphp
            <tr class="hover:bg-gray-50 transition-colors">
              <td class="px-5 py-4 text-sm text-gray-500">
                {{ ($publikasi->currentPage() - 1) * $publikasi->perPage() + $loop->iteration }}
              </td>
              <td class="px-5 py-4 w-full">
                <div class="font-medium text-gray-900 text-sm">{{ $pub->judul }}</div>
                @if ($pub->penerbit)
                  <div class="text-xs text-gray-500 mt-0.5">{{ $pub->penerbit }}</div>
                @endif
              </td>
              <td class="px-5 py-4">
                <span
                  class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $badgeCls }}">
                  {{ ucfirst($pub->jenis_publikasi) }}
                </span>
              </td>
              <td class="px-5 py-4 text-sm text-gray-700">{{ $pub->tahun }}</td>
              <td class="px-5 py-4">
                <div class="flex gap-2">
                  <a href="{{ route('dosen.publikasi.show', $pub->id) }}"
                    class="w-8 h-8 rounded-md bg-blue-100 text-blue-600 flex items-center justify-center hover:bg-blue-200 transition-colors"
                    title="Lihat Detail">
                    <i class="fas fa-eye text-xs"></i>
                  </a>
                  <a href="{{ route('dosen.publikasi.edit', $pub->id) }}"
                    class="w-8 h-8 rounded-md bg-amber-100 text-amber-700 flex items-center justify-center hover:bg-amber-200 transition-colors"
                    title="Edit">
                    <i class="fas fa-edit text-xs"></i>
                  </a>
                  <button type="button"
                    data-id="{{ $pub->id }}"
                    data-judul="{{ Str::limit($pub->judul, 60) }}"
                    onclick="window.dispatchEvent(new CustomEvent('open-delete-modal', { detail: { id: this.dataset.id, judul: this.dataset.judul } }))"
                    class="w-8 h-8 rounded-md bg-red-100 text-red-600 flex items-center justify-center hover:bg-red-200 transition-colors"
                    title="Hapus">
                    <i class="fas fa-trash text-xs"></i>
                  </button>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="px-5 py-12 text-center text-gray-400">
                <i class="fas fa-book text-3xl mb-3 block opacity-30"></i>
                Belum ada data publikasi.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- Card List — mobile & tablet --}}
    <div class="block lg:hidden divide-y divide-gray-100">
      @forelse ($publikasi as $pub)
        @php
          $badgeCls = match ($pub->jenis_publikasi) {
              'jurnal' => 'bg-blue-100 text-blue-700',
              'buku' => 'bg-purple-100 text-purple-700',
              'haki' => 'bg-amber-100 text-amber-700',
              default => 'bg-gray-100 text-gray-700',
          };
        @endphp
        <div class="px-4 py-3.5">
          <div class="font-medium text-gray-900 text-sm">{{ $pub->judul }}</div>
          <div class="text-xs text-gray-500 mt-0.5">{{ $pub->tahun }}</div>
          <div class="flex items-center justify-between mt-2">
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold {{ $badgeCls }}">
              {{ ucfirst($pub->jenis_publikasi) }}
            </span>
            <div class="flex items-center gap-1.5">
              <a href="{{ route('dosen.publikasi.show', $pub->id) }}"
                class="w-8 h-8 rounded-md bg-blue-100 text-blue-600 flex items-center justify-center hover:bg-blue-200 transition-colors">
                <i class="fas fa-eye text-xs"></i>
              </a>
              <a href="{{ route('dosen.publikasi.edit', $pub->id) }}"
                class="w-8 h-8 rounded-md bg-amber-100 text-amber-700 flex items-center justify-center hover:bg-amber-200 transition-colors">
                <i class="fas fa-edit text-xs"></i>
              </a>
              <button type="button"
                data-id="{{ $pub->id }}"
                data-judul="{{ Str::limit($pub->judul, 60) }}"
                onclick="window.dispatchEvent(new CustomEvent('open-delete-modal', { detail: { id: this.dataset.id, judul: this.dataset.judul } }))"
                class="w-8 h-8 rounded-md bg-red-100 text-red-600 flex items-center justify-center hover:bg-red-200 transition-colors">
                <i class="fas fa-trash text-xs"></i>
              </button>
            </div>
          </div>
        </div>
      @empty
        <div class="px-5 py-12 text-center">
          <i class="fas fa-book text-3xl mb-3 block text-gray-200"></i>
          <p class="text-sm text-gray-400">Belum ada data publikasi.</p>
        </div>
      @endforelse
    </div>
  </div>

  <div class="mt-4">
    {{ $publikasi->links() }}
  </div>

  @include('dosen.publikasi.partials.import-modal')

  {{-- Modal Konfirmasi Hapus --}}
  <div x-data="{ open: false, id: null, judul: '' }"
    x-on:open-delete-modal.window="open = true; id = $event.detail.id; judul = $event.detail.judul" x-show="open"
    x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm"
    style="display: none;">

    <div x-show="open" x-transition:enter="transition ease-out duration-200"
      x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
      @click.outside="open = false" class="bg-white rounded-2xl shadow-xl p-6 w-full max-w-sm mx-4">

      <div class="flex items-center gap-3 mb-4">
        <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center shrink-0">
          <i class="fas fa-triangle-exclamation text-red-600"></i>
        </div>
        <div>
          <h3 class="text-sm font-bold text-gray-900">Hapus Publikasi</h3>
          <p class="text-xs text-gray-500">Tindakan ini tidak dapat dibatalkan</p>
        </div>
      </div>

      <p class="text-sm text-gray-600 mb-5">
        Yakin ingin menghapus publikasi
        <span class="font-semibold text-gray-900" x-text='"&quot;" + judul + "&quot;"'></span>?
        Data ini akan dihapus secara permanen.
      </p>

      <div class="flex gap-3">
        <button @click="open = false" type="button"
          class="flex-1 px-4 py-2.5 rounded-lg text-sm font-medium border border-gray-300 text-gray-600 hover:bg-gray-50 transition-all">
          Batal
        </button>
        <form method="POST" :action="`/dosen/publikasi/${id}`" class="flex-1">
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

  @push('scripts')
    <script>
      // Submit filter form on Enter in search input
      document.querySelector('input[name="search"]')?.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') this.closest('form').submit();
      });
    </script>
  @endpush

@endsection
