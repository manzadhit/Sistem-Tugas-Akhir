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

  {{-- Card --}}
  <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
    <div class="p-5 border-b border-gray-200 flex flex-wrap items-center justify-between gap-4">
      <h3 class="text-lg font-semibold text-gray-900">Daftar Mahasiswa</h3>
      <form action="{{ route('dosen.nilai.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
        <div class="relative">
          <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
          <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau NIM..."
            class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm w-full md:w-56 focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600/10 transition-colors" />
        </div>
        <div class="relative">
          <select name="peran" onchange="this.form.submit()"
            class="appearance-none pl-4 pr-9 py-2 border border-gray-300 rounded-lg text-sm bg-white cursor-pointer focus:outline-none focus:border-blue-600">
            <option value="">Semua Peran</option>
            <option value="penguji_1" @selected(request('peran') === 'penguji_1')>Penguji 1</option>
            <option value="penguji_2" @selected(request('peran') === 'penguji_2')>Penguji 2</option>
            <option value="penguji_3" @selected(request('peran') === 'penguji_3')>Penguji 3</option>
          </select>
          <i
            class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
        </div>
        @if (request()->hasAny(['search', 'peran']))
          <a href="{{ route('dosen.nilai.index') }}" title="Reset Filter"
            class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-gray-100 text-gray-500 hover:bg-gray-200 transition-all">
            <i class="fas fa-times text-xs"></i>
          </a>
        @endif
      </form>
    </div>

    <div x-data="{ open: false, nama: '', nim: '', nilai: '', actionUrl: '' }">
      <div class="overflow-x-auto">
        <table class="w-full border-collapse">
          <thead>
            <tr>
              <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase bg-gray-50 border-b">Mahasiswa
              </th>
              <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase bg-gray-50 border-b">Tanggal
                Ujian</th>
              <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase bg-gray-50 border-b">Peran</th>
              <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase bg-gray-50 border-b">Status
                Nilai</th>
              <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase bg-gray-50 border-b">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($pengujiList as $penguji)
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
              <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 border-b text-sm">
                  {{ $mhs->nama_lengkap }}<br>
                  <span class="text-gray-500 text-xs">{{ $mhs->nim }}</span>
                </td>
                <td class="px-6 py-4 border-b text-sm whitespace-nowrap">
                  @if ($jadwal)
                    <span class="block text-gray-800">{{ $jadwal->tanggal_ujian->translatedFormat('d M Y') }}</span>
                    <span class="text-gray-500 text-xs">{{ $jadwal->jam_mulai->format('H:i') }} –
                      {{ $jadwal->jam_selesai->format('H:i') }}</span>
                  @else
                    <span class="text-gray-400 text-xs">Belum dijadwalkan</span>
                  @endif
                </td>
                <td class="px-6 py-4 border-b text-sm">{{ $labelPeran }}</td>
                <td class="px-6 py-4 border-b">
                  @if ($sudah)
                    <span
                      class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-600">
                      Sudah ({{ number_format($penguji->nilai, 2) }})
                    </span>
                  @else
                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-600">
                      Pending
                    </span>
                  @endif
                </td>
                <td class="px-6 py-4 border-b">
                  <button type="button"
                    data-nama="{{ $mhs->nama_lengkap }}"
                    data-nim="{{ $mhs->nim }}"
                    data-nilai="{{ $penguji->nilai ?? '' }}"
                    data-action-url="{{ route('dosen.nilai.store', $penguji->id) }}"
                    @click="nama = $el.dataset.nama; nim = $el.dataset.nim; nilai = $el.dataset.nilai; actionUrl = $el.dataset.actionUrl; open = true"
                    class="px-3 py-2 {{ $sudah ? 'bg-gray-500 hover:bg-gray-600' : 'bg-blue-600 hover:bg-blue-700' }} text-white rounded-md text-sm transition-colors">
                    {{ $sudah ? 'Edit Nilai' : 'Input Nilai' }}
                  </button>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="px-6 py-10 text-center text-gray-400 text-sm">
                  <i class="fas fa-inbox text-3xl mb-2 block"></i>
                  Tidak ada mahasiswa ujian skripsi yang perlu dinilai.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      @if ($pengujiList->hasPages())
        <div class="px-5 py-4 border-t border-gray-100">
          {{ $pengujiList->links() }}
        </div>
      @endif

      {{-- Modal --}}
      <div x-show="open" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4"
        @click.self="open = false" x-cloak>
        <div class="bg-white rounded-xl w-full max-w-lg shadow-2xl">

          <div class="flex justify-between items-center px-6 py-5 border-b">
            <h3 class="text-xl font-semibold text-gray-900">Input Nilai Mahasiswa</h3>
            <button type="button" @click="open = false"
              class="text-gray-400 hover:text-gray-600 text-2xl">&times;</button>
          </div>

          <div class="px-6 py-6">
            <div class="bg-gray-50 rounded-lg p-4 mb-6 space-y-2">
              <div class="flex justify-between text-sm">
                <span class="text-gray-500">Nama:</span>
                <span x-text="nama" class="font-medium text-gray-900"></span>
              </div>
              <div class="flex justify-between text-sm">
                <span class="text-gray-500">NIM:</span>
                <span x-text="nim" class="font-medium text-gray-900"></span>
              </div>
              <div class="flex justify-between text-sm">
                <span class="text-gray-500">Jenis Ujian:</span>
                <span class="font-medium text-gray-900">Skripsi</span>
              </div>
            </div>

            <form method="POST" :action="actionUrl">
              @csrf
              <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Nilai (0-100)</label>
                <input type="number" name="nilai" min="0" max="100" step="0.01" required
                  :value="nilai"
                  class="w-full px-3 py-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-600"
                  placeholder="Masukkan nilai..." />
              </div>

              <div class="flex justify-end gap-3 pt-2">
                <button type="button" @click="open = false"
                  class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 text-sm">
                  Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                  Simpan Nilai
                </button>
              </div>
            </form>
          </div>

        </div>
      </div>
    </div>
  </div>
@endsection
