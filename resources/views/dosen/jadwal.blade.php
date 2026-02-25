@extends('layouts.app')

@section('title', 'Jadwal Ujian')

@section('sidebar')
  @include('dosen.sidebar')
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

  {{-- Filter Section: .filter-section --}}
  <div class="bg-white rounded-xl p-6 shadow-sm mb-6">
    {{-- .filter-grid: grid, auto-fit cols --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
      {{-- .filter-group --}}
      <div class="flex flex-col gap-2">
        <label class="text-sm font-medium text-gray-700">Cari Mahasiswa</label>
        <input type="text"
          class="px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-700 transition-all focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600/10"
          placeholder="Nama atau NIM..." />
      </div>
      <div class="flex flex-col gap-2">
        <label class="text-sm font-medium text-gray-700">Jenis Ujian</label>
        <select
          class="px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-700 transition-all focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600/10">
          <option value="">Semua Jenis</option>
          <option value="proposal">Ujian Proposal</option>
          <option value="hasil">Ujian Hasil</option>
          <option value="skripsi">Ujian Skripsi</option>
        </select>
      </div>
      <div class="flex flex-col gap-2">
        <label class="text-sm font-medium text-gray-700">Peran</label>
        <select
          class="px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-700 transition-all focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600/10">
          <option value="">Semua Peran</option>
          <option value="pembimbing">Pembimbing</option>
          <option value="penguji">Penguji</option>
        </select>
      </div>
      {{-- .btn.btn-primary --}}
      <button
        class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-lg text-sm font-medium bg-blue-600 text-white hover:bg-blue-700 transition-all cursor-pointer">
        <i class="fas fa-filter"></i>
        Terapkan Filter
      </button>
    </div>
  </div>

  {{-- Schedule List --}}
  <div class="space-y-4">
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

        // Badge countdown
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

      {{-- .schedule-card + .today (border-l-4 blue jika hari ini) --}}
      <div @class([
          'bg-white rounded-xl p-4 sm:p-6 shadow-sm transition-all hover:shadow-md border-l-4',
          'border-l-blue-600' => $isHariIni,
          'border-l-transparent opacity-70' => $isLewat,
          'border-l-transparent' => !$isHariIni && !$isLewat,
      ])>
        {{-- .schedule-header: flex, sm:flex-row, gap --}}
        <div class="flex flex-col sm:flex-row sm:items-start gap-4 mb-4">

          {{-- .schedule-date: bg-blue-50, min-w-[80px] --}}
          <div
            class="flex sm:flex-col items-center justify-center bg-blue-50 px-4 py-2 rounded-lg text-blue-600 sm:min-w-[80px] gap-2 sm:gap-0">
            {{-- .date-day --}}
            <span class="text-2xl font-bold leading-none">{{ $item->tanggal_ujian->format('d') }}</span>
            {{-- .date-month --}}
            <span class="text-sm font-medium">{{ $item->tanggal_ujian->format('M Y') }}</span>
          </div>

          {{-- .schedule-info: flex-1 --}}
          <div class="flex-1 min-w-0">
            <div class="flex flex-wrap items-start justify-between gap-2 mb-1">
              <div class="min-w-0">
                <h3 class="text-base sm:text-lg font-semibold text-gray-900">{{ $jenisLabel }}</h3>
                <p class="text-sm sm:text-[0.95rem] text-gray-600">
                  {{ $mhs->nama_lengkap }} ({{ $mhs->nim }})
                </p>
              </div>
              <div class="flex items-center gap-2 shrink-0 flex-wrap justify-end">
                {{-- Badge countdown --}}
                <span
                  class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold {{ $countdownClass }}">
                  <i class="{{ $countdownIcon }} text-[0.6rem]"></i>
                  {{ $countdownText }}
                </span>
                {{-- Badge peran --}}
                <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $roleClass }}">
                  {{ $item->peran }}
                </span>
              </div>
            </div>

            {{-- .schedule-meta --}}
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

            {{-- Judul TA --}}
            <p class="text-xs text-gray-400 mt-2 line-clamp-1">
              <i class="fas fa-book-open mr-1"></i>{{ $ta->judul }}
            </p>
          </div>
        </div>
      </div>
    @empty
      <div class="bg-white rounded-xl p-10 shadow-sm border border-dashed border-gray-300 text-center text-gray-400">
        <i class="fas fa-calendar-xmark text-4xl mb-3 block"></i>
        <p class="text-sm">Belum ada jadwal ujian untuk Anda.</p>
      </div>
    @endforelse
  </div>

  <div class="mt-4">
    {{ $jadwals->links('pagination::tailwind') }}
  </div>
@endsection
