@extends('layouts.app')

@section('title', 'Input Nilai Ujian')

@section('sidebar')
  @if (auth()->user()->role === 'kajur')
    @include('kajur.sidebar')
  @else
    @include('dosen.sidebar')
  @endif
@endsection

@section('content')
  <x-alert type="success" />
  <x-alert type="error" />
  <x-alert type="warning" />

  {{-- Header --}}
  <div class="bg-gradient-to-br from-blue-800 to-blue-500 rounded-2xl p-5 sm:p-8 mb-6 text-white">
    <div class="flex justify-between items-center flex-wrap gap-4">
      <div>
        <h1 class="text-xl sm:text-[1.75rem] font-bold mb-1 sm:mb-2">
          <i class="fas fa-pencil-alt mr-2 sm:mr-3"></i>Input Nilai Ujian Skripsi
        </h1>
        <p class="opacity-90 text-sm sm:text-[0.95rem]">Kelola penilaian ujian skripsi mahasiswa</p>
      </div>
    </div>
  </div>

  {{-- Stats --}}
  <div class="grid grid-cols-2 gap-3 lg:gap-6 mb-6">
    <div
      class="bg-white rounded-xl p-4 lg:p-6 shadow-sm flex items-center gap-3 lg:gap-4 hover:-translate-y-0.5 hover:shadow-md transition-all">
      <div
        class="w-10 h-10 lg:w-14 lg:h-14 rounded-xl bg-orange-100 text-orange-500 flex items-center justify-center text-lg lg:text-2xl shrink-0">
        <i class="fas fa-clock"></i>
      </div>
      <div class="flex-1 min-w-0">
        <div class="text-xs lg:text-sm text-gray-500 mb-0.5 lg:mb-1">Menunggu Dinilai</div>
        <div class="text-xl lg:text-3xl font-bold text-gray-900">{{ $menunggu }}</div>
      </div>
    </div>
    <div
      class="bg-white rounded-xl p-4 lg:p-6 shadow-sm flex items-center gap-3 lg:gap-4 hover:-translate-y-0.5 hover:shadow-md transition-all">
      <div
        class="w-10 h-10 lg:w-14 lg:h-14 rounded-xl bg-green-100 text-green-500 flex items-center justify-center text-lg lg:text-2xl shrink-0">
        <i class="fas fa-check-circle"></i>
      </div>
      <div class="flex-1 min-w-0">
        <div class="text-xs lg:text-sm text-gray-500 mb-0.5 lg:mb-1">Sudah Dinilai</div>
        <div class="text-xl lg:text-3xl font-bold text-gray-900">{{ $sudahDinilai }}</div>
      </div>
    </div>
  </div>

  {{-- Modal State --}}
  <div x-data="{ open: false, nama: '', nim: '', nilai: '', actionUrl: '' }"
    class="rounded-xl border border-slate-200 bg-white shadow-sm">
    {{-- Header + Filter --}}
    <div class="flex flex-col gap-4 border-b border-slate-200 p-4 sm:p-5 sm:flex-row sm:items-center sm:justify-between">
      <div>
        <h2 class="text-base sm:text-lg font-semibold text-slate-800">Daftar Mahasiswa</h2>
        <p class="mt-0.5 sm:mt-1 text-xs sm:text-sm text-slate-500">Mahasiswa ujian skripsi yang perlu Anda nilai</p>
      </div>

      {{-- Filter --}}
      <form action="{{ route('dosen.nilai.index') }}" method="GET" id="filterForm"
        class="flex w-full flex-col items-stretch gap-2 sm:w-auto sm:flex-row sm:items-center sm:gap-3">
        <div class="relative flex-1 sm:flex-initial">
          <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-sm text-slate-400"></i>
          <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau NIM..."
            class="w-full rounded-lg border border-slate-200 py-2 pl-9 pr-4 text-sm text-slate-600 placeholder-slate-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
            onkeydown="if(event.key==='Enter'){event.preventDefault();document.getElementById('filterForm').submit()}" />
        </div>

        <div class="relative min-w-[150px] w-full sm:w-auto">
          <select name="peran" onchange="document.getElementById('filterForm').submit()"
            class="w-full appearance-none !bg-none rounded-lg border border-slate-200 bg-white py-2 pl-3 pr-8 text-sm text-slate-600 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
            <option value="">Semua Peran</option>
            <option value="penguji_1" @selected(request('peran') === 'penguji_1')>Penguji 1</option>
            <option value="penguji_2" @selected(request('peran') === 'penguji_2')>Penguji 2</option>
            <option value="penguji_3" @selected(request('peran') === 'penguji_3')>Penguji 3</option>
          </select>
          <i class="fas fa-chevron-down pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 text-[10px] text-slate-400"></i>
        </div>

        <div class="flex items-center gap-2">
          <button type="submit"
            class="flex-1 sm:flex-initial inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700">
            <i class="fas fa-search mr-1.5 text-xs sm:hidden"></i>Cari
          </button>
          @if (request()->hasAny(['search', 'peran']))
            <a href="{{ route('dosen.nilai.index') }}"
              class="flex-1 sm:flex-initial inline-flex items-center justify-center rounded-lg border border-slate-200 px-4 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-50">
              Reset
            </a>
          @endif
        </div>
      </form>
    </div>

    {{-- Mobile --}}
    <div class="block lg:hidden divide-y divide-slate-100">
      @forelse ($pengujiList as $index => $penguji)
        @php
          // Data card
          $mhs = $penguji->mahasiswa;
          $sudah = $penguji->nilai !== null;
          $jadwal = $penguji->jadwal;
          $labelPeran = match ($penguji->jenis_penguji) {
              'penguji_1' => 'Penguji 1',
              'penguji_2' => 'Penguji 2',
              'penguji_3' => 'Penguji 3',
              default => 'Penguji',
          };
          $statusClass = $sudah ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700';
        @endphp

        <div class="p-4">
          <div class="mb-3 flex items-center justify-between gap-2">
            <span class="text-xs font-medium text-slate-400">#{{ $pengujiList->firstItem() + $index }}</span>
            <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium {{ $statusClass }}">
              {{ $sudah ? 'Sudah Dinilai' : 'Pending' }}
            </span>
          </div>

          <div class="mb-3">
            <p class="text-sm font-semibold text-slate-800">{{ $mhs->nama_lengkap }}</p>
            <p class="text-xs text-slate-500">NIM: {{ $mhs->nim }}</p>
          </div>

          <div class="space-y-2.5">
            <div class="flex items-start gap-2 text-sm text-slate-600">
              <i class="fas fa-calendar-alt mt-0.5 w-4 shrink-0 text-slate-400"></i>
              <div>
                @if ($jadwal)
                  <p>{{ $jadwal->tanggal_ujian->translatedFormat('d M Y') }}</p>
                  <p class="text-xs text-slate-400">{{ $jadwal->jam_mulai->format('H:i') }} -
                    {{ $jadwal->jam_selesai->format('H:i') }}</p>
                @else
                  <p class="text-xs text-slate-400">Belum dijadwalkan</p>
                @endif
              </div>
            </div>

            <div class="flex items-center gap-2 text-sm text-slate-600">
              <i class="fas fa-user-shield w-4 shrink-0 text-slate-400"></i>
              <span>{{ $labelPeran }}</span>
            </div>

            <div class="flex items-center gap-2 text-sm text-slate-600">
              <i class="fas fa-star w-4 shrink-0 text-slate-400"></i>
              <span>{{ $sudah ? 'Nilai: ' . number_format($penguji->nilai, 2) : 'Nilai belum diinput' }}</span>
            </div>
          </div>

          <div class="mt-4 border-t border-slate-100 pt-3">
            <button type="button"
              data-nama="{{ $mhs->nama_lengkap }}"
              data-nim="{{ $mhs->nim }}"
              data-nilai="{{ $penguji->nilai ?? '' }}"
              data-action-url="{{ route('dosen.nilai.store', $penguji->id) }}"
              @click="nama = $el.dataset.nama; nim = $el.dataset.nim; nilai = $el.dataset.nilai; actionUrl = $el.dataset.actionUrl; open = true"
              class="inline-flex w-full items-center justify-center rounded-lg px-4 py-2.5 text-sm font-medium text-white transition {{ $sudah ? 'bg-gray-500 hover:bg-gray-600' : 'bg-blue-600 hover:bg-blue-700' }}">
              <i class="fas {{ $sudah ? 'fa-pencil-alt' : 'fa-plus' }} mr-2 text-xs"></i>
              {{ $sudah ? 'Edit Nilai' : 'Input Nilai' }}
            </button>
          </div>
        </div>
      @empty
        <div class="px-5 py-10 text-center">
          <div class="flex flex-col items-center gap-2 text-slate-500">
            <i class="fas fa-inbox text-3xl text-slate-300"></i>
            <p class="text-sm font-medium text-slate-600">Belum ada mahasiswa yang perlu dinilai.</p>
            <p class="text-xs text-slate-400">Data ujian skripsi yang menunggu nilai akan tampil di sini.</p>
          </div>
        </div>
      @endforelse
    </div>

    {{-- Desktop --}}
    <div class="hidden lg:block overflow-x-auto">
      <table class="w-full text-left text-sm">
        <thead>
          <tr class="border-b border-slate-200 bg-slate-50">
            <th class="whitespace-nowrap px-5 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">No</th>
            <th class="px-5 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">Mahasiswa</th>
            <th class="whitespace-nowrap px-5 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">Tanggal Ujian</th>
            <th class="whitespace-nowrap px-5 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">Peran</th>
            <th class="whitespace-nowrap px-5 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">Status Nilai</th>
            <th class="whitespace-nowrap px-5 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          @forelse ($pengujiList as $index => $penguji)
            @php
              $mhs = $penguji->mahasiswa;
              $sudah = $penguji->nilai !== null;
              $jadwal = $penguji->jadwal;
              $labelPeran = match ($penguji->jenis_penguji) {
                  'penguji_1' => 'Penguji 1',
                  'penguji_2' => 'Penguji 2',
                  'penguji_3' => 'Penguji 3',
                  default => 'Penguji',
              };
            @endphp

            <tr class="transition-colors hover:bg-slate-50">
              <td class="whitespace-nowrap px-5 py-4 text-slate-500">{{ $pengujiList->firstItem() + $index }}</td>
              <td class="px-5 py-4">
                <div class="font-medium text-slate-800">{{ $mhs->nama_lengkap }}</div>
                <div class="text-xs text-slate-500">{{ $mhs->nim }}</div>
              </td>
              <td class="whitespace-nowrap px-5 py-4 text-slate-500">
                @if ($jadwal)
                  <div class="text-slate-700">{{ $jadwal->tanggal_ujian->translatedFormat('d M Y') }}</div>
                  <div class="text-xs text-slate-400">{{ $jadwal->jam_mulai->format('H:i') }} -
                    {{ $jadwal->jam_selesai->format('H:i') }}</div>
                @else
                  <span class="text-xs text-slate-400">Belum dijadwalkan</span>
                @endif
              </td>
              <td class="whitespace-nowrap px-5 py-4 text-slate-600">{{ $labelPeran }}</td>
              <td class="px-5 py-4">
                @if ($sudah)
                  <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-xs font-medium text-emerald-700">
                    Sudah ({{ number_format($penguji->nilai, 2) }})
                  </span>
                @else
                  <span class="inline-flex rounded-full bg-amber-100 px-3 py-1 text-xs font-medium text-amber-700">
                    Pending
                  </span>
                @endif
              </td>
              <td class="whitespace-nowrap px-5 py-4">
                <button type="button"
                  data-nama="{{ $mhs->nama_lengkap }}"
                  data-nim="{{ $mhs->nim }}"
                  data-nilai="{{ $penguji->nilai ?? '' }}"
                  data-action-url="{{ route('dosen.nilai.store', $penguji->id) }}"
                  @click="nama = $el.dataset.nama; nim = $el.dataset.nim; nilai = $el.dataset.nilai; actionUrl = $el.dataset.actionUrl; open = true"
                  class="inline-flex items-center rounded-lg px-3 py-1.5 text-xs font-medium text-white transition {{ $sudah ? 'bg-gray-500 hover:bg-gray-600' : 'bg-blue-600 hover:bg-blue-700' }}">
                  {{ $sudah ? 'Edit Nilai' : 'Input Nilai' }}
                </button>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="px-5 py-10 text-center">
                <div class="flex flex-col items-center gap-2 text-slate-500">
                  <i class="fas fa-inbox text-3xl text-slate-300"></i>
                  <p class="text-sm font-medium text-slate-600">Belum ada mahasiswa yang perlu dinilai.</p>
                  <p class="text-sm text-slate-400">Data ujian skripsi yang menunggu nilai akan tampil di tabel ini.</p>
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- Pagination --}}
    @if ($pengujiList->hasPages())
      <div class="flex flex-col items-center justify-between gap-3 border-t border-slate-200 px-4 py-4 sm:flex-row sm:px-5">
        {{ $pengujiList->links() }}
      </div>
    @endif

    {{-- Modal --}}
    <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4"
      @click.self="open = false" x-cloak>
      <div class="max-h-[90vh] w-full max-w-lg overflow-y-auto rounded-xl bg-white shadow-2xl">
        <div class="flex items-center justify-between border-b px-4 py-4 sm:px-6 sm:py-5">
          <h3 class="text-lg font-semibold text-gray-900 sm:text-xl">Input Nilai Mahasiswa</h3>
          <button type="button" @click="open = false" class="text-2xl text-gray-400 hover:text-gray-600">&times;</button>
        </div>

        <div class="px-4 py-5 sm:px-6 sm:py-6">
          <div class="mb-6 space-y-3 rounded-lg bg-gray-50 p-4">
            <div class="flex flex-col gap-1 text-sm sm:flex-row sm:items-center sm:justify-between">
              <span class="text-gray-500">Nama:</span>
              <span x-text="nama" class="font-medium text-gray-900 sm:text-right"></span>
            </div>
            <div class="flex flex-col gap-1 text-sm sm:flex-row sm:items-center sm:justify-between">
              <span class="text-gray-500">NIM:</span>
              <span x-text="nim" class="font-medium text-gray-900 sm:text-right"></span>
            </div>
            <div class="flex flex-col gap-1 text-sm sm:flex-row sm:items-center sm:justify-between">
              <span class="text-gray-500">Jenis Ujian:</span>
              <span class="font-medium text-gray-900 sm:text-right">Skripsi</span>
            </div>
          </div>

          {{-- Form Submit --}}
          <form method="POST" :action="actionUrl">
            @csrf
            <div class="mb-5">
              <label class="mb-1.5 block text-sm font-medium text-gray-700">Nilai (0-100)</label>
              <input type="number" name="nilai" min="0" max="100" step="0.01" required :value="nilai"
                class="w-full rounded-md border border-gray-300 px-3 py-3 focus:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-100"
                placeholder="Masukkan nilai..." />
            </div>

            <div class="flex flex-col-reverse gap-3 pt-2 sm:flex-row sm:justify-end">
              <button type="button" @click="open = false"
                class="rounded-md bg-gray-100 px-4 py-2 text-sm text-gray-700 hover:bg-gray-200">
                Batal
              </button>
              <button type="submit" class="rounded-md bg-blue-600 px-4 py-2 text-sm text-white hover:bg-blue-700">
                Simpan Nilai
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
