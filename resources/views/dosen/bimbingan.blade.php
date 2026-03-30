@extends('layouts.app')

@section('title', 'Dosen-Bimbingan')

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
          <i class="fas fa-users mr-2 sm:mr-3"></i>Mahasiswa Bimbingan
        </h1>
        <p class="opacity-90 text-sm sm:text-[0.95rem]">Kelola dan pantau perkembangan mahasiswa bimbingan Anda</p>
      </div>
    </div>
  </div>

  <!-- Stats -->
  <div class="grid grid-cols-2 lg:grid-cols-3 gap-3 lg:gap-6 mb-6 lg:mb-8">
    <a href="{{ route('dosen.bimbingan.mahasiswa') }}"
      class="bg-white rounded-xl p-4 lg:p-6 shadow-sm flex items-center gap-3 lg:gap-4 hover:-translate-y-0.5 hover:shadow-md transition-all">
      <div
        class="w-10 h-10 lg:w-14 lg:h-14 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center text-lg lg:text-2xl shrink-0">
        <i class="fas fa-users"></i>
      </div>
      <div class="flex-1 min-w-0">
        <div class="text-xs lg:text-sm text-gray-500 mb-0.5 lg:mb-1">Bimbingan Aktif</div>
        <div class="text-xl lg:text-3xl font-bold text-gray-900">{{ $totalMahasiswaBimbingan }}</div>
      </div>
    </a>
    <a href="{{ route('dosen.bimbingan.mahasiswa-lulus') }}"
      class="bg-white rounded-xl p-4 lg:p-6 shadow-sm flex items-center gap-3 lg:gap-4 hover:-translate-y-0.5 hover:shadow-md transition-all">
      <div
        class="w-10 h-10 lg:w-14 lg:h-14 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-lg lg:text-2xl shrink-0">
        <i class="fas fa-graduation-cap"></i>
      </div>
      <div class="flex-1 min-w-0">
        <div class="text-xs lg:text-sm text-gray-500 mb-0.5 lg:mb-1">Bimbingan Lulus</div>
        <div class="text-xl lg:text-3xl font-bold text-gray-900">{{ $totalMahasiswaLulus }}</div>
      </div>
    </a>
    <div
      class="bg-white rounded-xl p-4 lg:p-6 shadow-sm flex items-center gap-3 lg:gap-4 hover:-translate-y-0.5 hover:shadow-md transition-all">
      <div
        class="w-10 h-10 lg:w-14 lg:h-14 rounded-xl bg-orange-200 text-orange-600 flex items-center justify-center text-lg lg:text-2xl shrink-0">
        <i class="fas fa-clock"></i>
      </div>
      <div class="flex-1 min-w-0">
        <div class="text-xs lg:text-sm text-gray-500 mb-0.5 lg:mb-1">Menunggu Review</div>
        <div class="text-xl lg:text-3xl font-bold text-gray-900">{{ $pendingSubmissions->total() }}</div>
      </div>
    </div>
  </div>

  <!-- Filter Section -->
  <div class="bg-white rounded-xl p-4 sm:p-6 shadow-sm mb-6">
    <form method="GET" action="{{ route('dosen.bimbingan.index') }}" id="filterForm">
      <div class="flex flex-wrap gap-3 items-end">
        <div class="flex flex-col gap-2 flex-1 min-w-40">
          <label class="text-sm font-medium text-gray-700">Cari Mahasiswa</label>
          <input type="text" name="search" value="{{ request('search') }}"
            class="px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-700 transition-all focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600/10"
            placeholder="Nama atau NIM..."
            onkeydown="if(event.key==='Enter'){event.preventDefault();document.getElementById('filterForm').submit()}" />
        </div>
        <div class="flex flex-col gap-2 flex-1 min-w-36">
          <label class="text-sm font-medium text-gray-700">Tahap</label>
          <select name="tahap" onchange="document.getElementById('filterForm').submit()"
            class="px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-700 transition-all focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600/10">
            <option value="">Semua Tahap</option>
            <option value="proposal" @selected(request('tahap') === 'proposal')>Proposal</option>
            <option value="hasil" @selected(request('tahap') === 'hasil')>Hasil</option>
            <option value="skripsi" @selected(request('tahap') === 'skripsi')>Skripsi</option>
          </select>
        </div>
        @if (request()->hasAny(['search', 'tahap']))
          <div class="flex flex-col justify-end" style="padding-bottom: 1px">
            <a href="{{ route('dosen.bimbingan.index') }}"
              class="w-10 h-10 flex items-center justify-center rounded-lg border border-gray-300 text-gray-500 hover:bg-gray-100 hover:text-gray-700 transition-colors"
              title="Reset filter">
              <i class="fas fa-times"></i>
            </a>
          </div>
        @endif
      </div>
    </form>
  </div>

  <!-- Mobile & Tablet Cards -->
  <div class="space-y-3 lg:hidden">
    @forelse ($pendingSubmissions as $submission)
      <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
        <div class="flex items-start justify-between gap-3 mb-3">
          <div class="min-w-0">
            <div class="text-sm font-semibold text-gray-900 truncate">
              {{ $submission->tugasAkhir->mahasiswa->nama_lengkap }}</div>
            <div class="text-xs text-gray-500">NIM: {{ $submission->tugasAkhir->mahasiswa->nim }}</div>
          </div>
          <span class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 shrink-0">
            {{ $submission->tugasAkhir->tahapan }}
          </span>
        </div>

        <div class="space-y-1.5 text-xs sm:text-sm">
          <div>
            <div class="text-xs text-gray-400">Tanggal Submit</div>
            <div class="text-gray-700">{{ $submission->created_at->format('d M Y') }}</div>
          </div>
          <div>
            <div class="text-xs text-gray-400">Judul</div>
            <div class="text-gray-700 leading-snug line-clamp-2">{{ $submission->tugasAkhir->judul }}</div>
          </div>
        </div>

        <div class="mt-3 pt-3 border-t border-gray-100">
          <a href="{{ route('dosen.bimbingan.detail', ['submission' => $submission->id]) }}"
            class="inline-flex items-center gap-2 rounded-lg bg-blue-100 text-blue-700 px-3 py-2 text-xs font-semibold hover:bg-blue-200 transition-all">
            <i class="fas fa-eye"></i>
            Lihat Detail
          </a>
        </div>
      </div>
    @empty
      <div
        class="bg-white rounded-xl shadow-sm border border-dashed border-gray-300 p-6 text-sm text-gray-500 text-center">
        Belum ada submission yang menunggu review.
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
            Tanggal Submit</th>
          <th
            class="px-4 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b-2 border-gray-200">
            Judul</th>
          <th
            class="px-4 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b-2 border-gray-200">
            Jenis</th>
          <th
            class="px-4 py-3.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider border-b-2 border-gray-200">
            Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($pendingSubmissions as $submission)
          <tr class="hover:bg-gray-50 transition-colors">
            <td class="p-4 text-sm text-gray-500 border-b border-gray-100">{{ $loop->iteration }}</td>
            <td class="p-4 text-sm text-gray-500 border-b border-gray-100">{{ $submission->tugasAkhir->mahasiswa->nim }}
            </td>
            <td class="p-4 text-sm text-gray-500 border-b border-gray-100">
              {{ $submission->tugasAkhir->mahasiswa->nama_lengkap }}</td>
            <td class="p-4 text-sm text-gray-500 border-b border-gray-100">
              {{ $submission->created_at->format('d M Y') }}</td>
            <td class="p-4 text-sm text-gray-500 border-b border-gray-100">
              <div class="max-w-[300px] whitespace-normal leading-snug">
                {{ $submission->tugasAkhir->judul }}
              </div>
            </td>
            <td class="p-4 text-sm text-gray-500 border-b border-gray-100">
              <span
                class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800 whitespace-nowrap">{{ $submission->tugasAkhir->tahapan }}</span>
            </td>
            <td class="p-4 text-sm text-gray-500 border-b border-gray-100">
              <div class="flex gap-2">
                <a href="{{ route('dosen.bimbingan.detail', ['submission' => $submission->id]) }}"
                  class="w-8 h-8 rounded-md flex items-center justify-center bg-blue-100 text-blue-600 hover:bg-blue-200 transition-all text-sm">
                  <i class="fas fa-eye"></i>
                </a>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="p-6 text-sm text-gray-500 text-center">Belum ada submission yang menunggu review.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="mt-4">
    {{ $pendingSubmissions->links('pagination::tailwind') }}
  </div>
@endsection
