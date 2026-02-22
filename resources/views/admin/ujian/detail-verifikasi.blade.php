@extends('layouts.app')

@section('title', 'Detail Verifikasi Ujian')

@section('sidebar')
  @include('admin.sidebar')
@endsection

@section('content')

  {{-- Page Header (Breadcrumb) --}}
  <div class="flex items-start justify-between gap-4 mb-8">
    <div>
      <nav class="flex items-center gap-1.5 mb-2 text-sm text-gray-500">
        <a href="{{ route('admin.ujian.verifikasi', $jenis) }}" class="transition hover:text-blue-600">Daftar Pengajuan</a>
        <i class="fas fa-chevron-right text-[10px] text-gray-400"></i>
        <span class="font-medium text-gray-900">Verifikasi Berkas</span>
      </nav>
      <h1 class="text-2xl font-bold text-gray-900">Detail Verifikasi Syarat Ujian</h1>
    </div>
  </div>

  {{-- Progress Bar --}}
  <div class="p-5 mb-8 bg-white shadow-sm rounded-xl">
    <div class="relative flex justify-between gap-3">
      <div class="absolute top-5 left-[8%] right-[8%] h-0.5 bg-gray-200 z-0"></div>
      <div class="relative z-10 flex flex-col items-center flex-1">
        <div
          class="flex items-center justify-center w-10 h-10 mb-2 text-base text-white bg-blue-600 rounded-full ring-4 ring-blue-100">
          <i class="fas fa-file-circle-check"></i>
        </div>
        <span class="text-xs font-semibold text-center text-blue-600">Verifikasi Syarat</span>
      </div>
      <div class="relative z-10 flex flex-col items-center flex-1">
        <div class="flex items-center justify-center w-10 h-10 mb-2 text-base text-gray-400 bg-gray-200 rounded-full">
          <i class="fas fa-envelope-open-text"></i>
        </div>
        <span class="text-xs font-medium text-center text-gray-500">Buat Undangan</span>
      </div>
    </div>
  </div>

  {{-- Flash Messages --}}
  <x-alert type="success" />
  <x-alert type="error" />
  <x-alert type="warning" />

  {{-- Info Grid: 3 Kolom (Mahasiswa 2/3, Jadwal 1/3) --}}
  <div class="grid grid-cols-1 gap-6 mb-6 lg:grid-cols-4">

    {{-- Ringkasan Mahasiswa (2/3) --}}
    <section class="overflow-hidden bg-white shadow-sm rounded-xl lg:col-span-3">
      <div class="flex items-center justify-between gap-4 px-6 py-4 border-b border-gray-200">
        <h2 class="text-base font-semibold text-gray-900">Ringkasan Mahasiswa</h2>
        <span
          class="inline-block px-3 py-1 text-xs font-semibold text-amber-800 bg-amber-100 rounded-full whitespace-nowrap">Menunggu</span>
      </div>
      <div class="grid grid-cols-2 gap-4 p-6 sm:grid-cols-4">
        <div>
          <div class="mb-1 text-xs font-medium tracking-wider text-gray-400 uppercase">Nama</div>
          <div class="text-sm font-medium text-gray-900">{{ $ujian->tugasAkhir->mahasiswa->nama_lengkap }}</div>
        </div>
        <div>
          <div class="mb-1 text-xs font-medium tracking-wider text-gray-400 uppercase">NIM</div>
          <div class="text-sm font-medium text-gray-900">{{ $ujian->tugasAkhir->mahasiswa->nim }}</div>
        </div>
        <div>
          <div class="mb-1 text-xs font-medium tracking-wider text-gray-400 uppercase">Program Studi</div>
          <div class="text-sm font-medium text-gray-900">{{ $ujian->tugasAkhir->mahasiswa->program_studi }}</div>
        </div>
        <div class="col-span-2 sm:col-span-1">
          <div class="mb-1 text-xs font-medium tracking-wider text-gray-400 uppercase">Jenis Ujian</div>
          <div class="text-sm font-medium text-gray-900">Ujian {{ ucfirst($jenis) }}</div>
        </div>

        {{-- Pembimbing & Penguji sejajar --}}
        <div class="col-span-2 pt-3 border-t border-gray-100 sm:col-span-3">
          <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
              <div class="mb-2 text-xs font-medium tracking-wider text-gray-400 uppercase">Dosen Pembimbing</div>
              <ol class="space-y-1">
                @forelse ($ujian->tugasAkhir->mahasiswa->dosenPembimbing as $index => $p)
                  <li class="text-sm text-gray-900">
                    <span class="mr-1.5 text-gray-400">{{ $index + 1 }}.</span>{{ $p->dosen->nama_lengkap }}
                  </li>
                @empty
                  <li class="text-sm text-gray-400 italic">Belum ditetapkan</li>
                @endforelse
              </ol>
            </div>
            <div>
              <div class="mb-2 text-xs font-medium tracking-wider text-gray-400 uppercase">Dosen Penguji</div>
              <ol class="space-y-1">
                @forelse ($ujian->tugasAkhir->mahasiswa->dosenPenguji as $index => $p)
                  <li class="text-sm text-gray-900">
                    <span class="mr-1.5 text-gray-400">{{ $index + 1 }}.</span>{{ $p->dosen->nama_lengkap }}
                  </li>
                @empty
                  <li class="text-sm text-gray-400 italic">Belum ditetapkan</li>
                @endforelse
              </ol>
            </div>
          </div>
        </div>

        <div class="col-span-2 pt-3 border-t border-gray-100 sm:col-span-3">
          <div class="mb-1 text-xs font-medium tracking-wider text-gray-400 uppercase">Judul</div>
          <div class="text-sm font-medium leading-relaxed text-gray-900">
            {{ $ujian->tugasAkhir->judul }}
          </div>
        </div>
      </div>
    </section>

    {{-- Jadwal Ujian (1/3) --}}
    <section class="overflow-hidden bg-white shadow-sm rounded-xl lg:col-span-1">
      <div class="flex items-center justify-between gap-4 px-6 py-4 border-b border-gray-200">
        <h2 class="text-base font-semibold text-gray-900">Jadwal Ujian</h2>
        <span
          class="inline-block px-3 py-1 text-xs font-semibold text-blue-800 bg-blue-100 rounded-full whitespace-nowrap">{{ ucfirst($jenis) }}</span>
      </div>
      @if ($ujian->jadwalUjian)
        <div class="flex flex-col gap-4 p-6">
          <div>
            <div class="mb-1 text-xs font-medium tracking-wider text-gray-400 uppercase">Tanggal</div>
            <div class="text-sm font-medium text-gray-900">
              {{ $ujian->jadwalUjian->tanggal_ujian->translatedFormat('d F Y') }}</div>
          </div>
          <div>
            <div class="mb-1 text-xs font-medium tracking-wider text-gray-400 uppercase">Waktu</div>
            <div class="text-sm font-medium text-gray-900">
              {{ \Carbon\Carbon::parse($ujian->jadwalUjian->jam_mulai)->format('H:i') }}
              &ndash;
              {{ \Carbon\Carbon::parse($ujian->jadwalUjian->jam_selesai)->format('H:i') }} WITA
            </div>
          </div>
          <div>
            <div class="mb-1 text-xs font-medium tracking-wider text-gray-400 uppercase">Ruangan</div>
            <div class="text-sm font-medium text-gray-900">{{ $ujian->jadwalUjian->ruangan }}</div>
          </div>
        </div>
      @else
        <div class="flex flex-col items-center justify-center gap-2 p-6 text-center text-gray-400">
          <i class="fas fa-calendar-times text-2xl"></i>
          <p class="text-sm">Jadwal belum diisi</p>
        </div>
      @endif
    </section>

  </div>

  {{-- Berkas Syarat --}}
  <form method="POST" action="{{ route('admin.ujian.verifikasi.proses', [$jenis, $ujian->id]) }}">
    @csrf
    <section class="mb-6 overflow-hidden bg-white shadow-sm rounded-xl">
      <div class="flex items-center justify-between gap-4 px-6 py-4 border-b border-gray-200">
        <div>
          <h2 class="text-base font-semibold text-gray-900">Berkas Syarat</h2>
          <p class="text-xs text-gray-500">Verifikasi setiap berkas, pilih ACC atau Tolak.</p>
        </div>
      </div>
      <div class="p-6 space-y-4">

        @forelse ($ujian->dokumenUjian as $index => $dokumen)
          <div x-data="{ status: 'acc' }"
            class="flex items-start gap-4 p-4 border rounded-xl bg-yellow-50 border-yellow-300">
            <div class="flex items-center justify-center flex-shrink-0 w-8 h-8 text-white bg-yellow-400 rounded-full">
              <i class="fas fa-exclamation text-sm"></i>
            </div>
            <div class="flex-1 min-w-0">
              <div class="mb-0.5 text-sm font-semibold text-gray-800">
                {{ $dokumen->nama_dokumen ?? basename($dokumen->file_path) }}</div>
              <div class="mb-3 text-xs text-gray-500">
                Diunggah {{ $dokumen->created_at->translatedFormat('d M Y') }}
              </div>

              {{-- File Preview --}}
              <div class="flex items-center gap-3 px-3 py-2 mb-3 bg-white border border-yellow-200 rounded-lg">
                <div class="flex items-center justify-center w-9 h-9 text-red-500 bg-red-50 rounded-lg shrink-0">
                  <i class="fas fa-file-pdf text-base"></i>
                </div>
                <div class="flex-1 min-w-0">
                  <div class="text-sm font-medium text-gray-900 truncate">{{ basename($dokumen->file_path) }}</div>
                </div>
                <div class="flex gap-3 shrink-0">
                  <a href="{{ asset('storage/' . $dokumen->file_path) }}"
                    class="text-xs font-semibold text-blue-600 hover:underline">
                    <i class="fas fa-eye mr-1"></i>Lihat
                  </a>
                  <a href="{{ asset('storage/' . $dokumen->file_path) }}" download
                    class="text-xs font-semibold text-green-500 hover:underline">
                    <i class="fas fa-download mr-1"></i>Unduh
                  </a>
                </div>
              </div>

              {{-- ACC / Tolak --}}
              <div class="flex gap-4 mb-2">
                <label class="inline-flex items-center gap-1.5 text-sm text-gray-700 cursor-pointer">
                  <input type="radio" name="dokumen[{{ $dokumen->id }}][status]" value="acc" x-model="status"
                    class="accent-green-600" />
                  <span>ACC</span>
                </label>
                <label class="inline-flex items-center gap-1.5 text-sm text-red-600 cursor-pointer">
                  <input type="radio" name="dokumen[{{ $dokumen->id }}][status]" value="tolak" x-model="status"
                    class="accent-red-600" />
                  <span>Tolak</span>
                </label>
              </div>
              <textarea x-show="status === 'tolak'" x-transition.duration.200ms name="dokumen[{{ $dokumen->id }}][catatan]"
                placeholder="Alasan penolakan (isi jika ditolak)"
                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg resize-y min-h-[70px] focus:outline-none focus:border-red-400 focus:ring-1 focus:ring-red-100"></textarea>
            </div>
          </div>
        @empty
          <div class="flex flex-col items-center justify-center gap-2 py-10 text-center text-gray-400">
            <i class="fas fa-folder-open text-2xl"></i>
            <p class="text-sm">Tidak ada berkas yang perlu diverifikasi</p>
          </div>
        @endforelse

      </div>

      {{-- Submit Buttons --}}
      <div class="flex flex-wrap items-center justify-end gap-3 px-6 py-4 border-t border-gray-200">
        <button type="submit"
          class="px-5 py-2.5 text-sm font-semibold text-white transition bg-blue-600 rounded-lg hover:bg-blue-700">
          <i class="fas fa-check mr-1.5"></i> ACC &amp; Buat Undangan
        </button>
      </div>
    </section>
  </form>

@endsection
