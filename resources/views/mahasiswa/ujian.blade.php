@extends('layouts.app')

@section('title', 'Ujian')

@section('sidebar')
  @include('mahasiswa.sidebar')
@endsection

@section('content')

  {{-- Page Banner --}}
  <div class="relative h-40 rounded-xl overflow-hidden mb-8 bg-gradient-to-br from-blue-600 to-blue-800">
    <div class="absolute inset-0 flex flex-col items-center justify-center text-white text-center px-4">
      <i class="fas fa-graduation-cap text-4xl mb-3"></i>
      <h1 class="text-2xl font-bold mb-1">Pengajuan Ujian {{ ucfirst($jenis) }}</h1>
      <p class="text-base opacity-90">Lengkapi dokumen persyaratan untuk mengajukan ujian {{ $jenis }}</p>
    </div>
  </div>

  {{-- Progress Bar --}}
  <div class="bg-white rounded-xl p-6 shadow-sm mb-8">
    <div class="flex justify-between relative">
      {{-- Garis penghubung --}}
      <div class="absolute top-5 left-[15%] right-[15%] h-0.5 bg-gray-200 z-0"></div>

      {{-- Step: Upload Syarat (active) --}}
      <div class="flex flex-col items-center relative z-10 flex-1">
        <div
          class="w-10 h-10 rounded-full flex items-center justify-center text-base mb-2 bg-blue-600 text-white ring-4 ring-blue-100">
          <i class="fas fa-file-upload"></i>
        </div>
        <span class="text-xs font-semibold text-blue-600 text-center">Upload Syarat</span>
      </div>

      {{-- Step: Jadwal (pending) --}}
      <div class="flex flex-col items-center relative z-10 flex-1">
        <div class="w-10 h-10 rounded-full flex items-center justify-center text-base mb-2 bg-gray-200 text-gray-400">
          <i class="fas fa-calendar-alt"></i>
        </div>
        <span class="text-xs font-medium text-gray-500 text-center">Jadwal</span>
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
  <div class="flex items-start gap-3 bg-amber-50 border border-amber-300 rounded-lg p-4 mb-6">
    <i class="fas fa-info-circle text-amber-600 text-xl flex-shrink-0 mt-0.5"></i>
    <div>
      <h4 class="text-sm font-semibold text-amber-800 mb-1">Informasi Penting</h4>
      <p class="text-xs text-amber-700">
        Pastikan semua dokumen yang diupload sudah benar dan lengkap.
        Dokumen yang tidak sesuai akan dikembalikan untuk diperbaiki.
      </p>
    </div>
  </div>

  <!-- Flash Messages -->
  <x-alert type="success" />
  <x-alert type="error" />
  <x-alert type="warning" />

  {{-- Form Card --}}
  <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-8">
    <div class="px-6 py-5 border-b border-gray-200 flex items-center gap-3">
      <i class="fas fa-file-upload text-blue-600 text-xl"></i>
      <h3 class="text-lg font-semibold text-gray-900">Upload Dokumen Persyaratan</h3>
    </div>
    <div class="p-6">
      <form id="ujianForm" action="{{ route('mahasiswa.ujian.upload.dokumen', ['jenis' => $jenis]) }}" method="POST"
        enctype="multipart/form-data">
        @csrf

        @foreach ($daftarSyarat as $syarat)
          <div class="mb-6">
            <label class="block text-sm font-semibold text-gray-700 mb-1">
              {{ $syarat['label'] }} <span class="text-red-600">*</span>
            </label>
            <p class="text-xs text-gray-500 mb-2">{{ $syarat['desc'] }}</p>

            <div
              class="relative border-2 border-blue-600 rounded-xl overflow-hidden bg-white transition focus-within:ring-4 focus-within:ring-blue-100">
              <input type="file" name="files[{{ $syarat['name'] }}]" id="{{ $syarat['name'] }}" accept=".pdf" required
                onchange="document.getElementById('label-{{ $syarat['name'] }}').textContent = this.files[0]?.name ?? '{{ $syarat['placeholder'] }}'"
                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" />
              <div class="flex items-stretch">
                <div
                  class="bg-blue-800 hover:bg-blue-900 text-white px-5 py-3 flex items-center gap-2 text-sm font-medium flex-shrink-0 transition">
                  <i class="fas fa-cloud-upload-alt"></i>
                  Browse File
                </div>
                <div class="flex-1 px-4 py-3 bg-gray-50 border-l border-gray-200 text-sm text-gray-500 flex items-center"
                  id="label-{{ $syarat['name'] }}">
                  {{ $syarat['placeholder'] }}
                </div>
              </div>
            </div>

            @error('files.' . $syarat['name'])
              <p class="text-xs text-red-600 mt-1.5"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
            @enderror

            <p class="text-xs text-gray-500 mt-1">Format: PDF. Maksimal 10MB</p>
          </div>
        @endforeach

        {{-- Action Buttons --}}
        <div class="flex justify-end gap-3 pt-6 border-t border-gray-200 mt-6">
          <button type="submit"
            class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition">
            <i class="fas fa-paper-plane"></i>
            Submit
          </button>
        </div>

      </form>
    </div>
  </div>

@endsection
