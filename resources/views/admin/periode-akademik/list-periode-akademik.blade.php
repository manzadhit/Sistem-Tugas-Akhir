@extends('layouts.app')

@section('title', 'Manajemen Periode Akademik')

@section('sidebar')
  @include('admin.sidebar')
@endsection

@section('content')

  <!-- Flash Messages -->
  <x-alert type="success" />
  <x-alert type="error" />
  <x-alert type="warning" />

  <div>
    @php
      $totalPeriode = $periodes->total();
      $jumlahAktif  = $periodeAktif ? 1 : 0;
      $jumlahDraft  = $periodes->getCollection()->where('status', 'draft')->count();
    @endphp

    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 md:gap-4 mb-5 md:mb-8">
      <div>
        <h1 class="text-xl md:text-3xl font-bold text-gray-900 mb-0.5 md:mb-2">Kelola Periode Akademik</h1>
        <p class="text-gray-500 text-sm">Atur periode akademik yang digunakan pada proses tugas akhir.</p>
      </div>
      <a href="{{ route('admin.periode.create') }}"
        class="inline-flex items-center gap-2 px-4 md:px-5 py-2 md:py-3 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-all shadow-sm shrink-0">
        <i class="fas fa-plus text-xs"></i>
        Tambah Periode
      </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-3 gap-3 md:gap-6 mb-5 md:mb-8">
      <div class="bg-white p-3 md:p-6 rounded-xl shadow-sm flex items-center gap-2 md:gap-4">
        <div class="w-8 h-8 md:w-12 md:h-12 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center text-sm md:text-xl shrink-0">
          <i class="fas fa-calendar-alt"></i>
        </div>
        <div>
          <div class="text-xs md:text-sm text-gray-500 mb-0.5 md:mb-1">Total Periode</div>
          <div class="text-base md:text-2xl font-bold text-gray-900">{{ $totalPeriode }}</div>
        </div>
      </div>

      <div class="bg-white p-3 md:p-6 rounded-xl shadow-sm flex items-center gap-2 md:gap-4">
        <div class="w-8 h-8 md:w-12 md:h-12 bg-emerald-100 text-emerald-600 rounded-lg flex items-center justify-center text-sm md:text-xl shrink-0">
          <i class="fas fa-check-circle"></i>
        </div>
        <div>
          <div class="text-xs md:text-sm text-gray-500 mb-0.5 md:mb-1">Periode Aktif</div>
          <div class="text-base md:text-2xl font-bold text-gray-900">{{ $jumlahAktif }}</div>
        </div>
      </div>

      <div class="bg-white p-3 md:p-6 rounded-xl shadow-sm flex items-center gap-2 md:gap-4">
        <div class="w-8 h-8 md:w-12 md:h-12 bg-amber-100 text-amber-600 rounded-lg flex items-center justify-center text-sm md:text-xl shrink-0">
          <i class="fas fa-clock"></i>
        </div>
        <div>
          <div class="text-xs md:text-sm text-gray-500 mb-0.5 md:mb-1">Periode Draft</div>
          <div class="text-base md:text-2xl font-bold text-gray-900">{{ $jumlahDraft }}</div>
        </div>
      </div>
    </div>

    <!-- Data Table Card -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
      <div class="p-5 border-b border-gray-200 flex flex-wrap items-center justify-between gap-4">
        <h3 class="text-lg font-semibold text-gray-900">Daftar Periode Akademik</h3>

        <form action="{{ route('admin.periode.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
          <!-- Cari tahun ajaran -->
          <div class="relative">
            <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400"></i>
            <input
              type="text"
              name="search"
              value="{{ request('search') }}"
              placeholder="Cari tahun ajaran..."
              class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm w-full md:w-56 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600/10 transition-colors"
            />
          </div>

          <!-- Filter semester -->
          <div class="relative">
            <select name="semester" onchange="this.form.submit()"
              class="appearance-none pl-4 pr-9 py-2 border border-gray-300 rounded-lg text-sm bg-white cursor-pointer focus:outline-none focus:border-blue-600">
              <option value="">Semua Semester</option>
              <option value="ganjil" {{ request('semester') === 'ganjil' ? 'selected' : '' }}>Ganjil</option>
              <option value="genap"  {{ request('semester') === 'genap'  ? 'selected' : '' }}>Genap</option>
            </select>
            <i class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
          </div>
        </form>
      </div>

      <!-- Tabel — Desktop -->
      <div class="hidden lg:block overflow-x-auto">
        <table class="w-full border-collapse">
          <thead>
            <tr class="bg-gray-50 border-b border-gray-200">
              <th class="px-5 py-4 text-left font-semibold text-xs text-gray-500 uppercase tracking-wider">No</th>
              <th class="px-5 py-4 text-left font-semibold text-xs text-gray-500 uppercase tracking-wider">Tahun Ajaran</th>
              <th class="px-5 py-4 text-left font-semibold text-xs text-gray-500 uppercase tracking-wider">Semester</th>
              <th class="px-5 py-4 text-left font-semibold text-xs text-gray-500 uppercase tracking-wider">Rentang Tanggal</th>
              <th class="px-5 py-4 text-left font-semibold text-xs text-gray-500 uppercase tracking-wider">Status</th>
              <th class="px-5 py-4 text-left font-semibold text-xs text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200">
            @forelse ($periodes as $periode)
              @php
                $nomorUrut = ($periodes->currentPage() - 1) * $periodes->perPage() + $loop->iteration;
                $namaPeriode = $periode->tahun_ajaran . ' ' . ucfirst($periode->semester);

                [$labelStatus, $kelasStatus] = match ($periode->status) {
                    'aktif'  => ['Aktif',   'bg-emerald-100 text-emerald-700'],
                    'draft'  => ['Draft',   'bg-amber-100 text-amber-700'],
                    default  => ['Selesai', 'bg-slate-100 text-slate-700'],
                };
              @endphp
              <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-5 py-4 text-sm text-gray-500">{{ $nomorUrut }}</td>

                <td class="px-5 py-4">
                  <div class="font-medium text-gray-900 text-sm">{{ $periode->tahun_ajaran }}</div>
                </td>

                <td class="px-5 py-4 text-sm text-gray-700 capitalize">{{ $periode->semester }}</td>

                <td class="px-5 py-4 text-sm text-gray-700">
                  {{ optional($periode->mulai_at)->translatedFormat('d M Y') }}
                  <span class="text-gray-400">–</span>
                  {{ $periode->selesai_at ? optional($periode->selesai_at)->translatedFormat('d M Y') : '–' }}
                </td>

                <td class="px-5 py-4">
                  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $kelasStatus }}">
                    {{ $labelStatus }}
                  </span>
                </td>

                <td class="px-5 py-4">
                  <div class="flex items-center gap-2">

                    <!-- Aktifkan — hanya jika draft -->
                    @if ($periode->status === 'draft')
                      <div x-data="{ open: false }" class="inline-block">
                        <button type="button" @click="open = true" title="Aktifkan"
                          class="w-8 h-8 rounded-md bg-emerald-100 text-emerald-700 flex items-center justify-center hover:bg-emerald-200 transition-colors">
                          <i class="fas fa-check text-xs"></i>
                        </button>

                        <form method="POST" action="{{ route('admin.periode.activate', $periode->id) }}">
                          @csrf @method('PATCH')
                          <x-modal-confirm model="open" title="Konfirmasi Aktivasi" theme="blue" icon="fa-check-circle" confirmText="Aktifkan">
                            Yakin ingin mengaktifkan periode <strong>{{ $namaPeriode }}</strong>?
                            <br>
                            <span class="text-xs text-gray-500">Bila ada periode lain yang aktif, otomatis akan berstatus Selesai.</span>
                          </x-modal-confirm>
                        </form>
                      </div>
                    @endif

                    <!-- Selesaikan — hanya jika aktif -->
                    @if ($periode->status === 'aktif')
                      <div x-data="{ open: false }" class="inline-block">
                        <button type="button" @click="open = true" title="Selesaikan"
                          class="w-8 h-8 rounded-md bg-slate-100 text-slate-700 flex items-center justify-center hover:bg-slate-200 transition-colors">
                          <i class="fas fa-flag-checkered text-xs"></i>
                        </button>

                        <form method="POST" action="{{ route('admin.periode.complete', $periode->id) }}">
                          @csrf @method('PATCH')
                          <x-modal-confirm model="open" title="Selesaikan Periode" theme="blue" icon="fa-flag-checkered" confirmText="Selesaikan">
                            Yakin ingin menyelesaikan periode <strong>{{ $namaPeriode }}</strong> secara paksa/manual?
                          </x-modal-confirm>
                        </form>
                      </div>
                    @endif

                    <!-- Edit -->
                    <a href="{{ route('admin.periode.edit', $periode->id) }}" title="Edit"
                      class="w-8 h-8 rounded-md bg-amber-100 text-amber-700 flex items-center justify-center hover:bg-amber-200 transition-colors">
                      <i class="fas fa-edit text-xs"></i>
                    </a>

                    <!-- Hapus — hanya jika draft -->
                    @if ($periode->status === 'draft')
                      <div x-data="{ open: false }" class="inline-block">
                        <button type="button" @click="open = true" title="Hapus"
                          class="w-8 h-8 rounded-md bg-red-100 text-red-600 flex items-center justify-center hover:bg-red-200 transition-colors">
                          <i class="fas fa-trash text-xs"></i>
                        </button>

                        <form method="POST" action="{{ route('admin.periode.destroy', $periode->id) }}">
                          @csrf @method('DELETE')
                          <x-modal-confirm model="open" title="Konfirmasi Hapus" theme="red" icon="fa-trash" confirmText="Hapus">
                            Yakin ingin menghapus periode akademik <strong>{{ $namaPeriode }}</strong>?
                          </x-modal-confirm>
                        </form>
                      </div>
                    @endif

                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="px-5 py-12 text-center text-gray-400">
                  <i class="fas fa-calendar-times text-3xl mb-3 block opacity-30"></i>
                  Tidak ada data periode akademik.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <!-- Card List — Mobile -->
      <div class="block lg:hidden divide-y divide-gray-100">
        @forelse ($periodes as $periode)
          @php
            $namaPeriode = $periode->tahun_ajaran . ' ' . ucfirst($periode->semester);
            [$labelStatus, $kelasStatus] = match ($periode->status) {
                'aktif'  => ['Aktif',   'bg-emerald-100 text-emerald-700'],
                'draft'  => ['Draft',   'bg-amber-100 text-amber-700'],
                default  => ['Selesai', 'bg-slate-100 text-slate-700'],
            };
          @endphp
          <div class="px-4 py-3.5 flex items-start gap-3">

            <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-sm shrink-0">
              <i class="fas fa-calendar"></i>
            </div>

            <div class="flex-1 min-w-0">
              <div class="font-medium text-gray-900 text-sm">{{ $periode->tahun_ajaran }}</div>
              <div class="text-xs text-gray-500 capitalize">Semester {{ $periode->semester }}</div>
              <div class="text-xs text-gray-500 mt-0.5">
                {{ optional($periode->mulai_at)->translatedFormat('d M Y') }} –
                {{ $periode->selesai_at ? optional($periode->selesai_at)->translatedFormat('d M Y') : '–' }}
              </div>
              <div class="mt-1.5">
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold {{ $kelasStatus }}">
                  {{ $labelStatus }}
                </span>
              </div>
            </div>

            <div class="flex items-center gap-1.5 shrink-0">

              @if ($periode->status === 'draft')
                <div x-data="{ open: false }" class="inline-block">
                  <button type="button" @click="open = true" title="Aktifkan"
                    class="w-8 h-8 rounded-md bg-emerald-100 text-emerald-700 flex items-center justify-center hover:bg-emerald-200 transition-colors">
                    <i class="fas fa-check text-xs"></i>
                  </button>

                  <form method="POST" action="{{ route('admin.periode.activate', $periode->id) }}">
                    @csrf @method('PATCH')
                    <x-modal-confirm model="open" title="Konfirmasi Aktivasi" theme="blue" icon="fa-check-circle" confirmText="Aktifkan">
                      Yakin ingin mengaktifkan periode <strong>{{ $namaPeriode }}</strong>?
                    </x-modal-confirm>
                  </form>
                </div>
              @endif

              @if ($periode->status === 'aktif')
                <div x-data="{ open: false }" class="inline-block">
                  <button type="button" @click="open = true" title="Selesaikan"
                    class="w-8 h-8 rounded-md bg-slate-100 text-slate-700 flex items-center justify-center hover:bg-slate-200 transition-colors">
                    <i class="fas fa-flag-checkered text-xs"></i>
                  </button>

                  <form method="POST" action="{{ route('admin.periode.complete', $periode->id) }}">
                    @csrf @method('PATCH')
                    <x-modal-confirm model="open" title="Selesaikan Periode" theme="blue" icon="fa-flag-checkered" confirmText="Selesaikan">
                      Yakin ingin menyelesaikan periode <strong>{{ $namaPeriode }}</strong> secara paksa/manual?
                    </x-modal-confirm>
                  </form>
                </div>
              @endif

              <a href="{{ route('admin.periode.edit', $periode->id) }}" title="Edit"
                class="w-8 h-8 rounded-md bg-amber-100 text-amber-700 flex items-center justify-center hover:bg-amber-200 transition-colors">
                <i class="fas fa-edit text-xs"></i>
              </a>

              @if ($periode->status === 'draft')
                <div x-data="{ open: false }" class="inline-block">
                  <button type="button" @click="open = true" title="Hapus"
                    class="w-8 h-8 rounded-md bg-red-100 text-red-600 flex items-center justify-center hover:bg-red-200 transition-colors">
                    <i class="fas fa-trash text-xs"></i>
                  </button>

                  <form method="POST" action="{{ route('admin.periode.destroy', $periode->id) }}">
                    @csrf @method('DELETE')
                    <x-modal-confirm model="open" title="Konfirmasi Hapus" theme="red" icon="fa-trash" confirmText="Hapus">
                      Yakin ingin menghapus periode akademik <strong>{{ $namaPeriode }}</strong>?
                    </x-modal-confirm>
                  </form>
                </div>
              @endif

            </div>
          </div>
        @empty
          <div class="px-5 py-12 text-center">
            <i class="fas fa-calendar-times text-3xl mb-3 block text-gray-200"></i>
            <p class="text-sm text-gray-400">Tidak ada data periode akademik.</p>
          </div>
        @endforelse
      </div>
    </div>

    <div class="mt-4">
      {{ $periodes->links() }}
    </div>
  </div>

@endsection
