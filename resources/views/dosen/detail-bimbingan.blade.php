@extends('layouts.app')

@section('title', 'Detail Bimbingan')

@section('sidebar')
  @if (auth()->user()->role === 'kajur')
    @include('kajur.sidebar')
  @else
    @include('dosen.sidebar')
  @endif
@endsection

@section('content')
  @php
    $isPending = $submission->status === 'pending';
  @endphp

  <!-- Page Header -->
  <div class="mb-8">
    <div class="flex items-center gap-3 mb-4">
      <a href="{{ route('dosen.bimbingan.index') }}" class="text-gray-500 hover:text-gray-700 transition-colors">
        <i class="fas fa-arrow-left"></i>
      </a>
      <h1 class="text-3xl font-bold text-gray-900">Detail Bimbingan</h1>
    </div>
    <p class="text-base text-gray-500">
      Review dan berikan feedback untuk submission mahasiswa
    </p>
  </div>

  <!-- Flash Messages -->
  <x-alert type="success" />
  <x-alert type="error" />
  <x-alert type="warning" />

  <div class="grid grid-cols-1 gap-6">
    <!-- Student Info & Files -->
    <div class="w-full space-y-6">
      <!-- Student Info Card -->
      <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="border-b border-gray-200 px-6 py-4">
          <h3 class="font-semibold text-gray-900">Informasi Mahasiswa</h3>
        </div>
        <div class="p-6">
          <div class="flex items-start gap-4">
            <div
              class="w-16 h-16 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-2xl flex-shrink-0">
              <i class="fas fa-user-graduate"></i>
            </div>
            <div class="flex-1">
              <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                <div>
                  <h4 class="text-lg font-semibold text-gray-900">{{ $submission->tugasAkhir->mahasiswa->nama_lengkap }}
                  </h4>
                  <p class="text-sm text-gray-500 mb-3">NIM: {{ $submission->tugasAkhir->mahasiswa->nim }}</p>
                </div>
                <span
                  class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">
                  {{ $submission->tugasAkhir->tahapan }}
                </span>
              </div>
              <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-xs text-gray-500 mb-1">Judul Tugas Akhir</p>
                <p class="text-sm font-medium text-gray-900">
                  {{ $submission->tugasAkhir->judul }}
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Submission Files Card -->
      <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="border-b border-gray-200 px-6 py-4">
          <h3 class="font-semibold text-gray-900">File Submission</h3>
        </div>
        <div class="p-6">
          <div class="space-y-3">
            <!-- File 1 -->
            @foreach ($submission->submissionFiles as $file)
              <x-file-preview-item :path="$file->file_path" type="submission-file" :file-id="$file->id" :uploaded-at="$file->created_at" />
            @endforeach
          </div>

        </div>
      </div>

      <!-- Student Notes Card -->
      <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="border-b border-gray-200 px-6 py-4">
          <h3 class="font-semibold text-gray-900">Catatan dari Mahasiswa</h3>
        </div>
        <div class="p-6">
          <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-start gap-3">
              <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center flex-shrink-0">
                <i class="fas fa-comment"></i>
              </div>
              <div>
                <p class="text-sm text-gray-700 leading-relaxed">
                  {{ $submission->catatan }}
                </p>
                <p class="text-xs text-gray-500 mt-2">Disubmit pada: {{ $submission->created_at }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Review Form -->
      @if ($isPending)
        <div class="bg-white rounded-xl shadow-sm overflow-hidden pb-4">
          <div class="border-b border-gray-200 px-6 py-4">
            <h3 class="font-semibold text-gray-900">Form Review</h3>
          </div>
          <div class="px-6">
            <form x-data="{ showConfirmModal: false }" action="{{ route('dosen.bimbingan.review', ['submission' => $submission->id]) }}" method="POST"
              enctype="multipart/form-data" class="space-y-5">
              @method('PUT')
              @csrf

              <!-- Review Textarea -->
              <div>
                <label for="review" class="block text-sm font-medium text-gray-700 mb-2">Catatan Review</label>
                <textarea id="review" name="review" rows="6"
                  class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-700 transition-all focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-600/10 resize-none"
                  placeholder="Tulis catatan atau feedback untuk mahasiswa..."></textarea>
              </div>

              <!-- File Upload -->
              <x-file-upload name="files[]" accept=".pdf,.doc,.docx" :multiple="true" :max-mb="10"
                label="Upload File (Opsional)" class="mb-1" />

              <!-- Status Selection -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status Review</label>
                <div class="grid grid-cols-3 gap-2">
                  <label
                    class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors has-[:checked]:border-green-500 has-[:checked]:bg-green-50">
                    <input type="radio" name="status" value="acc"
                      class="w-4 h-4 text-green-600 border-gray-300 focus:ring-green-500">
                    <span class="ml-3 flex items-center gap-2">
                      <span
                        class="w-6 h-6 rounded-full bg-green-100 text-green-600 flex items-center justify-center text-xs">
                        <i class="fas fa-check"></i>
                      </span>
                      <span class="text-sm font-medium text-gray-900">ACC / Disetujui</span>
                    </span>
                  </label>
                  <label
                    class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors has-[:checked]:border-yellow-500 has-[:checked]:bg-yellow-50">
                    <input type="radio" name="status" value="revisi"
                      class="w-4 h-4 text-yellow-600 border-gray-300 focus:ring-yellow-500">
                    <span class="ml-3 flex items-center gap-2">
                      <span
                        class="w-6 h-6 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center text-xs">
                        <i class="fas fa-edit"></i>
                      </span>
                      <span class="text-sm font-medium text-gray-900">Revisi</span>
                    </span>
                  </label>
                  <label
                    class="flex items-center p-3 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors has-[:checked]:border-red-500 has-[:checked]:bg-red-50">
                    <input type="radio" name="status" value="reject"
                      class="w-4 h-4 text-red-600 border-gray-300 focus:ring-red-500">
                    <span class="ml-3 flex items-center gap-2">
                      <span class="w-6 h-6 rounded-full bg-red-100 text-red-600 flex items-center justify-center text-xs">
                        <i class="fas fa-times"></i>
                      </span>
                      <span class="text-sm font-medium text-gray-900">Ditolak</span>
                    </span>
                  </label>
                </div>
              </div>

              <!-- Submit Button -->
              <div class="pt-2 flex justify-end">
                <button type="button" @click="showConfirmModal = true"
                  class="bg-blue-600 text-white py-2 px-4 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2">
                  <i class="fas fa-paper-plane mr-2"></i>Kirim Review
                </button>
              </div>

              <!-- Confirmation Modal -->
              <x-modal-confirm title="Kirim Review?" model="showConfirmModal" confirmText="Ya, Kirim" theme="blue">
                <p>Apakah Anda yakin ingin mengirim review ini?</p>
                <p class="mt-1">Pastikan catatan dan status sudah benar.</p>
              </x-modal-confirm>
            </form>
          </div>
        @endif

        <!-- Success Modal -->
        @if (session('show_modal'))
          <x-result-modal :status="session('show_modal')"  href="{{ route('dosen.bimbingan.index') }}" />
        @endif
      </div>
    </div>
  @endsection
