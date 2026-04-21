@extends('layouts.app')

@section('title', 'Undangan Ujian')

@section('sidebar')
  @if (auth()->user()->role === 'kajur')
    @include('kajur.sidebar')
  @else
    @include('dosen.sidebar')
  @endif
@endsection

@section('content')
  {{-- Page Header --}}
  <div class="bg-gradient-to-br from-blue-800 to-blue-500 rounded-2xl p-5 sm:p-8 mb-6 text-white">
    <div class="flex justify-between items-center flex-wrap gap-4">
      <div>
        <h1 class="text-xl sm:text-[1.75rem] font-bold mb-1 sm:mb-2">
          <i class="fas fa-envelope-open-text mr-2 sm:mr-3"></i>Undangan Ujian
        </h1>
        <p class="opacity-90 text-sm sm:text-[0.95rem]">Kelola undangan ujian sebagai pembimbing maupun penguji</p>
      </div>
    </div>
  </div>

  {{-- Stats --}}
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-3 lg:gap-6 mb-6">
    <div
      class="bg-white rounded-xl p-4 lg:p-6 shadow-sm flex items-center gap-3 lg:gap-4 hover:-translate-y-0.5 hover:shadow-md transition-all">
      <div
        class="w-10 h-10 lg:w-14 lg:h-14 rounded-xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-lg lg:text-2xl shrink-0">
        <i class="fas fa-envelope-open-text"></i>
      </div>
      <div class="flex-1 min-w-0">
        <div class="text-xs lg:text-sm text-gray-500 mb-0.5 lg:mb-1">Total Undangan</div>
        <div class="text-xl lg:text-3xl font-bold text-gray-900">{{ $undangan->total() }}</div>
      </div>
    </div>
    <div
      class="bg-white rounded-xl p-4 lg:p-6 shadow-sm flex items-center gap-3 lg:gap-4 hover:-translate-y-0.5 hover:shadow-md transition-all">
      <div
        class="w-10 h-10 lg:w-14 lg:h-14 rounded-xl bg-green-100 text-green-600 flex items-center justify-center text-lg lg:text-2xl shrink-0">
        <i class="fas fa-user-check"></i>
      </div>
      <div class="flex-1 min-w-0">
        <div class="text-xs lg:text-sm text-gray-500 mb-0.5 lg:mb-1">Sebagai Pembimbing</div>
        <div class="text-xl lg:text-3xl font-bold text-gray-900">
          {{ $undangan->getCollection()->filter(fn($u) => str_starts_with($u->peran, 'Pembimbing'))->count() }}
        </div>
      </div>
    </div>
    <div
      class="bg-white rounded-xl p-4 lg:p-6 shadow-sm flex items-center gap-3 lg:gap-4 hover:-translate-y-0.5 hover:shadow-md transition-all">
      <div
        class="w-10 h-10 lg:w-14 lg:h-14 rounded-xl bg-orange-100 text-orange-500 flex items-center justify-center text-lg lg:text-2xl shrink-0">
        <i class="fas fa-user-shield"></i>
      </div>
      <div class="flex-1 min-w-0">
        <div class="text-xs lg:text-sm text-gray-500 mb-0.5 lg:mb-1">Sebagai Penguji</div>
        <div class="text-xl lg:text-3xl font-bold text-gray-900">
          {{ $undangan->getCollection()->filter(fn($u) => str_starts_with($u->peran, 'Penguji'))->count() }}
        </div>
      </div>
    </div>
  </div>

  <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
    <div class="flex flex-col gap-4 border-b border-slate-200 p-4 sm:p-5 lg:flex-row lg:items-center lg:justify-between">
      <div>
        <h2 class="text-base sm:text-lg font-semibold text-slate-800">Daftar Undangan</h2>
        <p class="mt-0.5 sm:mt-1 text-xs sm:text-sm text-slate-500">Undangan sebagai pembimbing dan penguji</p>
      </div>

      <form method="GET" action="{{ route('dosen.undangan.index') }}" id="filterForm"
        class="flex w-full flex-col items-stretch gap-2 sm:flex-row sm:items-center lg:w-auto">
        <div class="relative flex-1 sm:flex-initial">
          <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-sm text-slate-400"></i>
          <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau NIM..."
            class="w-full rounded-lg border border-slate-200 py-2 pl-9 pr-4 text-sm text-slate-600 placeholder-slate-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
            onkeydown="if(event.key==='Enter'){event.preventDefault();document.getElementById('filterForm').submit()}" />
        </div>

        <div class="relative min-w-[150px] w-full sm:w-auto">
          <select name="jenis" onchange="document.getElementById('filterForm').submit()"
            class="w-full appearance-none !bg-none rounded-lg border border-slate-200 bg-white py-2 pl-3 pr-8 text-sm text-slate-600 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
            <option value="">Semua Jenis</option>
            <option value="proposal" @selected(request('jenis') === 'proposal')>Ujian Proposal</option>
            <option value="hasil" @selected(request('jenis') === 'hasil')>Ujian Hasil</option>
            <option value="skripsi" @selected(request('jenis') === 'skripsi')>Ujian Skripsi</option>
          </select>
          <i class="fas fa-chevron-down pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 text-[10px] text-slate-400"></i>
        </div>

        <div class="relative min-w-[150px] w-full sm:w-auto">
          <select name="peran" onchange="document.getElementById('filterForm').submit()"
            class="w-full appearance-none !bg-none rounded-lg border border-slate-200 bg-white py-2 pl-3 pr-8 text-sm text-slate-600 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
            <option value="">Semua Peran</option>
            <option value="pembimbing" @selected(request('peran') === 'pembimbing')>Pembimbing</option>
            <option value="penguji" @selected(request('peran') === 'penguji')>Penguji</option>
          </select>
          <i class="fas fa-chevron-down pointer-events-none absolute right-2.5 top-1/2 -translate-y-1/2 text-[10px] text-slate-400"></i>
        </div>

        <div class="flex items-center gap-2">
          <button type="submit"
            class="flex-1 sm:flex-initial inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700">
            <i class="fas fa-search mr-1.5 text-xs sm:hidden"></i>Cari
          </button>
          @if (request()->hasAny(['search', 'jenis', 'peran']))
            <a href="{{ route('dosen.undangan.index') }}"
              class="flex-1 sm:flex-initial inline-flex items-center justify-center rounded-lg border border-slate-200 px-4 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-50">
              Reset
            </a>
          @endif
        </div>
      </form>
    </div>

    <div class="space-y-4 bg-slate-50/60 p-4 sm:space-y-6 sm:p-5">
      @forelse ($undangan as $item)
        @php
          $ujian = $item->ujian;
          $ta = $ujian->tugasAkhir;
          $mhs = $ta->mahasiswa;
          $jadwal = $ujian->jadwalUjian;

          $badgeClass = match ($ujian->jenis_ujian) {
              'proposal' => 'bg-blue-100 text-blue-700',
              'hasil' => 'bg-yellow-100 text-yellow-700',
              'skripsi' => 'bg-green-100 text-green-700',
              default => 'bg-gray-100 text-gray-700',
          };
        @endphp

        <div
          class="bg-white rounded-2xl p-4 sm:p-6 shadow-sm border border-gray-200 transition-all duration-300 hover:shadow-xl hover:-translate-y-[3px] hover:border-blue-200">

          {{-- Card Header: Judul + Badge --}}
          <div class="flex items-start justify-between mb-3 sm:mb-4 gap-2 flex-wrap">
            <div class="text-sm sm:text-[1.05rem] font-bold text-gray-900 leading-snug min-w-0">
              Ujian Seminar {{ ucfirst($ujian->jenis_ujian) }} - {{ $mhs->nama_lengkap }}
            </div>
            <span
              class="inline-flex items-center gap-1.5 px-2.5 py-1 sm:px-3 sm:py-1.5 rounded-full text-xs font-semibold shrink-0 {{ $badgeClass }}">
              <i class="fas fa-circle-dot text-[0.6rem]"></i>
              {{ ucfirst($ujian->jenis_ujian) }}
            </span>
          </div>

          {{-- NIM & Judul --}}
          <div class="flex items-center gap-2 text-xs sm:text-[0.85rem] text-gray-600 mb-1.5 sm:mb-2">
            <i class="fas fa-id-card text-blue-500 w-4 shrink-0"></i>
            NIM: {{ $mhs->nim }}
          </div>
          <div class="flex items-start gap-2 text-xs sm:text-[0.85rem] text-gray-600 mb-1.5 sm:mb-2">
            <i class="fas fa-book-open text-blue-500 w-4 shrink-0 mt-0.5"></i>
            <span class="leading-snug">{{ $ta->judul }}</span>
          </div>

          {{-- Jadwal Section --}}
          <div class="border-t border-gray-100 pt-3 sm:pt-4 mt-3 sm:mt-4 grid grid-cols-1 sm:grid-cols-2 gap-1.5 sm:gap-2">
            @if ($jadwal)
              <div class="flex items-center gap-2 text-xs sm:text-[0.85rem] text-gray-600">
                <i class="fas fa-calendar-alt text-blue-500 w-4 shrink-0"></i>
                {{ $jadwal->tanggal_ujian->format('d M Y') }}
              </div>
              <div class="flex items-center gap-2 text-xs sm:text-[0.85rem] text-gray-600">
                <i class="fas fa-location-dot text-blue-500 w-4 shrink-0"></i>
                {{ $jadwal->ruangan }}
              </div>
              <div class="flex items-center gap-2 text-xs sm:text-[0.85rem] text-gray-600">
                <i class="fas fa-clock text-blue-500 w-4 shrink-0"></i>
                {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} -
                {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }} WITA
              </div>
              <div class="flex items-center gap-2 text-xs sm:text-[0.85rem] text-gray-600">
                <i class="fas fa-user-check text-blue-500 w-4 shrink-0"></i>
                Peran: {{ $item->peran }}
              </div>
            @else
              <div class="col-span-full flex items-center gap-2 text-xs sm:text-sm text-gray-400 italic">
                <i class="fas fa-calendar-xmark w-4 shrink-0"></i>
                Jadwal ujian belum ditetapkan
              </div>
            @endif
          </div>

          {{-- File PDF --}}
          <div
            class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-gradient-to-br from-slate-50 to-slate-100 border border-slate-200 rounded-xl p-3 sm:p-4 mt-4">
            <div class="flex items-center gap-2 sm:gap-3 text-xs sm:text-[0.875rem] text-gray-700 font-medium min-w-0">
              <i class="fas fa-file-pdf text-red-600 text-lg sm:text-xl shrink-0"></i>
              <span class="truncate">{{ basename($item->file_path) }}</span>
            </div>
            <div class="flex gap-2 shrink-0">
              <a href="{{ route('files.view', ['type' => 'undangan-ujian', 'id' => $item->id]) }}" target="_blank"
                class="flex-1 sm:flex-none inline-flex items-center justify-center gap-1.5 px-3 sm:px-4 py-2 rounded-lg text-xs sm:text-[0.8rem] font-semibold border border-blue-200 text-blue-600 bg-white hover:bg-blue-600 hover:text-white hover:border-blue-600 transition-all duration-200">
                <i class="fas fa-eye"></i>
                <span>Lihat</span>
              </a>
              <a href="{{ route('files.download', ['type' => 'undangan-ujian', 'id' => $item->id]) }}"
                class="flex-1 sm:flex-none inline-flex items-center justify-center gap-1.5 px-3 sm:px-4 py-2 rounded-lg text-xs sm:text-[0.8rem] font-semibold border border-blue-200 text-blue-600 bg-white hover:bg-blue-600 hover:text-white hover:border-blue-600 transition-all duration-200">
                <i class="fas fa-download"></i>
                <span>Unduh</span>
              </a>
            </div>
          </div>

        </div>
      @empty
        <div class="bg-white rounded-2xl p-8 shadow-sm border border-dashed border-gray-300 text-center text-gray-400 flex flex-col items-center">
          <i class="fas fa-envelope-open text-4xl mb-3 block"></i>
          <p class="text-sm">Belum ada undangan ujian untuk Anda.</p>
        </div>
      @endforelse
    </div>

    <div class="flex flex-col items-center justify-between gap-3 border-t border-slate-200 px-4 py-4 sm:flex-row sm:px-5">
      {{ $undangan->links('pagination::tailwind') }}
    </div>
  </div>
@endsection
