@extends('layouts.app')

@section('title', 'Daftar Mahasiswa Bimbingan')

@section('sidebar')
  @if (auth()->user()->role === 'kajur')
    @include('kajur.sidebar')
  @else
    @include('dosen.sidebar')
  @endif
@endsection

@section('content')
  <!-- Page Header -->
  <div class="mb-8">
    <nav class="flex items-center gap-2 text-sm text-gray-500 mb-3">
      <a href="{{ route('dosen.bimbingan.index') }}" class="hover:text-gray-700 transition-colors">Bimbingan</a>
      <i class="fas fa-chevron-right text-xs text-gray-400"></i>
      <span class="text-gray-900 font-medium">Daftar Mahasiswa</span>
    </nav>
    <div class="flex items-center gap-3 mb-4">
      <a href="{{ route('dosen.bimbingan.index') }}" class="text-gray-500 hover:text-gray-700 transition-colors">
        <i class="fas fa-arrow-left"></i>
      </a>
      <h1 class="text-3xl font-bold text-gray-900">Daftar Mahasiswa Bimbingan</h1>
    </div>
    <p class="text-base text-gray-500">Semua mahasiswa aktif yang berada di bawah bimbingan Anda</p>
  </div>

  <!-- Search Filter -->
  <div class="bg-white rounded-xl p-4 sm:p-6 shadow-sm mb-6">
    <form method="GET" action="{{ route('dosen.bimbingan.mahasiswa') }}" id="filterForm">
      <div class="flex flex-wrap gap-3 items-end">
        <div class="flex flex-col gap-2 flex-1 min-w-48">
          <label class="text-sm font-medium text-gray-700">Cari Mahasiswa</label>
          <input type="text" name="search" value="{{ request('search') }}"
            class="px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-700 transition-all focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600/10"
            placeholder="Nama atau NIM..."
            onkeydown="if(event.key==='Enter'){event.preventDefault();document.getElementById('filterForm').submit()}" />
        </div>
        @if (request('search'))
          <div class="flex flex-col justify-end">
            <a href="{{ route('dosen.bimbingan.mahasiswa') }}"
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
    @forelse ($mahasiswaBimbingan as $dp)
      <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
        <div class="flex items-start justify-between gap-3 mb-3">
          <div class="min-w-0">
            <div class="text-sm font-semibold text-gray-900 truncate">{{ $dp->mahasiswa->nama_lengkap }}</div>
            <div class="text-xs text-gray-500">NIM: {{ $dp->mahasiswa->nim }}</div>
          </div>
          <span class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 shrink-0">
            {{ $dp->getJenisPembimbing() }}
          </span>
        </div>
        <div class="space-y-1.5 text-xs sm:text-sm">
          <div>
            <div class="text-xs text-gray-400">Tahapan</div>
            <div class="text-gray-700">{{ $dp->mahasiswa->tugasAkhir?->tahapan ?? '-' }}</div>
          </div>
          <div>
            <div class="text-xs text-gray-400">Judul</div>
            <div class="text-gray-700 leading-snug line-clamp-2">{{ $dp->mahasiswa->tugasAkhir?->judul ?? '-' }}</div>
          </div>
          <div>
            <div class="text-xs text-gray-400">Mulai Bimbingan</div>
            <div class="text-gray-700">{{ $dp->tanggal_mulai?->format('d M Y') ?? '-' }}</div>
          </div>
        </div>
        <div class="mt-3 pt-3 border-t border-gray-100">
          <a href="{{ route('dosen.bimbingan.riwayat', $dp->id) }}"
            class="inline-flex items-center gap-2 rounded-lg bg-blue-100 text-blue-700 px-3 py-2 text-xs font-semibold hover:bg-blue-200 transition-all">
            <i class="fas fa-history"></i> Riwayat Bimbingan
          </a>
        </div>
      </div>
    @empty
      <div
        class="bg-white rounded-xl shadow-sm border border-dashed border-gray-300 p-6 text-sm text-gray-500 text-center">
        Belum ada mahasiswa bimbingan.
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
          <th
            class="px-4 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b-2 border-gray-200">
            Mulai</th>
          <th
            class="px-4 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b-2 border-gray-200">
            Riwayat</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($mahasiswaBimbingan as $dp)
          <tr class="hover:bg-gray-50 transition-colors">
            <td class="p-4 text-sm text-gray-500 border-b border-gray-100">
              {{ $mahasiswaBimbingan->firstItem() + $loop->index }}</td>
            <td class="p-4 text-sm text-gray-500 border-b border-gray-100">{{ $dp->mahasiswa->nim }}</td>
            <td class="p-4 text-sm font-medium text-gray-900 border-b border-gray-100">{{ $dp->mahasiswa->nama_lengkap }}
            </td>
            <td class="p-4 text-sm text-gray-500 border-b border-gray-100">
              <span class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                {{ $dp->mahasiswa->tugasAkhir?->tahapan ?? '-' }}
              </span>
            </td>
            <td class="p-4 text-sm text-gray-500 border-b border-gray-100">
              <div class="max-w-[300px] whitespace-normal leading-snug">
                {{ $dp->mahasiswa->tugasAkhir?->judul ?? '-' }}
              </div>
            </td>
            <td class="p-4 text-sm text-gray-500 border-b border-gray-100">{{ $dp->getJenisPembimbing() }}</td>
            <td class="p-4 text-sm text-gray-500 border-b border-gray-100">
              {{ $dp->tanggal_mulai?->format('d M Y') ?? '-' }}</td>
            <td class="p-4 text-sm text-gray-500 border-b border-gray-100">
              <a href="{{ route('dosen.bimbingan.riwayat', $dp->id) }}"
                class="w-8 h-8 rounded-md inline-flex items-center justify-center bg-blue-100 text-blue-600 hover:bg-blue-200 transition-all text-sm"
                title="Riwayat Bimbingan">
                <i class="fas fa-history"></i>
              </a>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="8" class="p-6 text-sm text-gray-500 text-center">Belum ada mahasiswa bimbingan.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-4">
    {{ $mahasiswaBimbingan->links('pagination::tailwind') }}
  </div>
@endsection
