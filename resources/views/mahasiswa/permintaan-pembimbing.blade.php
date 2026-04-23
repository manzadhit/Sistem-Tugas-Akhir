@extends('layouts.app')

@section('title', 'Pengajuan Dosen Pembimbing')

@section('content')
  @php
    $isWaitingForPembimbing = $permintaanPembimbing && $permintaanPembimbing->status_verifikasi_bukti !== 'ditolak';
  @endphp

  <div class="mx-auto max-w-6xl space-y-4">
    {{-- Banner --}}
    <div class="relative overflow-hidden rounded-xl bg-gradient-to-br from-blue-600 to-blue-800">
      <div class="flex min-h-28 items-center justify-center px-5 py-5 text-center text-white">
        <div class="max-w-3xl">
          <h1 class="text-lg font-bold sm:text-xl md:text-2xl">
            {{ $isWaitingForPembimbing ? 'Menunggu penetapan pembimbing' : 'Ajukan topik untuk rekomendasi pembimbing' }}
          </h1>
          <p class="mt-1 text-xs opacity-90 sm:text-sm">
            {{ $isWaitingForPembimbing ? 'Pengajuan diterima. Sistem akan mengecek status secara berkala.' : 'Lengkapi data, unggah bukti ACC, lalu tunggu hasil rekomendasi.' }}
          </p>
        </div>
      </div>
    </div>

    {{-- Step bar --}}
    <div class="rounded-xl bg-white p-4 shadow-sm">
      <div class="relative flex justify-between gap-4">
        <div class="absolute left-[20%] right-[20%] top-[18px] h-0.5 bg-gray-200"></div>

        <div class="relative z-10 flex flex-1 flex-col items-center">
          <div
            class="mb-2 flex h-9 w-9 items-center justify-center rounded-full bg-blue-600 text-sm text-white shadow-[0_0_0_4px_rgba(37,99,235,0.18)]">
            <i class="fas fa-file-lines"></i>
          </div>
          <span class="text-center text-[10px] font-semibold text-blue-600 sm:text-xs">Pengajuan</span>
        </div>

        <div class="relative z-10 flex flex-1 flex-col items-center">
          <div class="mb-2 flex h-9 w-9 items-center justify-center rounded-full bg-gray-200 text-sm text-gray-400">
            <i class="fas fa-user-check"></i>
          </div>
          <span class="text-center text-[10px] font-medium text-gray-500 sm:text-xs">Rekomendasi</span>
        </div>
      </div>
    </div>

    @if (session('success'))
      <div class="rounded-lg bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
        {{ session('success') }}
      </div>
    @endif

    @if (session('warning'))
      <div class="rounded-lg bg-amber-50 px-4 py-3 text-sm text-amber-800">
        {{ session('warning') }}
      </div>
    @endif

    @if ($permintaanPembimbing?->catatan)
      <div class="rounded-lg bg-red-50 px-4 py-3 text-sm text-red-800">
        <p class="font-semibold">Pengajuan sebelumnya ditolak</p>
        <p class="mt-1">{{ $permintaanPembimbing->catatan }}</p>
      </div>
    @endif

    @if ($isWaitingForPembimbing)
      <section class="overflow-hidden rounded-xl bg-white shadow-sm">
        <div class="px-5 py-10 text-center">
          <div class="mx-auto mb-4 h-12 w-12 animate-spin rounded-full border-4 border-blue-100 border-t-blue-700"></div>

          <h3 class="text-base font-bold text-gray-900 sm:text-lg">Menunggu Penetapan Pembimbing</h3>
          <p class="mx-auto mt-2 max-w-xl text-sm text-gray-600">
            Permintaan pembimbing sudah diterima dan sedang menunggu penetapan dari ketua jurusan.
          </p>
          <p class="mt-1 text-xs text-gray-500">Halaman ini akan mengecek status secara berkala.</p>

          <div class="mt-5">
            <a href="{{ route('mahasiswa.permintaan-pembimbing.create') }}"
              class="inline-flex items-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-700">
              Cek Sekarang
            </a>
          </div>
        </div>
      </section>
    @else
      <section class="overflow-hidden rounded-xl bg-white shadow-sm">
        <div class="border-b border-gray-200 bg-gray-50/60 px-8 py-5">
          <div class="flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100 text-blue-600">
              <i class="fas fa-clipboard-list text-lg"></i>
            </div>
            <div>
              <h3 class="text-base font-bold text-gray-900 sm:text-lg">Form Pengajuan</h3>
              <p class="mt-0.5 text-sm text-gray-500">Isi data sesuai judul yang sudah disetujui.</p>
            </div>
          </div>
        </div>

        <div class="px-8 pb-4 mx-2 mt-6 mb-2 ">

          <form method="POST" action="{{ route('mahasiswa.permintaan-pembimbing.store') }}" enctype="multipart/form-data"
            class="" x-data="{ judul_ta: @js(old('judul_ta', $permintaanPembimbing?->judul_ta ?? '')) }">
            @csrf

            <div>
              <div class="max-w-xl space-y-2">
                <label class="flex items-center gap-2 text-sm font-bold text-gray-700">
                  <i class="fas fa-chart-line text-blue-500"></i>
                  IPK <span class="text-red-500">*</span>
                </label>

                <div class="flex flex-col gap-2 md:flex-row md:items-center md:gap-3">
                  <div class="w-full md:w-44 md:flex-none">
                    <input type="number" name="ipk" value="{{ old('ipk', $mahasiswa->ipk) }}" required step="0.01"
                      min="0" max="4.00" placeholder="0.00 - 4.00"
                      class="w-full rounded-lg border border-gray-300 px-3 py-3 text-[0.95rem] transition-colors focus:border-blue-600 focus:outline-none focus:ring-4 focus:ring-blue-100">
                  </div>

                  <span class="text-xs leading-relaxed text-gray-500 md:flex-1">Digunakan untuk pemerataan penyebaran
                    mahasiswa berdasarkan IPK.</span>
                </div>


                @error('ipk')
                  <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
              </div>

              <div class="mt-6 space-y-2">
                <label class="flex items-center gap-2 text-sm font-bold text-gray-700">
                  <i class="fas fa-heading text-blue-500"></i>
                  Judul Tugas Akhir <span class="text-red-500">*</span>
                </label>

                <textarea name="judul_ta"
                  class="min-h-[80px] w-full rounded-lg border border-gray-300 px-3 py-3 text-[0.95rem] transition-colors focus:border-blue-600 focus:outline-none focus:ring-4 focus:ring-blue-100"
                  maxlength="500" placeholder="Contoh: Sistem Rekomendasi Pemilihan Dosen Pembimbing Berbasis Machine Learning"
                  x-model="judul_ta">{{ old('judul_ta', $permintaanPembimbing?->judul_ta) }}</textarea>

                <div class="text-right text-xs text-gray-500">
                  <span x-text="judul_ta.length"></span>/500 karakter
                </div>

                @error('judul_ta')
                  <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
              </div>

              <div class="space-y-2">
                <label class="flex items-center gap-2 text-sm font-bold text-gray-700">
                  <i class="fas fa-file-circle-check text-blue-500"></i>
                  Bukti ACC <span class="text-red-500">*</span>
                </label>

                <div
                  class="relative overflow-hidden transition bg-white border border-gray-300 rounded-lg focus-within:border-blue-600 focus-within:ring-4 focus-within:ring-blue-100">
                  <input type="file" name="bukti_acc" id="bukti_acc" accept=".pdf,.jpg,.jpeg,.png" required
                    onchange="document.getElementById('label-bukti_acc').textContent = this.files[0]?.name ?? 'Pilih file'"
                    class="absolute inset-0 z-10 w-full h-full opacity-0 cursor-pointer" />
                  <div class="flex items-stretch">
                    <div
                      class="flex items-center flex-shrink-0 gap-2 px-5 py-3 text-sm font-medium text-gray-700 transition bg-gray-100 hover:bg-gray-200">
                      <i class="fas fa-cloud-upload-alt"></i>
                      Browse File
                    </div>
                    <div class="flex items-center flex-1 px-4 py-3 text-sm text-gray-500 border-l border-gray-200"
                      id="label-bukti_acc">
                      Pilih file (PDF, JPG, PNG — maks 2MB)
                    </div>
                  </div>
                </div>

                @error('bukti_acc')
                  <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
              </div>

              <div class="mt-6 space-y-2">
                <label class="flex items-center gap-2 text-sm font-bold text-gray-700">
                  <i class="fas fa-book-open text-blue-500"></i>
                  Mata Kuliah Relevan <span class="text-red-500">*</span>
                </label>

                <x-multi-select name="mata_kuliah_ids" :options="$mataKuliahOptions" :selected="old('mata_kuliah_ids', $permintaanPembimbing?->mataKuliah?->pluck('id')->toArray() ?? [])"
                  placeholder="Pilih mata kuliah relevan..." search-placeholder="Cari mata kuliah..."
                  empty-text="Mata kuliah tidak ditemukan." />

                <p class="text-xs text-gray-500">Pilih minimal 1 mata kuliah.</p>

                @error('mata_kuliah_ids')
                  <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
                @error('mata_kuliah_ids.*')
                  <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
              </div>
            </div>

            <div class="mt-2 flex justify-end">
              <x-primary-button
                class="inline-flex items-center justify-center gap-2 rounded-lg px-4 py-2 md:px-6 md:py-3">
                <span>Kirim</span>
                <i class="fas fa-paper-plane"></i>
              </x-primary-button>
            </div>
          </form>
        </div>
      </section>
    @endif
  </div>
@endsection
