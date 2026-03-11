@extends('layouts.app')

@section('title', 'Detail Dosen - ' . $dosen->nama_lengkap)

@section('sidebar')
  @include('admin.sidebar')
@endsection

@section('content')

  {{-- Breadcrumb --}}
  <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
    <a href="{{ route('admin.dosen.index') }}" class="hover:text-teal-600 transition-colors">Kelola Dosen</a>
    <i class="fas fa-chevron-right text-xs text-gray-400"></i>
    <span class="text-gray-800 font-medium truncate">{{ $dosen->nama_lengkap }}</span>
  </div>

  {{-- Header Card --}}
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm mb-6">
    <div class="p-5 sm:p-6">
      <div class="flex flex-col lg:flex-row lg:items-center gap-5">
        <div class="flex items-center gap-4 sm:gap-5 flex-1 min-w-0">
          @php
            $initials = collect(explode(' ', $dosen->nama_lengkap))
                ->take(2)
                ->map(fn($w) => strtoupper($w[0]))
                ->join('');

            $statusColors = [
                'aktif' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                'cuti' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                'nonaktif' => 'bg-gray-100 text-gray-500 border-gray-200',
                'pensiun' => 'bg-red-100 text-red-600 border-red-200',
            ];
            $statusColor = $statusColors[$dosen->status] ?? 'bg-gray-100 text-gray-500 border-gray-200';
          @endphp
          <div
            class="w-16 h-16 sm:w-20 sm:h-20 rounded-xl bg-teal-50 text-teal-700 border border-teal-100 flex items-center justify-center text-2xl sm:text-3xl font-bold shrink-0">
            {{ $initials }}
          </div>
          <div class="min-w-0">
            <h1 class="text-xl sm:text-2xl font-semibold text-gray-900 truncate">{{ $dosen->nama_lengkap }}</h1>
            <p class="text-sm text-gray-500 mt-0.5">NIDN {{ $dosen->nidn }} · {{ $dosen->jabatan_fungsional }}</p>
            <div class="mt-2 flex flex-wrap gap-2">
              <span
                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-700 border border-gray-200">
                {{ $dosen->program_studi }}
              </span>
              <span
                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $statusColor }}">
                {{ ucfirst($dosen->status) }}
              </span>
            </div>
          </div>
        </div>

        <div class="flex items-center gap-2 shrink-0">
          <a href="{{ route('admin.dosen.edit', $dosen->id) }}"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold bg-teal-600 text-white hover:bg-teal-700 transition-colors">
            <i class="fas fa-edit text-xs"></i> Edit Data
          </a>
        </div>
      </div>
    </div>
  </div>

  <div class="space-y-5">

    {{-- ██ Data Pribadi & Akademik --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
      <div class="px-5 py-3.5 border-b border-gray-100 flex items-center gap-2">
        <i class="fas fa-id-card text-teal-500 text-sm"></i>
        <h2 class="text-sm font-semibold text-gray-800">Data Dosen</h2>
      </div>
      <div class="px-5 py-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

        {{-- NIDN --}}
        <div class="flex items-start gap-3">
          <div
            class="w-8 h-8 rounded-lg bg-teal-50 text-teal-500 flex items-center justify-center text-xs shrink-0 mt-0.5">
            <i class="fas fa-id-badge"></i>
          </div>
          <div class="min-w-0">
            <div class="text-xs text-gray-400 mb-0.5">NIDN</div>
            <div class="text-sm font-medium text-gray-800 break-words">{{ $dosen->nidn }}</div>
          </div>
        </div>

        {{-- Jurusan --}}
        <div class="flex items-start gap-3">
          <div
            class="w-8 h-8 rounded-lg bg-teal-50 text-teal-500 flex items-center justify-center text-xs shrink-0 mt-0.5">
            <i class="fas fa-building-columns"></i>
          </div>
          <div class="min-w-0">
            <div class="text-xs text-gray-400 mb-0.5">Jurusan</div>
            <div class="text-sm font-medium text-gray-800 break-words">{{ $dosen->jurusan }}</div>
          </div>
        </div>

        {{-- Program Studi --}}
        <div class="flex items-start gap-3">
          <div
            class="w-8 h-8 rounded-lg bg-teal-50 text-teal-500 flex items-center justify-center text-xs shrink-0 mt-0.5">
            <i class="fas fa-graduation-cap"></i>
          </div>
          <div class="min-w-0">
            <div class="text-xs text-gray-400 mb-0.5">Program Studi</div>
            <div class="text-sm font-medium text-gray-800 break-words">{{ $dosen->program_studi }}</div>
          </div>
        </div>

        {{-- Jabatan Fungsional --}}
        <div class="flex items-start gap-3">
          <div
            class="w-8 h-8 rounded-lg bg-teal-50 text-teal-500 flex items-center justify-center text-xs shrink-0 mt-0.5">
            <i class="fas fa-briefcase"></i>
          </div>
          <div class="min-w-0">
            <div class="text-xs text-gray-400 mb-0.5">Jabatan Fungsional</div>
            <div class="text-sm font-medium text-gray-800 break-words">{{ $dosen->jabatan_fungsional }}</div>
          </div>
        </div>

        {{-- Keahlian --}}
        <div class="flex items-start gap-3 sm:col-span-2 lg:col-span-1">
          <div
            class="w-8 h-8 rounded-lg bg-teal-50 text-teal-500 flex items-center justify-center text-xs shrink-0 mt-0.5">
            <i class="fas fa-flask"></i>
          </div>
          <div class="min-w-0">
            <div class="text-xs text-gray-400 mb-0.5">Keahlian / Bidang Riset</div>
            <div class="text-sm font-medium text-gray-800 break-words">{{ $dosen->keahlian }}</div>
          </div>
        </div>

        <div class="flex items-start gap-3 sm:col-span-2 lg:col-span-3">
          <div
            class="w-8 h-8 rounded-lg bg-teal-50 text-teal-500 flex items-center justify-center text-xs shrink-0 mt-0.5">
            <i class="fas fa-book-open"></i>
          </div>
          <div class="min-w-0">
            <div class="text-xs text-gray-400 mb-1">Mata Kuliah yang Diampu</div>
            @if ($dosen->mataKuliah->isNotEmpty())
              <div class="flex flex-wrap gap-2">
                @foreach ($dosen->mataKuliah as $mataKuliah)
                  <span
                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-sky-50 text-sky-700 border border-sky-100">
                    {{ $mataKuliah->kode }} - {{ $mataKuliah->nama }}
                  </span>
                @endforeach
              </div>
            @else
              <div class="text-sm font-medium text-gray-500">Belum ada mata kuliah yang diampu.</div>
            @endif
          </div>
        </div>

        {{-- No. Telp --}}
        <div class="flex items-start gap-3">
          <div
            class="w-8 h-8 rounded-lg bg-teal-50 text-teal-500 flex items-center justify-center text-xs shrink-0 mt-0.5">
            <i class="fas fa-phone"></i>
          </div>
          <div class="min-w-0">
            <div class="text-xs text-gray-400 mb-0.5">No. Telepon</div>
            <div class="text-sm font-medium text-gray-800 break-words">{{ $dosen->no_telp ?? '-' }}</div>
          </div>
        </div>

        {{-- Email --}}
        <div class="flex items-start gap-3">
          <div
            class="w-8 h-8 rounded-lg bg-teal-50 text-teal-500 flex items-center justify-center text-xs shrink-0 mt-0.5">
            <i class="fas fa-envelope"></i>
          </div>
          <div class="min-w-0">
            <div class="text-xs text-gray-400 mb-0.5">Email</div>
            <div class="text-sm font-medium text-gray-800 break-words">{{ $dosen->user->email ?? '-' }}</div>
          </div>
        </div>

        {{-- Status --}}
        <div class="flex items-start gap-3">
          <div
            class="w-8 h-8 rounded-lg bg-teal-50 text-teal-500 flex items-center justify-center text-xs shrink-0 mt-0.5">
            <i class="fas fa-circle-check"></i>
          </div>
          <div class="min-w-0">
            <div class="text-xs text-gray-400 mb-0.5">Status</div>
            <span
              class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold border {{ $statusColor }}">
              {{ ucfirst($dosen->status) }}
            </span>
          </div>
        </div>

      </div>
    </div>

    {{-- ██ Mahasiswa Bimbingan --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
      <div class="px-5 py-3.5 border-b border-gray-100 flex items-center justify-between">
        <div class="flex items-center gap-2">
          <i class="fas fa-users text-blue-500 text-sm"></i>
          <h2 class="text-sm font-semibold text-gray-800">Mahasiswa Bimbingan</h2>
        </div>
        <span class="text-xs text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full">
          {{ $dosen->pembimbingMahasiswa->count() }} mahasiswa
        </span>
      </div>

      @if ($dosen->pembimbingMahasiswa->isNotEmpty())
        <div class="divide-y divide-gray-50">
          @foreach ($dosen->pembimbingMahasiswa as $bimbingan)
            @php $mhs = $bimbingan->mahasiswa; @endphp
            <div class="px-5 py-3.5 flex items-center gap-4">
              {{-- Avatar inisial --}}
              @php
                $mhsInitials = collect(explode(' ', $mhs->nama_lengkap ?? ''))
                    ->take(2)
                    ->map(fn($w) => strtoupper($w[0]))
                    ->join('');
              @endphp
              <div
                class="w-9 h-9 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xs font-bold shrink-0">
                {{ $mhsInitials }}
              </div>

              <div class="flex-1 min-w-0">
                <div class="text-sm font-semibold text-gray-800 truncate">{{ $mhs->nama_lengkap }}</div>
                <div class="text-xs text-gray-400">NIM {{ $mhs->nim }} · {{ $mhs->program_studi }}</div>
              </div>

              <div class="shrink-0 flex items-center gap-2">
                <span
                  class="text-xs px-2.5 py-0.5 rounded-full font-medium bg-blue-50 text-blue-600 border border-blue-100">
                  {{ $bimbingan->getJenisPembimbing() }}
                </span>
                @if ($bimbingan->status_aktif)
                  <span class="text-xs px-2 py-0.5 rounded-full font-medium bg-emerald-50 text-emerald-600">Aktif</span>
                @else
                  <span class="text-xs px-2 py-0.5 rounded-full font-medium bg-gray-100 text-gray-400">Selesai</span>
                @endif
              </div>

              <a href="{{ route('admin.mahasiswa.show', $mhs->id) }}"
                class="shrink-0 text-gray-400 hover:text-teal-600 transition-colors ml-1">
                <i class="fas fa-chevron-right text-xs"></i>
              </a>
            </div>
          @endforeach
        </div>
      @else
        <div class="px-5 py-8 text-center">
          <div class="text-gray-300 text-3xl mb-2"><i class="fas fa-user-graduate"></i></div>
          <p class="text-sm text-gray-400">Belum ada mahasiswa bimbingan</p>
        </div>
      @endif
    </div>

    {{-- ██ Mahasiswa yang Dijuji --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
      <div class="px-5 py-3.5 border-b border-gray-100 flex items-center justify-between">
        <div class="flex items-center gap-2">
          <i class="fas fa-clipboard-check text-orange-500 text-sm"></i>
          <h2 class="text-sm font-semibold text-gray-800">Mahasiswa yang Dijuji</h2>
        </div>
        <span class="text-xs text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full">
          {{ $dosen->pengujiMahasiswa->count() }} mahasiswa
        </span>
      </div>

      @if ($dosen->pengujiMahasiswa->isNotEmpty())
        <div class="divide-y divide-gray-50">
          @foreach ($dosen->pengujiMahasiswa as $pengujian)
            @php $mhs = $pengujian->mahasiswa; @endphp
            <div class="px-5 py-3.5 flex items-center gap-4">
              {{-- Avatar inisial --}}
              @php
                $mhsInitials = collect(explode(' ', $mhs->nama_lengkap ?? ''))
                    ->take(2)
                    ->map(fn($w) => strtoupper($w[0]))
                    ->join('');
              @endphp
              <div
                class="w-9 h-9 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center text-xs font-bold shrink-0">
                {{ $mhsInitials }}
              </div>

              <div class="flex-1 min-w-0">
                <div class="text-sm font-semibold text-gray-800 truncate">{{ $mhs->nama_lengkap }}</div>
                <div class="text-xs text-gray-400">NIM {{ $mhs->nim }} · {{ $mhs->program_studi }}</div>
              </div>

              <div class="shrink-0 flex items-center gap-2">
                <span
                  class="text-xs px-2.5 py-0.5 rounded-full font-medium bg-orange-50 text-orange-600 border border-orange-100">
                  {{ ucfirst(str_replace('_', ' ', $pengujian->jenis_penguji)) }}
                </span>
                @if ($pengujian->status_aktif)
                  <span class="text-xs px-2 py-0.5 rounded-full font-medium bg-emerald-50 text-emerald-600">Aktif</span>
                @else
                  <span class="text-xs px-2 py-0.5 rounded-full font-medium bg-gray-100 text-gray-400">Selesai</span>
                @endif
              </div>

              <a href="{{ route('admin.mahasiswa.show', $mhs->id) }}"
                class="shrink-0 text-gray-400 hover:text-teal-600 transition-colors ml-1">
                <i class="fas fa-chevron-right text-xs"></i>
              </a>
            </div>
          @endforeach
        </div>
      @else
        <div class="px-5 py-8 text-center">
          <div class="text-gray-300 text-3xl mb-2"><i class="fas fa-clipboard-list"></i></div>
          <p class="text-sm text-gray-400">Belum ada mahasiswa yang dijuji</p>
        </div>
      @endif
    </div>

  </div>

@endsection
