@extends('layouts.app')

@section('title', 'Daftar Pengujian')

@section('sidebar')
  @include('dosen.sidebar')
@endsection

@section('content')
  <!-- Page Header -->
  <div class="mb-8">
    <nav class="flex items-center gap-2 text-sm text-gray-500 mb-3">
      <a href="{{ route('dosen.dashboard') }}" class="hover:text-gray-700 transition-colors">Dashboard</a>
      <i class="fas fa-chevron-right text-xs text-gray-400"></i>
      <span class="text-gray-900 font-medium">Daftar Pengujian</span>
    </nav>
    <div class="flex items-center gap-3 mb-4">
      <a href="{{ route('dosen.dashboard') }}" class="text-gray-500 hover:text-gray-700 transition-colors">
        <i class="fas fa-arrow-left"></i>
      </a>
      <h1 class="text-3xl font-bold text-gray-900">Daftar Pengujian</h1>
    </div>
    <p class="text-base text-gray-500">
      Daftar mahasiswa yang Anda uji
      @if ($selectedPeriode)
        pada periode <span class="font-medium text-gray-700">{{ $selectedPeriode->tahun_ajaran }} — Semester {{ $selectedPeriode->semester }}</span>
      @endif
    </p>
  </div>

  <!-- Search & Filter -->
  <div class="bg-white rounded-xl p-4 sm:p-6 shadow-sm mb-6">
    <form method="GET" action="{{ route('dosen.pengujian.index') }}" id="filterForm">
      <div class="flex flex-wrap gap-3 items-end">
        <div class="flex flex-col gap-2 flex-1 min-w-48">
          <label class="text-sm font-medium text-gray-700">Cari Mahasiswa</label>
          <input type="text" name="search" value="{{ request('search') }}"
            class="px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-700 transition-all focus:outline-none focus:border-violet-600 focus:ring-2 focus:ring-violet-600/10"
            placeholder="Nama atau NIM..."
            onkeydown="if(event.key==='Enter'){event.preventDefault();document.getElementById('filterForm').submit()}" />
        </div>
        <div class="flex flex-col gap-2 flex-1 w-full sm:w-64">
          <label class="text-sm font-medium text-gray-700">Periode Akademik</label>
          <select name="periode_akademik_id"
            class="px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-700 transition-all focus:outline-none focus:border-violet-600 focus:ring-2 focus:ring-violet-600/10 bg-white"
            onchange="document.getElementById('filterForm').submit()">
            @foreach ($semuaPeriode as $periode)
              <option value="{{ $periode->id }}" {{ $selectedPeriodeId == $periode->id ? 'selected' : '' }}>
                {{ $periode->tahun_ajaran }} — Semester {{ $periode->semester }}
                @if ($periode->status === 'aktif') (Aktif) @endif
              </option>
            @endforeach
          </select>
        </div>
        @if (request('search') || request('periode_akademik_id'))
          <div class="flex flex-col justify-end">
            <a href="{{ route('dosen.pengujian.index') }}"
              class="w-10 h-10 flex items-center justify-center rounded-lg border border-gray-300 text-gray-500 hover:bg-gray-100 hover:text-gray-700 transition-colors"
              title="Reset filter">
              <i class="fas fa-times"></i>
            </a>
          </div>
        @endif
      </div>
    </form>
  </div>

  <!-- Mobile Cards -->
  <div class="space-y-3 lg:hidden">
    @forelse ($daftarPengujian as $item)
      @php($ujians = $item->mahasiswa?->tugasAkhir?->ujian ?? collect())
      <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
        <div class="flex items-start justify-between gap-3 mb-3">
          <div class="min-w-0">
            <div class="text-sm font-semibold text-gray-900 truncate">{{ $item->mahasiswa->nama_lengkap }}</div>
            <div class="text-xs text-gray-500">NIM: {{ $item->mahasiswa->nim }}</div>
          </div>
          <span class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold bg-violet-100 text-violet-800 shrink-0">
            {{ $item->getJenisPenguji() }}
          </span>
        </div>
        <div class="space-y-1.5 text-xs sm:text-sm">
          <div>
            <div class="text-xs text-gray-400">Tahapan</div>
            <div class="text-gray-700">
              <div class="flex flex-wrap gap-1.5">
                @foreach ($ujians as $ujian)
                  <span class="inline-block px-2 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                    {{ ucfirst($ujian->jenis_ujian) }}
                  </span>
                @endforeach
              </div>
            </div>
          </div>
          <div>
            <div class="text-xs text-gray-400">Judul</div>
            <div class="text-gray-700 leading-snug line-clamp-2">{{ $item->mahasiswa?->tugasAkhir?->judul ?? '-' }}</div>
          </div>
        </div>
      </div>
    @empty
      <div
        class="bg-white rounded-xl shadow-sm border border-dashed border-gray-300 p-6 text-sm text-gray-500 text-center">
        Belum ada data pengujian pada periode ini.
      </div>
    @endforelse
  </div>

  <!-- Desktop Table -->
  <div class="hidden lg:block bg-white rounded-xl shadow-sm overflow-x-auto">
    <table class="w-full min-w-[720px] border-collapse">
      <thead class="bg-gray-50">
        <tr>
          <th
            class="px-4 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b-2 border-gray-200">
            No</th>
          <th
            class="px-4 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b-2 border-gray-200">
            NIM</th>
          <th
            class="px-4 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b-2 border-gray-200">
            Nama</th>
          <th
            class="px-4 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b-2 border-gray-200">
            Tahapan</th>
          <th
            class="px-4 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b-2 border-gray-200">
            Judul</th>
          <th
            class="px-4 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b-2 border-gray-200">
            Peran</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($daftarPengujian as $item)
          @php($ujians = $item->mahasiswa?->tugasAkhir?->ujian ?? collect())
          <tr class="hover:bg-gray-50 transition-colors">
            <td class="p-4 text-sm text-gray-500 border-b border-gray-100">
              {{ $daftarPengujian->firstItem() + $loop->index }}</td>
            <td class="p-4 text-sm text-gray-500 border-b border-gray-100">{{ $item->mahasiswa->nim }}</td>
            <td class="p-4 text-sm font-medium text-gray-900 border-b border-gray-100">{{ $item->mahasiswa->nama_lengkap }}
            </td>
            <td class="p-4 text-sm text-gray-500 border-b border-gray-100">
              <div class="flex flex-wrap gap-1.5">
                @foreach ($ujians as $ujian)
                  <span class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                    {{ ucfirst($ujian->jenis_ujian) }}
                  </span>
                @endforeach
              </div>
            </td>
            <td class="p-4 text-sm text-gray-500 border-b border-gray-100">
              <div class="max-w-[300px] whitespace-normal leading-snug">
                {{ $item->mahasiswa?->tugasAkhir?->judul ?? '-' }}
              </div>
            </td>
            <td class="p-4 text-sm text-gray-500 border-b border-gray-100">
              <span class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold bg-violet-100 text-violet-800">
                {{ $item->getJenisPenguji() }}
              </span>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="p-6 text-sm text-gray-500 text-center">Belum ada data pengujian pada periode ini.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-4">
    {{ $daftarPengujian->links('pagination::tailwind') }}
  </div>
@endsection
