@extends('layouts.app')

@section('title', 'Buat Undangan Ujian')

@section('sidebar')
  @include('admin.sidebar')
@endsection

@section('content')
  {{-- Page Header --}}
  <div class="flex items-center justify-between mb-8 pb-6 border-b border-gray-200">
    <div class="flex items-center gap-4">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Buat Undangan Ujian</h1>
        <p class="text-sm text-gray-500 mt-1">Ujian Proposal • Rizal Firmansyah</p>
      </div>
    </div>
  </div>

  {{-- Progress Steps --}}
  <div class="bg-white rounded-2xl border border-gray-200 p-5 mb-8">
    <div class="flex justify-between relative gap-3">
      {{-- Connector line --}}
      <div class="absolute top-5 left-[8%] right-[8%] h-[3px] bg-gray-200 z-[1]"></div>

      {{-- Step 1: Completed --}}
      <div class="flex flex-col items-center relative z-[2] flex-1">
        <div
          class="w-10 h-10 rounded-full bg-emerald-500 text-white flex items-center justify-center text-base mb-2 shadow-[0_0_0_4px_rgba(16,185,129,0.2)]">
          <i class="fas fa-file-circle-check"></i>
        </div>
        <span class="text-xs font-semibold text-emerald-500 text-center">Verifikasi Syarat</span>
      </div>

      {{-- Step 2: Active --}}
      <div class="flex flex-col items-center relative z-[2] flex-1">
        <div
          class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center text-base mb-2 shadow-[0_0_0_4px_rgba(37,99,235,0.2)]">
          <i class="fas fa-envelope-open-text"></i>
        </div>
        <span class="text-xs font-semibold text-blue-600 text-center">Buat Undangan</span>
      </div>
    </div>
  </div>

  {{-- Flash Messages --}}
  <x-alert type="success" />
  <x-alert type="error" />
  <x-alert type="warning" />

  @if ($errors->any())
    <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 shadow-sm">
      <div class="flex items-center gap-2 mb-2">
        <i class="fas fa-exclamation-triangle text-red-600"></i>
        <h3 class="text-sm font-semibold text-red-800">Terdapat Kesalahan</h3>
      </div>
      <ul class="list-disc pl-5 text-sm text-red-700 space-y-1">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

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
        </div>
        <div class="col-span-2 sm:col-span-1">
          <div class="mb-1 text-xs font-medium tracking-wider text-gray-400 uppercase">Jenis Ujian</div>
          <div class="text-sm font-medium text-gray-900">Ujian {{ ucfirst($ujian->jenis_ujian) }}</div>
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
          class="inline-block px-3 py-1 text-xs font-semibold text-blue-800 bg-blue-100 rounded-full whitespace-nowrap">{{ ucfirst($ujian->jenis_ujian) }}</span>
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



  {{-- Section Stack --}}
  <div class="flex flex-col gap-4">
    {{-- Card: Periode Akademik --}}
    <form method="POST" action="{{ route('admin.ujian.syarat.undangan.store', $ujian->id) }}">
      @csrf
      {{-- Card Periode Akademik --}}
      <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden mb-4">
        <div class="flex items-center gap-2 px-6 py-4 border-b border-gray-100">
          <i class="fas fa-calendar-alt text-blue-500 text-sm"></i>
          <h2 class="text-base font-semibold text-gray-900">Periode Akademik</h2>
          @if ($ujian->periodeAkademik)
            <span class="ml-auto px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">
              {{ $ujian->periodeAkademik->tahun_ajaran }} — {{ ucfirst($ujian->periodeAkademik->semester) }}
            </span>
          @endif
        </div>
        <div class="px-6 py-5">
          <div class="flex flex-col gap-1.5">
            <label class="text-xs font-semibold text-gray-700">Pilih Periode <span class="text-red-500">*</span></label>
            <select name="periode_akademik_id" required
              class="px-3 py-2.5 border {{ $errors->has('periode_akademik_id') ? 'border-red-300 ring-1 ring-red-100 bg-red-50' : 'border-gray-200 bg-gray-50' }} rounded-lg text-sm text-gray-900 focus:outline-none focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100 transition-all">
              <option value="">-- Pilih Periode Akademik --</option>
              @foreach ($periodeAkademik as $p)
                <option value="{{ $p->id }}"
                  {{ old('periode_akademik_id', $ujian->periode_akademik_id) == $p->id ? 'selected' : '' }}>
                  {{ $p->tahun_ajaran }} — Semester {{ ucfirst($p->semester) }}
                  @if ($p->status === 'aktif') (Aktif) @endif
                </option>
              @endforeach
            </select>
            @error('periode_akademik_id')
              <span class="text-xs text-red-500"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</span>
            @enderror
            <p class="text-xs text-gray-400 mt-0.5">Pilih periode akademik yang berlaku untuk ujian ini sebelum membuat surat undangan.</p>
          </div>
        </div>
      </div>

      {{-- Card Detail Surat --}}
      <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
        <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
          <h2 class="text-base font-semibold text-gray-900 flex items-center gap-2">
            <i class="fas fa-file-alt text-gray-400 text-sm"></i>
            Detail Surat
          </h2>
        </div>
        <div class="flex flex-col gap-4 px-6 py-5">
          <div class="flex flex-col gap-1.5">
            <label class="text-xs font-semibold text-gray-700">Nomor Surat <span class="text-red-500">*</span></label>
            <input name="nomor_surat"
              class="px-3 py-2.5 border {{ $errors->has('nomor_surat') ? 'border-red-300 ring-1 ring-red-100 bg-red-50' : 'border-gray-200 bg-gray-50' }} rounded-lg text-sm text-gray-900 focus:outline-none focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100 transition-all"
              type="text" required placeholder="Masukkan Nomor Surat"
              value="{{ old('nomor_surat', '/UN29.13.2.1/PP/' . now()->year) }}" />
            @error('nomor_surat')
              <span class="text-xs text-red-500"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</span>
            @enderror
          </div>
          <div class="flex flex-col gap-1.5">
            <label class="text-xs font-semibold text-gray-700">Tanggal Surat <span class="text-red-500">*</span></label>
            <input name="tanggal_surat" onclick="this.showPicker()"
              class="px-3 py-2.5 border {{ $errors->has('tanggal_surat') ? 'border-red-300 ring-1 ring-red-100 bg-red-50' : 'border-gray-200 bg-gray-50' }} rounded-lg text-sm text-gray-900 focus:outline-none focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100 transition-all"
              type="date" required placeholder="Pilih Tanggal Surat"
              value="{{ old('tanggal_surat', optional($ujian->undanganUjian?->tanggal_surat)->format('Y-m-d')) }}" />
            @error('tanggal_surat')
              <span class="text-xs text-red-500"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</span>
            @enderror
          </div>
          <div class="flex flex-col gap-1.5">
            <label class="text-xs font-semibold text-gray-700">Perihal <span class="text-red-500">*</span></label>
            <input name="hal"
              class="px-3 py-2.5 border {{ $errors->has('hal') ? 'border-red-300 ring-1 ring-red-100 bg-red-50' : 'border-gray-200 bg-gray-50' }} rounded-lg text-sm text-gray-900 focus:outline-none focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100 transition-all"
              type="text" required placeholder="Masukkan Perihal Surat"
              value="{{ old('hal', $ujian->undanganUjian->hal ?? 'Undangan Seminar ' . ucfirst($ujian->jenis_ujian)) }}" />
            @error('hal')
              <span class="text-xs text-red-500"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</span>
            @enderror
          </div>
          <div class="flex flex-col gap-1.5">
            <label class="text-xs font-semibold text-gray-700">Ketua Sidang</label>
            <input type="hidden" name="ketua_sidang_id" value="{{ $ketuaSidang->id ?? '' }}">
            <input name="ketua_sidang"
              class="px-3 py-2.5 border border-gray-200 rounded-lg text-sm text-gray-900 bg-gray-50 focus:outline-none focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100 transition-all"
              type="text" readonly value="{{ $ketuaSidang->nama_lengkap ?? '' }}" />
          </div>
          <div class="flex flex-col gap-1.5">
            <label class="text-xs font-semibold text-gray-700">Sekertaris Sidang <span
                class="text-red-500">*</span></label>
            <select name="sekretaris_sidang_id" required
              class="px-3 py-2.5 border {{ $errors->has('sekretaris_sidang_id') ? 'border-red-300 ring-1 ring-red-100 bg-red-50' : 'border-gray-200 bg-gray-50' }} rounded-lg text-sm text-gray-900 focus:outline-none focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100 transition-all"
              placeholder="Masukkan Nama Sekertaris Sidang">
              <option value="">Pilih Sekertaris Sidang</option>
              <option value="{{ $ketuaJurusan->id }}"
                {{ old('sekretaris_sidang_id', $sekretarisJurusan->id) == $ketuaJurusan->id ? 'selected' : '' }}>
                {{ $ketuaJurusan->nama_lengkap }} - Ketua Jurusan
              </option>
              <option value="{{ $sekretarisJurusan->id }}"
                {{ old('sekretaris_sidang_id', $sekretarisJurusan->id) == $sekretarisJurusan->id ? 'selected' : '' }}>
                {{ $sekretarisJurusan->nama_lengkap }} - Sekertaris Jurusan
              </option>
            </select>
            @error('sekretaris_sidang_id')
              <span class="text-xs text-red-500"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</span>
            @enderror
          </div>
        </div>
        <div class="px-4 sm:px-6 py-4 sm:py-5 bg-gray-50 border-t border-gray-100">
          <button type="submit"
            class="w-full flex items-center justify-center gap-2 py-2.5 sm:py-3 px-4 sm:px-6 rounded-xl bg-gradient-to-r from-blue-600 to-blue-700 text-white text-xs sm:text-sm font-semibold shadow-[0_4px_12px_rgba(37,99,235,0.3)] hover:shadow-[0_6px_20px_rgba(37,99,235,0.4)] hover:-translate-y-0.5 transition-all cursor-pointer border-none">
            <i class="fas fa-file-pdf"></i>
            Generate PDF
          </button>
        </div>
      </div>
    </form>

    {{-- Berkas Card --}}
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
      <div class="flex items-center justify-between p-4 sm:px-6 sm:py-5 border-b border-gray-100">
        <h2 class="text-sm sm:text-base font-semibold text-gray-900 flex items-center gap-2">
          <i class="fas fa-paperclip text-gray-400 text-sm"></i>
          Berkas Undangan
        </h2>
      </div>
      @if ($ujian->undanganUjian && $ujian->undanganUjian->file_path)
        <div
          class="flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-4 mx-4 my-4 sm:mx-6 sm:my-5 p-3 sm:p-4 bg-gray-50 rounded-xl">
          <div class="flex items-center gap-3 sm:gap-4 w-full sm:w-auto sm:flex-1 min-w-0">
            <div
              class="w-10 h-10 sm:w-11 sm:h-11 rounded-xl bg-gradient-to-br from-red-500 to-red-600 text-white flex items-center justify-center text-base sm:text-lg shrink-0">
              <i class="fas fa-file-pdf"></i>
            </div>
            <div class="flex-1 min-w-0">
              <div class="text-xs sm:text-sm font-semibold text-gray-900 break-all">
                {{ basename($ujian->undanganUjian->file_path) }}</div>
              <div class="text-[10px] sm:text-xs text-gray-500">PDF •
                {{ $ujian->undanganUjian->updated_at->translatedFormat('d F Y') }}
              </div>
            </div>
          </div>
          <div class="flex gap-2 w-full sm:w-auto mt-1 sm:mt-0">
            <a href="{{ Storage::url($ujian->undanganUjian->file_path) }}"
              class="flex-1 sm:flex-none flex items-center justify-center px-3 py-2 sm:py-1.5 rounded-md border border-gray-200 bg-white text-xs font-medium text-gray-700 hover:border-blue-500 hover:text-blue-600 transition-all cursor-pointer">
              <i class="fas fa-eye mr-1.5 sm:mr-0"></i> <span class="sm:hidden">Lihat</span>
            </a>
            <a href="{{ Storage::url($ujian->undanganUjian->file_path) }}" download
              class="flex-1 sm:flex-none flex items-center justify-center px-3 py-2 sm:py-1.5 rounded-md border border-gray-200 bg-white text-xs font-medium text-gray-700 hover:border-blue-500 hover:text-blue-600 transition-all cursor-pointer">
              <i class="fas fa-download mr-1.5 sm:mr-0"></i> <span class="sm:hidden">Unduh</span>
            </a>
          </div>
        </div>
      @else
        <div class="flex flex-col items-center justify-center gap-2 p-6 sm:p-8 text-center text-gray-400">
          <i class="fas fa-file-circle-question text-xl sm:text-2xl"></i>
          <p class="text-xs sm:text-sm">Belum ada berkas undangan.<br>Generate PDF terlebih dahulu.</p>
        </div>
      @endif
    </div>
  </div>

  {{-- Action Footer --}}
  <div x-data="{ showConfirmModal: false, showSuccessModal: {{ session('show_success_modal') ? 'true' : 'false' }} }">
    <form method="POST" action="{{ route('admin.ujian.syarat.undangan.kirim', $ujian->id) }}">
      @csrf
      <div
        class="flex items-center justify-between p-4 sm:p-6 bg-white rounded-2xl border border-gray-200 mt-6 max-sm:flex-col max-sm:gap-4 max-sm:text-center">
        <div class="flex items-center gap-3">
          <p class="text-[10px] sm:text-xs text-gray-500">Undangan akan dikirim ke semua Pembimbing, Penguji dan Mahasiswa</p>
        </div>
        <div class="flex gap-3 max-sm:w-full">
          <button type="button" @click="showConfirmModal = true"
            class="flex items-center gap-2 px-4 sm:px-6 py-2 sm:py-2.5 rounded-lg bg-emerald-500 text-white text-xs sm:text-sm font-semibold shadow-[0_2px_8px_rgba(16,185,129,0.3)] hover:bg-emerald-600 hover:shadow-[0_4px_12px_rgba(16,185,129,0.4)] transition-all cursor-pointer border-none max-sm:flex-1 max-sm:justify-center">
            <i class="fas fa-paper-plane"></i>
            Kirim Undangan
          </button>
        </div>
      </div>

      <x-modal-confirm model="showConfirmModal" title="Konfirmasi Pengiriman" icon="fas fa-paper-plane" theme="blue"
        confirmText="Ya, Kirim Sekarang" cancelText="Batal">
        Apakah Anda yakin ingin mengirim undangan ujian ini? Undangan akan segera dikirimkan kepada seluruh Dosen
        Pembimbing, Dosen Penguji, dan Mahasiswa yang bersangkutan.
      </x-modal-confirm>
    </form>

    {{-- Success Modal --}}
    <div x-show="showSuccessModal" style="display: none;" class="relative z-50">
      <div x-show="showSuccessModal" x-transition.opacity class="fixed inset-0 transition-opacity bg-black/50"></div>
      <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex items-center justify-center min-h-full p-4 text-center sm:p-0">
          <div x-show="showSuccessModal" x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            class="relative px-4 pt-5 pb-4 text-left transition-all transform bg-white shadow-xl overflow-hidden rounded-xl sm:my-8 sm:w-full sm:max-w-sm sm:p-6">
            <div>
              <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-emerald-100">
                <i class="fas fa-check text-xl text-emerald-600"></i>
              </div>
              <div class="mt-3 text-center sm:mt-5">
                <h3 class="text-lg font-semibold leading-6 text-gray-900">Berhasil Dikirim!</h3>
                <div class="mt-2">
                  <p class="text-sm text-gray-500">Undangan telah berhasil dikirim ke seluruh pihak yang bersangkutan.
                  </p>
                </div>
              </div>
            </div>
            <div class="mt-5 sm:mt-6">
              <a href="{{ route('admin.ujian.syarat.index') }}"
                class="inline-flex w-full justify-center rounded-lg bg-emerald-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-600">
                Oke, Kembali ke Daftar
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
