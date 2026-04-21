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

  <!-- Container: Filter + Table/Cards -->
  <div class="rounded-xl border border-slate-200 bg-white shadow-sm">
    <!-- Header + Filter -->
    <div class="flex flex-col gap-4 border-b border-slate-200 p-4 sm:p-5 sm:flex-row sm:items-center sm:justify-between">
      <div>
        <h2 class="text-base sm:text-lg font-semibold text-slate-800">Menunggu Review</h2>
        <p class="mt-0.5 sm:mt-1 text-xs sm:text-sm text-slate-500">Submission mahasiswa bimbingan yang perlu ditinjau</p>
      </div>
      <form action="{{ route('dosen.bimbingan.index') }}" method="GET" id="filterForm"
        class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:gap-3 w-full sm:w-auto">
        <div class="relative flex-1 sm:flex-initial">
          <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-sm text-slate-400"></i>
          <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau NIM..."
            class="w-full rounded-lg border border-slate-200 py-2 pl-9 pr-4 text-sm text-slate-600 placeholder-slate-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
            onkeydown="if(event.key==='Enter'){event.preventDefault();document.getElementById('filterForm').submit()}" />
        </div>
        <div class="relative min-w-[150px] w-full sm:w-auto">
          <select name="tahap" onchange="document.getElementById('filterForm').submit()"
            class="w-full appearance-none !bg-none rounded-lg border border-slate-200 py-2 pl-3 pr-8 text-sm text-slate-600 bg-white focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
            <option value="">Semua Tahap</option>
            <option value="proposal" @selected(request('tahap') === 'proposal')>Proposal</option>
            <option value="hasil" @selected(request('tahap') === 'hasil')>Hasil</option>
            <option value="skripsi" @selected(request('tahap') === 'skripsi')>Skripsi</option>
          </select>
          <i class="fas fa-chevron-down absolute right-2.5 top-1/2 -translate-y-1/2 text-[10px] text-slate-400 pointer-events-none"></i>
        </div>
        <div class="flex items-center gap-2">
          <button type="submit"
            class="flex-1 sm:flex-initial inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700">
            <i class="fas fa-search mr-1.5 sm:hidden text-xs"></i>Cari
          </button>
          @if (request()->hasAny(['search', 'tahap']))
            <a href="{{ route('dosen.bimbingan.index') }}"
              class="flex-1 sm:flex-initial inline-flex items-center justify-center rounded-lg border border-slate-200 px-4 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-50">
              Reset
            </a>
          @endif
        </div>
      </form>
    </div>

    {{-- ═══ Mobile & Tablet: Card Layout (visible < lg) ═══ --}}
    <div class="block lg:hidden">
      @forelse ($pendingSubmissions as $index => $submission)
        <div class="border-b border-slate-100 p-4 last:border-b-0">
          {{-- Top row: number + tahap --}}
          <div class="flex items-center justify-between mb-2.5">
            <span class="text-xs font-medium text-slate-400">#{{ $pendingSubmissions->firstItem() + $index }}</span>
            <span class="inline-flex rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">
              {{ $submission->tugasAkhir->tahapan }}
            </span>
          </div>

          {{-- Mahasiswa info --}}
          <div class="mb-2">
            <p class="font-semibold text-sm text-slate-800">{{ $submission->tugasAkhir->mahasiswa->nama_lengkap }}</p>
            <p class="text-xs text-slate-500">NIM: {{ $submission->tugasAkhir->mahasiswa->nim }}</p>
          </div>

          {{-- Judul --}}
          <div class="mb-3">
            <p class="text-xs font-medium text-slate-400 mb-0.5">Judul Tugas Akhir</p>
            <p class="text-sm text-slate-600 leading-relaxed line-clamp-2">{{ $submission->tugasAkhir->judul }}</p>
          </div>

          {{-- Footer: tanggal + aksi --}}
          <div class="flex items-center justify-between pt-2.5 border-t border-slate-100">
            <div class="flex items-center gap-1.5 text-xs text-slate-400">
              <i class="fas fa-calendar-alt"></i>
              <span>{{ $submission->created_at->format('d M Y') }}</span>
            </div>
            <a href="{{ route('dosen.bimbingan.detail', ['submission' => $submission->id]) }}"
              class="inline-flex items-center gap-1.5 rounded-lg bg-blue-600 px-3.5 py-2 text-xs font-medium text-white hover:bg-blue-700 transition">
              <i class="fas fa-eye text-[10px]"></i> Lihat Detail
            </a>
          </div>
        </div>
      @empty
        <div class="px-5 py-10 text-center">
          <div class="flex flex-col items-center gap-2 text-slate-500">
            <i class="fas fa-inbox text-3xl text-slate-300"></i>
            <p class="text-sm font-medium text-slate-600">Belum ada submission yang menunggu review.</p>
            <p class="text-xs text-slate-400">Submission mahasiswa yang masuk akan tampil di sini.</p>
          </div>
        </div>
      @endforelse
    </div>

    {{-- ═══ Desktop: Table Layout (visible lg+) ═══ --}}
    <div class="hidden lg:block overflow-x-auto">
      <table class="w-full text-left text-sm">
        <thead>
          <tr class="border-b border-slate-200 bg-slate-50">
            <th class="whitespace-nowrap px-5 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">No</th>
            <th class="whitespace-nowrap px-5 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">NIM</th>
            <th class="whitespace-nowrap px-5 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">Nama</th>
            <th class="whitespace-nowrap px-5 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">Tanggal Submit</th>
            <th class="whitespace-nowrap px-5 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">Judul</th>
            <th class="whitespace-nowrap px-5 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">Jenis</th>
            <th class="whitespace-nowrap px-5 py-3 text-xs font-semibold uppercase tracking-wider text-slate-500">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
          @forelse ($pendingSubmissions as $index => $submission)
            <tr class="hover:bg-slate-50 transition-colors">
              <td class="whitespace-nowrap px-5 py-4 text-slate-500">{{ $pendingSubmissions->firstItem() + $index }}</td>
              <td class="whitespace-nowrap px-5 py-4 text-slate-500">{{ $submission->tugasAkhir->mahasiswa->nim }}</td>
              <td class="px-5 py-4 font-medium text-slate-800">{{ $submission->tugasAkhir->mahasiswa->nama_lengkap }}</td>
              <td class="whitespace-nowrap px-5 py-4 text-slate-500">{{ $submission->created_at->format('d M Y') }}</td>
              <td class="px-5 py-4">
                <div class="max-w-xs text-slate-600 leading-relaxed">{{ $submission->tugasAkhir->judul }}</div>
              </td>
              <td class="px-5 py-4">
                <span class="inline-flex rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 whitespace-nowrap">{{ $submission->tugasAkhir->tahapan }}</span>
              </td>
              <td class="px-5 py-4">
                <a href="{{ route('dosen.bimbingan.detail', ['submission' => $submission->id]) }}"
                  class="inline-flex items-center rounded-lg bg-blue-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-blue-700 transition">Lihat</a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="7" class="px-5 py-10 text-center">
                <div class="flex flex-col items-center gap-2 text-slate-500">
                  <i class="fas fa-inbox text-3xl text-slate-300"></i>
                  <p class="text-sm font-medium text-slate-600">Belum ada submission yang menunggu review.</p>
                  <p class="text-sm text-slate-400">Submission mahasiswa yang masuk akan tampil di tabel ini.</p>
                </div>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div class="flex flex-col items-center justify-between gap-3 border-t border-slate-200 px-4 sm:px-5 py-4 sm:flex-row">
      {{ $pendingSubmissions->links() }}
    </div>
  </div>
@endsection
