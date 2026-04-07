@extends('layouts.app')

@section('title', 'Detail Verifikasi Hasil Ujian')

@section('sidebar')
  @include('admin.sidebar')
@endsection

@section('content')

  {{-- Page Header (Breadcrumb) --}}
  <div class="flex items-start justify-between gap-4 mb-8">
    <div>
      <nav class="flex items-center gap-1.5 mb-2 text-sm text-gray-500">
        <a href="{{ route('admin.ujian.hasil.index') }}" class="transition hover:text-blue-600">Daftar
          Pengajuan</a>
        <i class="fas fa-chevron-right text-[10px] text-gray-400"></i>
        <span class="font-medium text-gray-900">Verifikasi Berkas</span>
      </nav>
      <h1 class="text-2xl font-bold text-gray-900">Detail Verifikasi Hasil Ujian</h1>
    </div>
  </div>


  {{-- Flash Messages --}}
  <x-alert type="success" />
  <x-alert type="error" />
  <x-alert type="warning" />

  <div class="grid grid-cols-1 gap-6 mb-6 lg:grid-cols-4">

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
                @foreach ($ujian->tugasAkhir->mahasiswa->dosenPembimbing as $index => $p)
                  <li class="text-sm text-gray-900">
                    <span class="mr-1.5 text-gray-400">{{ $index + 1 }}.</span>{{ $p->dosen->nama_lengkap }}
                  </li>
                @endforeach
              </ol>
            </div>
            <div>
              <div class="mb-2 text-xs font-medium tracking-wider text-gray-400 uppercase">Dosen Penguji</div>
              <ol class="space-y-1">
                @foreach ($ujian->tugasAkhir->mahasiswa->dosenPenguji as $index => $p)
                  <li class="text-sm text-gray-900">
                    <span class="mr-1.5 text-gray-400">{{ $index + 1 }}.</span>{{ $p->dosen->nama_lengkap }}
                  </li>
                @endforeach
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

  {{-- Berkas Syarat --}}
  <div x-data="{ showModal: false, showSuccessModal: {{ session('show_success_modal') ? 'true' : 'false' }} }">
    <form id="verifikasiForm" method="POST" action="{{ route('admin.ujian.hasil.proses', $ujian->id) }}">
      @csrf
      <section class="mb-6 overflow-hidden bg-white shadow-sm rounded-xl">
        <div class="flex items-center justify-between gap-4 px-6 py-4 border-b border-gray-200">
          <div>
            <h2 class="text-base font-semibold text-gray-900">Berkas Hasil Ujian</h2>
            <p class="text-xs text-gray-500">Verifikasi setiap berkas, pilih ACC atau Tolak.</p>
          </div>
        </div>
        <div class="p-6 space-y-4">

          @php
            $dokumenPending = $ujian->dokumenUjian->where('status', 'pending');
            $dokumenAcc = $ujian->dokumenUjian->where('status', 'acc');
          @endphp

          {{-- Section: Sudah Disetujui (ACC) --}}
          @if ($dokumenAcc->isNotEmpty())
            <div class="{{ $dokumenPending->isNotEmpty() ? 'mb-8 pb-6 border-b border-gray-200' : '' }}">
              <h3 class="mb-3 text-sm font-bold text-gray-800">Sudah Disetujui (ACC)</h3>
              @foreach ($dokumenAcc as $index => $dokumen)
                <div class="flex items-start gap-4 p-4 border rounded-xl bg-green-50 border-green-300 mb-4">
                  <div
                    class="flex items-center justify-center flex-shrink-0 w-8 h-8 text-white bg-green-500 rounded-full">
                    <i class="fas fa-check text-sm"></i>
                  </div>
                  <div class="flex-1 min-w-0">
                    <div class="mb-2 text-sm font-semibold text-gray-800">
                      {{ $dokumen->nama_dokumen ?? basename($dokumen->file_path) }}</div>

                    {{-- File Preview using Component --}}
                    <x-file-preview-item :path="$dokumen->file_path" type="dokumen-ujian" :file-id="$dokumen->id" :uploadedAt="$dokumen->created_at" class="border-green-200 shadow-sm mb-0" />
                  </div>
                </div>
              @endforeach
            </div>
          @endif

          {{-- Section: Membutuhkan Verifikasi --}}
          @if ($dokumenPending->isNotEmpty())
            <h3 class="mb-3 text-sm font-bold text-gray-800">Membutuhkan Verifikasi</h3>
            @foreach ($dokumenPending as $index => $dokumen)
              <div x-data="{ status: 'acc' }"
                class="flex items-start gap-4 p-4 border rounded-xl bg-yellow-50 border-yellow-300 mb-4">
                <div class="flex items-center justify-center flex-shrink-0 w-8 h-8 text-white bg-yellow-400 rounded-full">
                  <i class="fas fa-exclamation text-sm"></i>
                </div>
                <div class="flex-1 min-w-0">
                  <div class="mb-2 text-sm font-semibold text-gray-800">
                    {{ $dokumen->nama_dokumen ?? basename($dokumen->file_path) }}</div>

                  {{-- File Preview using Component --}}
                  <x-file-preview-item :path="$dokumen->file_path" type="dokumen-ujian" :file-id="$dokumen->id" :uploadedAt="$dokumen->created_at" class="mb-3 border-yellow-200 shadow-sm" />

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
            @endforeach
          @endif


          @if ($dokumenPending->isEmpty() && $dokumenAcc->isEmpty())
            <div class="flex flex-col items-center justify-center gap-2 py-10 text-center text-gray-400">
              <i class="fas fa-folder-open text-2xl"></i>
              <p class="text-sm">Tidak ada berkas yang dilampirkan</p>
            </div>
          @endif

        </div>

        {{-- Submit Buttons --}}
        @if ($dokumenPending->isNotEmpty())
          <div class="flex flex-wrap items-center justify-end gap-3 px-6 py-4 border-t border-gray-200">
            <button type="button" @click="showModal = true"
              class="px-5 py-2.5 text-sm font-semibold text-white transition bg-blue-600 rounded-lg hover:bg-blue-700">
              <i class="fas fa-check mr-1.5"></i> Simpan Verifikasi
            </button>
          </div>
        @endif
      </section>

      {{-- Modal Konfirmasi --}}
      <x-modal-confirm title="Konfirmasi Verifikasi" confirmText="Ya, Simpan">
        <p class="mb-2">
          Apakah Anda yakin proses verifikasi sudah benar?
        </p>
        <ul class="list-disc pl-5 space-y-1 text-sm text-gray-500">
          <li>Jika ada yang <b>Ditolak</b>: Ujian dikembalikan ke tahap revisi hasil ujian.</li>
          <li>Jika semua <b>Di-ACC</b>: Ujian akan ditandai telah selesai / mahasiswa dinyatakan lulus tahap ini.</li>
        </ul>
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
                <h3 class="text-lg font-semibold leading-6 text-gray-900">Verifikasi Berhasil!</h3>
                <div class="mt-2">
                  <p class="text-sm text-gray-500">Semua berkas hasil ujian telah di-ACC. Ujian dinyatakan selesai.</p>
                </div>
              </div>
            </div>
            <div class="mt-5 sm:mt-6">
              <a href="{{ route('admin.ujian.hasil.index') }}"
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
