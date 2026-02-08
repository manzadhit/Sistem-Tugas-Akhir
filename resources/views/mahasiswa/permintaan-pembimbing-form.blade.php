@extends('layouts.app')

@section('title', 'Pengajuan Dosen Pembimbing')

@section('content')
  <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-3 mb-5">
    <div>
      <h1 class="text-2xl font-extrabold tracking-tight text-slate-900">
        Pengajuan Dosen Pembimbing
      </h1>
      <p class="text-slate-500 mt-1">Isi judul & unggah bukti ACC (maks. 2MB)</p>
    </div>

    <span
      class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-bold
                 bg-blue-50 text-blue-700 border border-blue-100 w-fit">
      <i class="fas fa-sparkles"></i> Form Pengajuan
    </span>
  </div>

  <section class="rounded-2xl border border-slate-200 bg-white/80 backdrop-blur shadow-sm">
    <div class="p-6 border-b border-slate-200 flex items-start justify-between gap-4">
      <div>
        <h3 class="font-extrabold text-slate-900">📝 Form Pengajuan</h3>
        <p class="text-sm text-slate-500 mt-1">Pastikan data sesuai dengan yang sudah disetujui.</p>
      </div>

    </div>

    <div class="p-6">
      <x-alert-info>
        <strong>Penting:</strong> Judul harus sudah ACC. File: PDF/JPG/PNG (maks. 2MB).
      </x-alert-info>

      <form method="POST" action="{{ route('mahasiswa.permintaan-pembimbing.store') }}" enctype="multipart/form-data"
        class="mt-5" x-data="pengajuanForm()">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
          <div>
            <label class="flex items-center gap-2 font-bold text-slate-900 mb-2">
              <i class="fas fa-book"></i>
              Judul Tugas Akhir <span class="text-red-500">*</span>
            </label>

            <textarea name="judul_ta"
              class="w-full min-h-[128px] rounded-xl border border-slate-200 bg-white px-4 py-3
                     text-sm focus:outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-400"
              maxlength="500" placeholder="Contoh: Sistem Rekomendasi Pemilihan Dosen Pembimbing Berbasis Machine Learning"
              x-model="judul_ta">{{ old('judul_ta') }}</textarea>

            <div class="mt-2 text-right text-xs text-slate-500">
              <span x-text="judul_ta.length"></span>/500 karakter
            </div>

            @error('judul_ta')
              <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>

          <div>
            <label class="flex items-center gap-2 font-bold text-slate-900 mb-2">
              <i class="fas fa-file-arrow-up"></i>
              Bukti ACC <span class="text-red-500">*</span>
            </label>

            <x-file-upload name="bukti_acc" accept=".pdf,.jpg,.jpeg,.png" max-mb="2" />

            @error('bukti_acc')
              <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>
        </div>

        <div class="mt-6 flex justify-end">
          <x-primary-button class="inline-flex justify-center items-center gap-2">
            <span>Kirim</span>
            <i class="fas fa-paper-plane"></i>
          </x-primary-button>
        </div>
      </form>
    </div>
  </section>

  <script>
    function pengajuanForm() {
      return {
        judul_ta: @js(old('judul_ta', '')),
      }
    }
  </script>
@endsection
