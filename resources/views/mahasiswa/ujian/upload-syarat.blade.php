@extends('layouts.app')

@section('title', 'Ujian')

@section('sidebar')
  @include('mahasiswa.sidebar')
@endsection

@section('content')

  {{-- Page Banner --}}
  <div class="relative h-40 rounded-xl overflow-hidden mb-8 bg-gradient-to-br from-blue-600 to-blue-800">
    <div class="absolute inset-0 flex flex-col items-center justify-center text-white text-center px-4">
      <i class="fas fa-graduation-cap text-3xl sm:text-4xl mb-3"></i>
      <h1 class="text-xl sm:text-2xl md:text-[1.75rem] font-bold mb-1">Pengajuan Ujian {{ ucfirst($jenis) }}</h1>
      <p class="text-xs sm:text-sm md:text-base opacity-90">
        @if ($ujian->status === 'draft')
          Lengkapi dokumen persyaratan untuk mengajukan ujian {{ $jenis }}
        @elseif ($ujian->status === 'revisi_syarat')
          Upload ulang dokumen yang ditolak oleh Admin
        @elseif ($ujian->status === 'menunggu_verifikasi_syarat')
          Pengajuan ujian Anda sedang ditinjau oleh Admin/Staf
        @endif
      </p>
    </div>
  </div>

  {{-- Progress Bar --}}
  @include('mahasiswa.ujian.partials.progress-bar', ['activeStep' => 'syarat'])

  @if (in_array($ujian->status, ['draft', 'revisi_syarat']))
    {{-- Alert --}}
    @if ($isRevisi)
      <div class="flex items-start gap-3 p-4 mb-6 border rounded-lg bg-red-50 border-red-300">
        <i class="flex-shrink-0 text-xl mt-0.5 text-red-600 fas fa-exclamation-triangle"></i>
        <div>
          <h4 class="mb-1 text-sm font-semibold text-red-800">Dokumen Ditolak</h4>
          <p class="text-xs text-red-700">
            Beberapa dokumen Anda ditolak oleh Admin. Silakan upload ulang dokumen yang ditolak di bawah ini.
          </p>
        </div>
      </div>
    @else
      <div class="flex items-start gap-3 p-4 mb-6 border rounded-lg bg-amber-50 border-amber-300">
        <i class="flex-shrink-0 text-xl mt-0.5 text-amber-600 fas fa-info-circle"></i>
        <div>
          <h4 class="mb-1 text-sm font-semibold text-amber-800">Informasi Penting</h4>
          <p class="text-xs text-amber-700">
            Pastikan berkas benar dan jadwal telah dikoordinasikan dengan Penguji & Pembimbing.
            Jika terdapat berkas yang salah, ujian akan dikembalikan ke status revisi syarat.
          </p>
        </div>
      </div>
    @endif

    <!-- Flash Messages -->
    <x-alert type="success" />
    <x-alert type="error" />
    <x-alert type="warning" />

    <div x-data="{ showModal: false }">
      <form id="ujianForm" action="{{ route('mahasiswa.ujian.submitPengajuan', ['jenis' => $jenis]) }}" method="POST"
        enctype="multipart/form-data">
        @csrf

        {{-- Form Card: Upload Dokumen --}}
        <div class="overflow-hidden mb-6 bg-white shadow-sm rounded-xl">
          <div class="flex items-center gap-3 px-6 py-5 border-b border-gray-200">
            <i class="text-xl {{ $isRevisi ? 'text-red-600' : 'text-blue-600' }} fas fa-file-upload"></i>
            <h3 class="text-lg font-semibold text-gray-900">
              {{ $isRevisi ? 'Upload Ulang Dokumen' : 'Upload Dokumen Persyaratan' }}
            </h3>
          </div>
          <div class="p-6">
            @foreach ($daftarSyarat as $syarat)
              <div class="mb-6">
                {{-- Catatan Admin --}}
                @if ($isRevisi && $rejectedDokumen->has($syarat['name']) && $rejectedDokumen[$syarat['name']]->catatan)
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
                  class="relative overflow-hidden transition bg-white border-2 {{ $isRevisi ? 'border-red-500 focus-within:ring-red-100' : 'border-blue-600 focus-within:ring-blue-100' }} rounded-xl focus-within:ring-4">
                  <input type="file" name="files[{{ $syarat['name'] }}]" id="{{ $syarat['name'] }}" accept=".pdf"
                    required
                    onchange="document.getElementById('label-{{ $syarat['name'] }}').textContent = this.files[0]?.name ?? '{{ $syarat['placeholder'] ?? 'Pilih file' }}'"
                    class="absolute inset-0 z-10 w-full h-full opacity-0 cursor-pointer" />
                  <div class="flex items-stretch">
                    <div
                      class="flex items-center flex-shrink-0 gap-2 px-5 py-3 text-sm font-medium text-white transition {{ $isRevisi ? 'bg-red-600 hover:bg-red-700' : 'bg-blue-800 hover:bg-blue-900' }}">
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

        {{-- Form Card: Jadwal Ujian --}}
        @if (!$isRevisi)
          <div class="overflow-hidden bg-white shadow-sm rounded-xl">
            <div class="flex items-center gap-3 px-6 py-5 border-b border-gray-200">
              <i class="text-xl text-blue-600 fas fa-calendar-alt"></i>
              <h3 class="text-lg font-semibold text-gray-900">Input Rencana Jadwal Ujian</h3>
            </div>

            <div class="p-6 space-y-6">
              <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                  <label for="tanggal_ujian" class="block mb-1 text-sm font-semibold text-gray-700">Tanggal Ujian <span
                      class="text-red-600">*</span></label>
                  <input type="date" id="tanggal_ujian" name="tanggal_ujian" required
                    value="{{ old('tanggal_ujian') }}" onclick="this.showPicker()"
                    class="w-full px-3 py-3 text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100 {{ $errors->has('tanggal_ujian') ? 'border-red-500 ring-red-100 focus:border-red-500 focus:ring-red-100' : '' }}" />
                  @error('tanggal_ujian')
                    <p class="mt-1.5 text-xs text-red-600"><i class="fas fa-exclamation-circle"></i> {{ $message }}
                    </p>
                  @enderror
                </div>

                <div>
                  <label for="slot_waktu" class="block mb-1 text-sm font-semibold text-gray-700">Slot Waktu <span
                      class="text-red-600">*</span></label>
                  <select id="slot_waktu" name="slot_waktu" required
                    class="w-full px-3 py-3 text-sm text-gray-900 border border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100 {{ $errors->has('slot_waktu') ? 'border-red-500 ring-red-100 focus:border-red-500 focus:ring-red-100' : '' }}">
                    <option value="">Pilih slot waktu</option>
                    <option value="08:00-09:00" @selected(old('slot_waktu') === '08:00-09:00')>08.00 – 09.00</option>
                    <option value="09:30-11:00" @selected(old('slot_waktu') === '09:30-11:00')>09.30 – 11.00</option>
                    <option value="13:30-15:00" @selected(old('slot_waktu') === '13:30-15:00')>13.30 – 15.00</option>
                    <option value="15:00-16:30" @selected(old('slot_waktu') === '15:00-16:30')>15.00 – 16.30</option>
                  </select>
                  @error('slot_waktu')
                    <p class="mt-1.5 text-xs text-red-600"><i class="fas fa-exclamation-circle"></i> {{ $message }}
                    </p>
                  @enderror
                </div>
              </div>

              <div>
                <label for="ruang_ujian" class="block mb-1 text-sm font-semibold text-gray-700">Ruang Ujian <span
                    class="text-red-600">*</span></label>
                <select id="ruang_ujian" name="ruang_ujian" required
                  class="w-full px-3 py-3 text-sm text-gray-900 border border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100 {{ $errors->has('ruang_ujian') ? 'border-red-500 ring-red-100 focus:border-red-500 focus:ring-red-100' : '' }}">
                  <option value="">Pilih ruangan</option>
                  <option value="Ruang Sidang 1" @selected(old('ruang_ujian') === 'Ruang Sidang 1')>Ruang Sidang 1</option>
                  <option value="Ruang Sidang 2" @selected(old('ruang_ujian') === 'Ruang Sidang 2')>Ruang Sidang 2</option>
                  <option value="Ruang Seminar" @selected(old('ruang_ujian') === 'Ruang Seminar')>Ruang Seminar</option>
                  <option value="Lab Multimedia" @selected(old('ruang_ujian') === 'Lab Multimedia')>Lab Multimedia</option>
                  <option value="Ruang Utama" @selected(old('ruang_ujian') === 'Ruang Utama')>Ruang Utama</option>
                </select>
                @error('ruang_ujian')
                  <p class="mt-1.5 text-xs text-red-600"><i class="fas fa-exclamation-circle"></i> {{ $message }}
                  </p>
                @enderror
              </div>
            </div>
          </div>
        @endif

        {{-- Action Buttons --}}
        <div class="flex justify-end gap-3 pt-6 mt-6 border-t border-gray-200">
          <button type="button"
            @click="if(document.getElementById('ujianForm').checkValidity()) { showModal = true } else { document.getElementById('ujianForm').reportValidity() }"
            class="inline-flex items-center gap-2 px-6 py-3 text-sm font-medium text-white transition {{ $isRevisi ? 'bg-red-600 hover:bg-red-700' : 'bg-blue-600 hover:bg-blue-700' }} rounded-lg">
            <i class="fas fa-paper-plane"></i>
            {{ $isRevisi ? 'Upload Ulang Dokumen' : 'Ajukan Ujian' }}
          </button>
        </div>

        {{-- Modal Konfirmasi (Alpine JS) --}}
        <x-modal-confirm :title="$isRevisi ? 'Konfirmasi Upload Ulang' : 'Konfirmasi Submit Dokumen'" :theme="$isRevisi ? 'red' : 'blue'" :confirmText="$isRevisi ? 'Ya, Upload Ulang' : 'Ya, Submit Sekarang'">
          <p class="text-sm text-gray-500">
            @if ($isRevisi)
              Apakah Anda yakin dokumen yang diupload ulang sudah benar? Dokumen akan dikirim ulang untuk
              diverifikasi.
            @else
              Apakah Anda yakin semua dokumen yang diupload sudah benar dan lengkap? Anda tidak dapat mengubah
              file setelah proses submit berhasil.
            @endif
          </p>
        </x-modal-confirm>

      </form>
    </div>
  @elseif ($ujian->status === 'menunggu_verifikasi_syarat')
    <div class="overflow-hidden bg-white shadow-sm rounded-xl mb-8">
      <div class="p-6 flex flex-col items-center justify-center text-center">
        <div class="w-12 h-12 mb-4 text-blue-600 bg-blue-50 rounded-full flex items-center justify-center text-xl">
          <i class="fas fa-sync-alt fa-spin"></i>
        </div>
        <h3 class="text-base font-bold text-gray-900 mb-1">Sedang Diverifikasi</h3>
        <p class="text-xs text-gray-500 max-w-md mx-auto">
          Berkas dan jadwal ujian <span class="font-medium text-gray-700">{{ ucfirst($jenis) }}</span> Anda sedang
          ditinjau oleh Admin/Staf Jurusan.
        </p>
        <p class="text-xs text-gray-500 max-w-md mx-auto mt-1">
          Cek halaman ini secara berkala untuk memantau status atau melihat surat undangan.
        </p>
        <div class="mt-5 flex gap-3 justify-center">
          <a href="{{ route('mahasiswa.dashboard') }}"
            class="px-4 py-2 text-xs font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition">
            <i class="fas fa-arrow-left mr-1"></i> Beranda
          </a>
          <button onclick="window.location.reload()"
            class="px-4 py-2 text-xs font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">
            <i class="fas fa-sync-alt mr-1"></i> Refresh
          </button>
        </div>
      </div>
    </div>
  @endif

@endsection
