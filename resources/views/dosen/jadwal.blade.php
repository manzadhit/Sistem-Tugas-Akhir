@extends('layouts.app')

@section('title', 'Jadwal Ujian')

@section('sidebar')
  @if (auth()->user()->role === 'kajur')
    @include('kajur.sidebar')
  @else
    @include('dosen.sidebar')
  @endif
@endsection

@section('content')
  <div class="bg-gradient-to-br from-blue-800 to-blue-500 rounded-2xl p-5 sm:p-8 mb-6 text-white">
    <div class="flex justify-between items-center flex-wrap gap-4">
      <div>
        <h1 class="text-xl sm:text-[1.75rem] font-bold mb-1 sm:mb-2">
          <i class="fas fa-calendar-alt mr-2 sm:mr-3"></i>Jadwal Ujian
        </h1>
        <p class="opacity-90 text-sm sm:text-[0.95rem]">Lihat jadwal ujian mahasiswa yang akan datang</p>
      </div>
    </div>
  </div>

  <div class="grid grid-cols-2 gap-3 lg:gap-6 mb-6">
    <div
      class="bg-white rounded-xl p-4 lg:p-6 shadow-sm flex items-center gap-3 lg:gap-4 hover:-translate-y-0.5 hover:shadow-md transition-all">
      <div
        class="w-10 h-10 lg:w-14 lg:h-14 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center text-lg lg:text-2xl shrink-0">
        <i class="fas fa-calendar-alt"></i>
      </div>
      <div class="flex-1 min-w-0">
        <div class="text-xs lg:text-sm text-gray-500 mb-0.5 lg:mb-1">Total Jadwal</div>
        <div class="text-xl lg:text-3xl font-bold text-gray-900">{{ $jadwals->total() }}</div>
      </div>
    </div>
    <div
      class="bg-white rounded-xl p-4 lg:p-6 shadow-sm flex items-center gap-3 lg:gap-4 hover:-translate-y-0.5 hover:shadow-md transition-all">
      <div
        class="w-10 h-10 lg:w-14 lg:h-14 rounded-xl bg-orange-100 text-orange-500 flex items-center justify-center text-lg lg:text-2xl shrink-0">
        <i class="fas fa-clock"></i>
      </div>
      <div class="flex-1 min-w-0">
        <div class="text-xs lg:text-sm text-gray-500 mb-0.5 lg:mb-1">Akan Datang</div>
        <div class="text-xl lg:text-3xl font-bold text-gray-900">
          {{ $jadwals->getCollection()->filter(fn($j) => $j->tanggal_ujian->isFuture() || $j->tanggal_ujian->isToday())->count() }}
        </div>
      </div>
    </div>
  </div>

  <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
    <div class="flex flex-col gap-4 border-b border-slate-200 p-4 sm:p-5 lg:flex-row lg:items-center lg:justify-between">
      <div>
        <h2 class="text-base sm:text-lg font-semibold text-slate-800">Daftar Jadwal</h2>
        <p class="mt-0.5 sm:mt-1 text-xs sm:text-sm text-slate-500">Jadwal ujian mahasiswa sebagai pembimbing maupun penguji</p>
      </div>

      <form method="GET" action="{{ route('dosen.jadwal.index') }}" id="filterForm"
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
            <a href="{{ route('dosen.jadwal.index') }}"
              class="flex-1 sm:flex-initial inline-flex items-center justify-center rounded-lg border border-slate-200 px-4 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-50">
              Reset
            </a>
          @endif
        </div>
      </form>
    </div>

    <div class="space-y-4 bg-slate-50/60 p-4 sm:space-y-6 sm:p-5">
      @forelse ($jadwals as $item)
        @php
          $ujian = $item->ujian;
          $ta = $ujian->tugasAkhir;
          $mhs = $ta->mahasiswa;
          $isHariIni = $item->tanggal_ujian->isToday();
          $isLewat = $item->tanggal_ujian->isPast() && !$isHariIni;
          $isBesok = $item->tanggal_ujian->isTomorrow();
          $sisaHari = (int) now()
              ->startOfDay()
              ->diffInDays($item->tanggal_ujian->startOfDay(), false);

          $jenisLabel = match ($ujian->jenis_ujian) {
              'proposal' => 'Ujian Seminar Proposal',
              'hasil' => 'Ujian Seminar Hasil',
              'skripsi' => 'Ujian Seminar Skripsi',
              default => 'Ujian Seminar ' . ucfirst($ujian->jenis_ujian),
          };

          $roleClass = str_starts_with($item->peran, 'Pembimbing')
              ? 'bg-green-100 text-green-800'
              : 'bg-yellow-100 text-yellow-800';

          if ($isHariIni) {
              $countdownText = 'Hari Ini';
              $countdownClass = 'bg-green-100 text-green-700 border border-green-200';
              $countdownIcon = 'fas fa-circle text-green-500 text-[0.5rem] animate-pulse';
          } elseif ($isBesok) {
              $countdownText = 'Besok';
              $countdownClass = 'bg-blue-100 text-blue-700 border border-blue-200';
              $countdownIcon = 'fas fa-clock';
          } elseif ($isLewat) {
              $countdownText = 'Selesai';
              $countdownClass = 'bg-gray-100 text-gray-400 border border-gray-200';
              $countdownIcon = 'fas fa-check-circle';
          } else {
              $countdownText = $sisaHari . ' hari lagi';
              $countdownClass = 'bg-orange-50 text-orange-600 border border-orange-200';
              $countdownIcon = 'fas fa-hourglass-half';
          }
        @endphp

        <div @class([
            'bg-white rounded-xl p-4 sm:p-6 shadow-sm transition-all hover:shadow-md border-l-4',
            'border-l-blue-600' => $isHariIni,
            'border-l-transparent opacity-70' => $isLewat,
            'border-l-transparent' => !$isHariIni && !$isLewat,
        ])>
          <div class="flex flex-col sm:flex-row sm:items-start gap-4 mb-4">
            <div
              class="flex sm:flex-col items-center justify-center bg-blue-50 px-4 py-2 rounded-lg text-blue-600 sm:min-w-[80px] gap-2 sm:gap-0">
              <span class="text-2xl font-bold leading-none">{{ $item->tanggal_ujian->format('d') }}</span>
              <span class="text-sm font-medium">{{ $item->tanggal_ujian->format('M Y') }}</span>
            </div>

            <div class="flex-1 min-w-0">
              <div class="flex flex-wrap items-start justify-between gap-2 mb-1">
                <div class="min-w-0">
                  <h3 class="text-base sm:text-lg font-semibold text-gray-900">{{ $jenisLabel }}</h3>
                  <p class="text-sm sm:text-[0.95rem] text-gray-600">
                    {{ $mhs->nama_lengkap }} ({{ $mhs->nim }})
                  </p>
                </div>
                <div class="flex items-center gap-2 shrink-0 flex-wrap justify-end">
                  <span
                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold {{ $countdownClass }}">
                    <i class="{{ $countdownIcon }} text-[0.6rem]"></i>
                    {{ $countdownText }}
                  </span>
                  <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $roleClass }}">
                    {{ $item->peran }}
                  </span>
                </div>
              </div>

              <div class="flex flex-wrap gap-3 sm:gap-6 text-sm text-gray-500 mt-2">
                <div class="flex items-center gap-2">
                  <i class="fas fa-clock text-gray-400"></i>
                  <span>
                    {{ \Carbon\Carbon::parse($item->jam_mulai)->format('H:i') }} -
                    {{ \Carbon\Carbon::parse($item->jam_selesai)->format('H:i') }} WITA
                  </span>
                </div>
                <div class="flex items-center gap-2">
                  <i class="fas fa-map-marker-alt text-gray-400"></i>
                  <span>{{ $item->ruangan }}</span>
                </div>
              </div>

              <p class="text-xs text-gray-400 mt-2 line-clamp-1">
                <i class="fas fa-book-open mr-1"></i>{{ $ta->judul }}
              </p>
            </div>
          </div>
        </div>
      @empty
        <div
          class="bg-white rounded-xl p-10 shadow-sm border border-dashed border-gray-300 text-center text-gray-400 flex flex-col items-center">
          <i class="fas fa-calendar-xmark text-4xl mb-3 block"></i>
          <p class="text-sm">Belum ada jadwal ujian untuk Anda.</p>
        </div>
      @endforelse
    </div>

    <div class="flex flex-col items-center justify-between gap-3 border-t border-slate-200 px-4 py-4 sm:flex-row sm:px-5">
      {{ $jadwals->links('pagination::tailwind') }}
    </div>
  </div>
@endsection
