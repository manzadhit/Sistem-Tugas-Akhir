@extends('layouts.app')

@section('title', 'Upload Hasil Ujian')

@section('sidebar')
  @include('mahasiswa.sidebar')
@endsection

@section('content')

  {{-- Page Banner --}}
  <div class="relative h-40 rounded-xl overflow-hidden mb-8 bg-gradient-to-br from-blue-600 to-blue-800">
    <div class="absolute inset-0 flex flex-col items-center justify-center text-white text-center px-4">
      <i class="fas fa-file-signature text-3xl sm:text-4xl mb-3"></i>
      <h1 class="text-xl sm:text-2xl md:text-[1.75rem] font-bold mb-1">Upload Hasil Ujian {{ ucfirst($jenis) }}</h1>
      <p class="text-xs sm:text-sm md:text-base opacity-90">
        @if ($ujian->status === 'menunggu_hasil')
          Lengkapi dokumen hasil ujian (Lembar Pengesahan, Form Revisi, dll) {{ $jenis }} Anda.
        @elseif ($ujian->status === 'revisi_hasil')
          Upload ulang dokumen hasil ujian yang ditolak oleh Admin.
        @elseif ($ujian->status === 'menunggu_verifikasi_hasil')
          Dokumen hasil ujian Anda sedang diverifikasi oleh Admin.
        @endif
      </p>
    </div>
  </div>

  {{-- Progress Bar --}}
  @include('mahasiswa.ujian.partials.progress-bar', ['activeStep' => 3])

  @if (in_array($ujian->status, ['menunggu_hasil', 'revisi_hasil']))
    {{-- Alert --}}
    @if ($isRevisi ?? false)
      <div class="flex items-start gap-3 p-4 mb-6 border rounded-lg bg-red-50 border-red-300">
        <i class="flex-shrink-0 text-xl mt-0.5 text-red-600 fas fa-exclamation-triangle"></i>
        <div>
          <h4 class="mb-1 text-sm font-semibold text-red-800">Dokumen Ditolak</h4>
          <p class="text-xs text-red-700">
            Beberapa dokumen hasil ujian Anda ditolak oleh Admin. Silakan upload ulang dokumen yang ditolak di bawah ini.
          </p>
        </div>
      </div>
    @else
      <div class="flex items-start gap-3 p-4 mb-6 border rounded-lg bg-amber-50 border-amber-300">
        <i class="flex-shrink-0 text-xl mt-0.5 text-amber-600 fas fa-info-circle"></i>
        <div>
          <h4 class="mb-1 text-sm font-semibold text-amber-800">Informasi Penting</h4>
          <p class="text-xs text-amber-700">
            Pastikan berkas hasil ujian (berita acara dan absensi kehadiran) telah lengkap dan disetujui
            Penguji maupun Pembimbing.
          </p>
        </div>
      </div>
    @endif

    <!-- Flash Messages -->
    <x-alert type="success" />
    <x-alert type="error" />
    <x-alert type="warning" />

    <div x-data="{ showModal: false }">
      <form id="ujianForm" action="{{ route('mahasiswa.ujian.submitHasilUjian', ['jenis' => $jenis]) }}" method="POST"
        enctype="multipart/form-data">
        @csrf

        {{-- Form Card: Upload Dokumen Hasil --}}
        <div class="overflow-hidden mb-6 bg-white shadow-sm rounded-xl border border-gray-200">
          <div class="flex items-center gap-3 px-6 py-5 border-b border-gray-100 bg-gray-50/50">
            <i class="text-xl {{ $isRevisi ?? false ? 'text-red-600' : 'text-blue-600' }} fas fa-file-upload"></i>
            <h3 class="text-base font-semibold text-gray-900">
              {{ $isRevisi ?? false ? 'Upload Ulang Hasil Ujian' : 'Upload Berkas Hasil Ujian' }}
            </h3>
          </div>
          <div class="p-6">
            @foreach ($daftarSyarat as $syarat)
              <div class="mb-6">
                {{-- Catatan Admin --}}
                @if (
                    ($isRevisi ?? false) &&
                        isset($rejectedDokumen) &&
                        $rejectedDokumen->has($syarat['name']) &&
                        $rejectedDokumen[$syarat['name']]->catatan)
                  <div class="flex items-start gap-2 p-3 mb-3 text-xs border rounded-lg bg-red-50 border-red-200">
                    <i class="flex-shrink-0 mt-0.5 text-red-500 fas fa-comment-alt"></i>
                    <div>
                      <span class="font-semibold text-red-700">Catatan Admin:</span>
                      <span class="text-red-600">{{ $rejectedDokumen[$syarat['name']]->catatan }}</span>
                    </div>
                  </div>
                @endif

                <label class="block mb-1 text-sm font-semibold text-gray-700">
                  {{ $syarat['label'] }} <span class="text-red-600">*</span>
                </label>
                <p class="mb-2 text-xs text-gray-500">{{ $syarat['desc'] }}</p>

                <div
                  class="relative overflow-hidden transition bg-white border-2 {{ $isRevisi ?? false ? 'border-red-500 focus-within:ring-red-100' : 'border-blue-600 focus-within:ring-blue-100' }} rounded-xl focus-within:ring-4">
                  <input type="file" name="files[{{ $syarat['name'] }}]" id="{{ $syarat['name'] }}" accept=".pdf"
                    required
                    onchange="document.getElementById('label-{{ $syarat['name'] }}').textContent = this.files[0]?.name ?? '{{ $syarat['placeholder'] ?? 'Pilih file' }}'"
                    class="absolute inset-0 z-10 w-full h-full opacity-0 cursor-pointer" />
                  <div class="flex items-stretch">
                    <div
                      class="flex items-center flex-shrink-0 gap-2 px-5 py-3 text-sm font-medium text-white transition {{ $isRevisi ?? false ? 'bg-red-600 hover:bg-red-700' : 'bg-blue-800 hover:bg-blue-900' }}">
                      <i class="fas fa-cloud-upload-alt"></i>
                      Browse File
                    </div>
                    <div
                      class="flex items-center flex-1 px-4 py-3 text-sm text-gray-500 border-l border-gray-200 bg-gray-50"
                      id="label-{{ $syarat['name'] }}">
                      {{ $syarat['placeholder'] ?? 'Pilih file' }}
                    </div>
                  </div>
                </div>

                @error('files.' . $syarat['name'])
                  <p class="text-xs text-red-600 mt-1.5"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                @enderror

                <p class="mt-1 text-xs text-gray-500">Format: PDF. Maksimal 10MB</p>
              </div>
            @endforeach
          </div>
        </div>

        {{-- Action Buttons --}}
        <div class="flex justify-end gap-3 pt-6 mt-6 border-t border-gray-200">
          <button type="button"
            @click="if(document.getElementById('ujianForm').checkValidity()) { showModal = true } else { document.getElementById('ujianForm').reportValidity() }"
            class="inline-flex items-center gap-2 px-6 py-2.5 sm:py-3 text-xs sm:text-sm font-medium text-white transition {{ $isRevisi ?? false ? 'bg-red-600 hover:bg-red-700 shadow-[0_4px_12px_rgba(220,38,38,0.3)]' : 'bg-gradient-to-r from-blue-600 to-blue-700 hover:-translate-y-0.5 shadow-[0_4px_12px_rgba(37,99,235,0.3)] hover:shadow-[0_6px_20px_rgba(37,99,235,0.4)]' }} rounded-xl border-none cursor-pointer">
            <i class="fas fa-paper-plane"></i>
            {{ $isRevisi ?? false ? 'Upload Ulang Dokumen' : 'Ajukan Hasil Ujian' }}
          </button>
        </div>

        {{-- Modal Konfirmasi (Alpine JS) --}}
        <x-modal-confirm :title="$isRevisi ?? false ? 'Konfirmasi Upload Ulang' : 'Konfirmasi Submit Dokumen'" :theme="$isRevisi ?? false ? 'red' : 'blue'" :confirmText="$isRevisi ?? false ? 'Ya, Upload Ulang' : 'Ya, Submit Sekarang'">
          <p class="text-sm text-gray-500">
            @if ($isRevisi ?? false)
              Apakah Anda yakin dokumen hasil ujian yang diupload ulang sudah benar? Dokumen akan dikirim ulang untuk
              diverifikasi.
            @else
              Apakah Anda yakin semua dokumen hasil ujian yang diupload sudah benar dan lengkap? Anda tidak dapat mengubah
              file setelah proses submit berhasil.
            @endif
          </p>
        </x-modal-confirm>

      </form>
    </div>
  @elseif ($ujian->status === 'menunggu_verifikasi_hasil')
    <div class="overflow-hidden bg-white shadow-sm rounded-xl mb-8 border border-gray-200">
      <div class="p-6 sm:p-10 flex flex-col items-center justify-center text-center">
        <div
          class="w-16 h-16 mb-5 text-blue-600 bg-blue-50 rounded-full flex items-center justify-center text-2xl shadow-inner">
          <i class="fas fa-sync-alt fa-spin"></i>
        </div>
        <h3 class="text-lg font-bold text-gray-900 mb-2">Hasil Sedang Diverifikasi</h3>
        <p class="text-sm text-gray-500 max-w-md mx-auto leading-relaxed">
          Berkas hasil ujian <span class="font-medium text-gray-800">{{ ucfirst($jenis) }}</span> Anda telah kami terima
          dan sedang
          dalam proses verifikasi oleh tim koordinator / staf jurusan.
        </p>
        <div class="mt-6 flex flex-wrap gap-3 justify-center">
          <a href="{{ route('mahasiswa.dashboard') }}"
            class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition cursor-pointer no-underline">
            <i class="fas fa-arrow-left mr-1.5 text-gray-400"></i> Kembali
          </a>
          <button onclick="window.location.reload()"
            class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition cursor-pointer border-none flex items-center shadow-sm">
            <i class="fas fa-sync-alt mr-1.5"></i> Refresh Halaman
          </button>
        </div>
      </div>
    </div>
  @endif

@endsection
