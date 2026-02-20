@extends('layouts.app')

@section('title', 'Jadwal Ujian')

@section('sidebar')
  @include('mahasiswa.sidebar')
@endsection

@section('content')
  {{-- Page Banner --}}
  <div class="relative mb-8 h-40 overflow-hidden rounded-xl bg-gradient-to-br from-blue-600 to-blue-800">
    <div class="absolute inset-0 flex flex-col items-center justify-center px-4 text-center text-white">
      <i class="fas fa-calendar-check mb-3 text-4xl"></i>
      <h1 class="mb-1 text-2xl font-bold">Jadwal Ujian</h1>
      <p class="text-base opacity-90">Input jadwal ujian yang sudah disetujui semua penguji</p>
    </div>
  </div>

  {{-- Progress Bar --}}
  <div class="bg-white rounded-xl p-6 shadow-sm mb-8">
    <div class="flex justify-between relative">
      {{-- Garis penghubung --}}
      <div class="absolute top-5 left-[15%] right-[15%] h-0.5 bg-gray-200 z-0"></div>

      {{-- Step: Upload Syarat (completed) --}}
      <div class="flex flex-col items-center relative z-10 flex-1">
        <div class="w-10 h-10 rounded-full flex items-center justify-center text-base mb-2 bg-emerald-500 text-white">
          <i class="fas fa-file-upload"></i>
        </div>
        <span class="text-xs font-semibold text-emerald-600 text-center">Upload Syarat</span>
      </div>

      {{-- Step: Jadwal (active) --}}
      <div class="flex flex-col items-center relative z-10 flex-1">
        <div
          class="w-10 h-10 rounded-full flex items-center justify-center text-base mb-2 bg-blue-600 text-white ring-4 ring-blue-100">
          <i class="fas fa-calendar-alt"></i>
        </div>
        <span class="text-xs font-semibold text-blue-600 text-center">Jadwal</span>
      </div>

      {{-- Step: Undangan (pending) --}}
      <div class="flex flex-col items-center relative z-10 flex-1">
        <div class="w-10 h-10 rounded-full flex items-center justify-center text-base mb-2 bg-gray-200 text-gray-400">
          <i class="fas fa-envelope-open-text"></i>
        </div>
        <span class="text-xs font-medium text-gray-500 text-center">Undangan</span>
      </div>

      {{-- Step: Selesai (pending) --}}
      <div class="flex flex-col items-center relative z-10 flex-1">
        <div class="w-10 h-10 rounded-full flex items-center justify-center text-base mb-2 bg-gray-200 text-gray-400">
          <i class="fas fa-check-circle"></i>
        </div>
        <span class="text-xs font-medium text-gray-500 text-center">Selesai</span>
      </div>
    </div>
  </div>

  {{-- Info Alert --}}
  <div class="mb-6 flex items-start gap-3 rounded-lg border border-emerald-200 bg-emerald-50 p-4">
    <i class="fas fa-check-circle mt-0.5 text-xl text-emerald-600"></i>
    <div>
      <h4 class="mb-1 text-sm font-semibold text-emerald-800">Jadwal Siap Ditetapkan</h4>
      <p class="text-xs text-emerald-700">
        Pastikan waktu, lokasi, dan metode ujian sudah disepakati semua penguji sebelum menyimpan jadwal.
      </p>
    </div>
  </div>

  {{-- Flash Messages --}}
  <x-alert type="success" />
  <x-alert type="error" />
  <x-alert type="warning" />

  {{-- Form Card --}}
  <div class="mb-8 overflow-hidden rounded-xl bg-white shadow-sm">
    <div class="flex items-center gap-3 border-b border-gray-200 px-6 py-5">
      <i class="fas fa-calendar-alt text-xl text-blue-600"></i>
      <h3 class="text-lg font-semibold text-gray-900">Input Jadwal Ujian</h3>
    </div>

    <div class="p-6">
      <form id="jadwalForm" class="space-y-6" action="{{ route('mahasiswa.addJadwal', ['jenis' => $jenis]) }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
          <div>
            <label for="tanggal_ujian" class="mb-1 block text-sm font-semibold text-gray-700">Tanggal Ujian</label>
            <input type="date" id="tanggal_ujian" name="tanggal_ujian" required value="{{ old('tanggal_ujian') }}"
              onclick="this.showPicker()"
              class="w-full rounded-lg border border-gray-300 px-3 py-3 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100 cursor-pointer" />
          </div>

          <div>
            <label for="jenis_ujian" class="mb-1 block text-sm font-semibold text-gray-700">Jenis Ujian</label>
            <select id="jenis_ujian" name="jenis_ujian" required
              class="w-full rounded-lg border border-gray-300 px-3 py-3 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
              <option value="">Pilih jenis ujian</option>
              <option value="proposal" @selected(old('jenis_ujian', $jenis) === 'proposal')>Ujian Proposal</option>
              <option value="hasil" @selected(old('jenis_ujian', $jenis) === 'hasil')>Ujian Hasil</option>
              <option value="skripsi" @selected(old('jenis_ujian', $jenis) === 'skripsi')>Ujian Skripsi</option>
            </select>
          </div>
        </div>

        <div>
          <label for="slot_waktu" class="mb-1 block text-sm font-semibold text-gray-700">Slot Waktu</label>
          <select id="slot_waktu" name="slot_waktu" required
            class="w-full rounded-lg border border-gray-300 px-3 py-3 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
            <option value="">Pilih slot waktu</option>
            <option value="08:00-09:00" @selected(old('slot_waktu') === '08:00-09:00')>08.00 – 09.00</option>
            <option value="09:30-11:00" @selected(old('slot_waktu') === '09:30-11:00')>09.30 – 11.00</option>
            <option value="13:30-15:00" @selected(old('slot_waktu') === '13:30-15:00')>13.30 – 15.00</option>
            <option value="15:00-16:30" @selected(old('slot_waktu') === '15:00-16:30')>15.00 – 16.30</option>
          </select>
        </div>

        <div>
          <label for="ruang_ujian" class="mb-1 block text-sm font-semibold text-gray-700">Ruang Ujian</label>
          <select id="ruang_ujian" name="ruang_ujian" required
            class="w-full rounded-lg border border-gray-300 px-3 py-3 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100">
            <option value="">Pilih ruangan</option>
            <option value="ruang-sidang-1" @selected(old('ruang_ujian') === 'ruang-sidang-1')>Ruang Sidang 1</option>
            <option value="ruang-sidang-2" @selected(old('ruang_ujian') === 'ruang-sidang-2')>Ruang Sidang 2</option>
            <option value="ruang-seminar" @selected(old('ruang_ujian') === 'ruang-seminar')>Ruang Seminar</option>
            <option value="lab-multimedia" @selected(old('ruang_ujian') === 'lab-multimedia')>Lab Multimedia</option>
            <option value="ruang-utama" @selected(old('ruang_ujian') === 'ruang-utama')>Ruang Utama</option>
          </select>
        </div>

        <div class="mt-6 flex flex-col-reverse gap-3 border-t border-gray-200 pt-6 sm:flex-row sm:justify-end">
          <a href="{{ route('mahasiswa.ujian', ['jenis' => $jenis ?? 'proposal']) }}"
            class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 bg-white px-5 py-3 text-sm font-medium text-gray-700 transition hover:bg-gray-50">
            <i class="fas fa-arrow-left"></i>
            Kembali
          </a>
          <button type="submit"
            class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-5 py-3 text-sm font-medium text-white transition hover:bg-blue-700">
            <i class="fas fa-save"></i>
            Simpan Jadwal
          </button>
        </div>
      </form>
    </div>
  </div>

@endsection
