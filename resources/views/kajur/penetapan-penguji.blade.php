@extends('layouts.app')

@section('title', 'Penetapan Penguji')

@section('sidebar')
  @include('kajur.sidebar')
@endsection

@section('content')
  <!-- Breadcrumb -->
  <nav class="flex items-center gap-1.5 mb-4 text-xs sm:gap-2 sm:mb-6 sm:text-sm">
    <a href="{{ route('kajur.dashboard') }}" class="text-gray-500 hover:text-blue-600 transition-colors"><i
        class="fas fa-home"></i></a>
    <span class="text-gray-400">/</span>
    <a href="{{ route('kajur.permintaan-penguji.index') }}"
      class="text-gray-500 hover:text-blue-600 transition-colors">Permintaan Penguji</a>
    <span class="text-gray-400">/</span>
    <span class="text-gray-900 font-medium">Penetapan Penguji</span>
  </nav>

  <!-- Page Header -->
  <div class="mb-6 sm:mb-8">
    <h1 class="text-2xl font-bold text-gray-900 mb-1 sm:text-3xl">Penetapan Dosen Penguji</h1>
    <p class="text-sm text-gray-500 sm:text-base">Tetapkan dosen penguji untuk seminar/ujian mahasiswa</p>
  </div>

  <!-- Flash Messages -->
  <x-alert type="error" />
  <x-alert type="warning" />

  <!-- Data Mahasiswa Card -->
  <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
    <div class="px-4 py-3.5 border-b border-gray-200 flex items-center gap-3 sm:px-6 sm:py-5">
      <i class="fas fa-user-graduate text-blue-500 text-xl"></i>
      <h3 class="text-base font-semibold text-gray-900 sm:text-lg">Data Mahasiswa</h3>
    </div>
    <div class="p-4 sm:p-6">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
        @php
          $mahasiswa = $permintaan->tugasAkhir->mahasiswa;
        @endphp
        <!-- Nama -->
        <div class="flex flex-col gap-1">
          <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Nama Mahasiswa</span>
          <span class="text-sm font-semibold text-blue-600 sm:text-base">{{ $mahasiswa->nama_lengkap }}</span>
        </div>
        <!-- NIM -->
        <div class="flex flex-col gap-1">
          <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">NIM</span>
          <span class="text-sm text-gray-900 sm:text-base">{{ $mahasiswa->nim }}</span>
        </div>
        <!-- Jurusan -->
        <div class="flex flex-col gap-1">
          <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Jurusan</span>
          <span class="text-sm text-gray-900 sm:text-base">{{ $mahasiswa->jurusan ?? '-' }}</span>
        </div>
        <!-- Tahapan -->
        <div class="flex flex-col gap-1">
          <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Tahapan</span>
          <span
            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 w-fit">{{ $permintaan->tugasAkhir->tahapan }}</span>
        </div>
        <!-- Judul -->
        <div class="flex flex-col gap-1 md:col-span-2">
          <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Judul Tugas Akhir</span>
          <span class="text-sm text-gray-900 sm:text-base">{{ $permintaan->tugasAkhir->judul }}</span>
        </div>
        <!-- Abstrak -->
        <div class="flex flex-col gap-1 md:col-span-2">
          <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Abstrak</span>
          <span
            class="text-sm text-gray-900 text-justify leading-relaxed line-clamp-5 sm:text-base">{{ $permintaan->tugasAkhir->abstrak }}</span>
        </div>
        <!-- Kata Kunci -->
        <div class="flex flex-col gap-1 md:col-span-2">
          <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Kata Kunci</span>
          <div class="flex flex-wrap gap-2 mt-1">
            @foreach (explode(',', $permintaan->tugasAkhir->kata_kunci) as $kata_kunci)
              <span
                class="px-3 py-1 bg-indigo-100 text-indigo-800 rounded-md text-sm font-medium">{{ $kata_kunci }}</span>
            @endforeach
          </div>
        </div>
        <!-- Dosen Pembimbing -->
        <div class="flex flex-col gap-1 md:col-span-2">
          <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Dosen Pembimbing</span>
          <div class="flex flex-col gap-2 mt-1">
            @foreach ($mahasiswa->dosenPembimbing as $pembimbing)
              <div class="flex items-center gap-3 px-4 py-3 bg-gray-50 rounded-lg">
                <span
                  class="w-6 h-6 bg-blue-500 text-white rounded-full flex items-center justify-center text-xs font-semibold">{{ $loop->iteration }}</span>
                <span class="text-sm text-gray-700">{{ $pembimbing->dosen->nama_lengkap }}</span>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>

  <x-alert type="success" />


  <!-- Verifikasi Persyaratan Card -->
  <div x-data="{ status: @js($permintaan->status), catatan: '', files: [], showAccModal: false }" class="bg-white rounded-xl shadow-sm overflow-hidden mb-6 border-2"
    :class="status === 'acc' ? 'border-emerald-400' : status === 'reject' ? 'border-red-400' : 'border-amber-400'">
    <div class="px-4 py-3.5 border-b border-gray-200 flex items-center justify-between flex-wrap gap-3 sm:px-6 sm:py-5">
      <div class="flex items-center gap-3">
        <i class="fas fa-clipboard-check text-emerald-500 text-xl"></i>
        <h3 class="text-base font-semibold text-gray-900 sm:text-lg">Verifikasi Persyaratan</h3>
      </div>
      <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold"
        :class="{
            'bg-emerald-100 text-emerald-800': status === 'acc',
            'bg-amber-100 text-amber-800': status === 'revisi' || status === 'pending',
            'bg-red-100 text-red-800': status === 'reject'
        }">
        <template x-if="status === 'acc'"><span><i class="fas fa-check-circle"></i> Disetujui</span></template>
        <template x-if="status === 'revisi'"><span><i class="fas fa-pen"></i> Revisi</span></template>
        <template x-if="status === 'reject'"><span><i class="fas fa-times-circle"></i> Ditolak</span></template>
        <template x-if="status === 'pending'"><span><i class="fas fa-exclamation-circle"></i> Perlu
            Verifikasi</span></template>
      </span>
    </div>
    <div class="p-3 sm:p-6">
      <div class="flex flex-col gap-4">
        <div class="flex items-start gap-3 p-3 rounded-lg border sm:gap-4 sm:p-4"
          :class="status === 'acc' ? 'bg-emerald-50 border-emerald-300' : status === 'reject' ?
              'bg-red-50 border-red-300' : 'bg-amber-50 border-amber-300'">
          <div class="hidden w-8 h-8 rounded-full text-white items-center justify-center flex-shrink-0 sm:flex"
            :class="status === 'acc' ? 'bg-emerald-500' : status === 'reject' ? 'bg-red-500' : 'bg-amber-500'">
            <i class="text-sm"
              :class="status === 'acc' ? 'fas fa-check' : status === 'reject' ? 'fas fa-times' : 'fas fa-exclamation'"></i>
          </div>
          <div class="flex-1 min-w-0">
            <div class="text-[13px] font-semibold text-gray-700 mb-1 sm:text-sm">File Laporan Tugas Akhir</div>
            <div class="text-xs text-gray-500 leading-relaxed mb-3">
              Dokumen laporan tugas akhir lengkap telah diupload mahasiswa dan perlu diverifikasi.
            </div>

            @if ($permintaan->catatan)
              <div class="mb-3 rounded-lg border bg-white px-3 py-2">
                <div class="mb-1 text-xs font-semibold ">
                  <i class="fas fa-comment-dots mr-1"></i> Catatan Mahasiswa
                </div>
                <p class="text-sm leading-relaxed">{{ $permintaan->catatan }}</p>
              </div>
            @endif

            @foreach ($permintaan->kajurSubmissionFiles as $file)
              <x-file-preview-item :path="$file->file_path" type="kajur-submission-file" :file-id="$file->id" :uploaded-at="$file->created_at"
                class="rounded-lg mb-3" />
            @endforeach

            {{-- Satu form untuk semua aksi (acc / revisi / reject) --}}
            @if ($permintaan->status === 'pending')
              <form action="{{ route('kajur.verify-laporan', ['permintaan' => $permintaan->id]) }}" method="POST"
                enctype="multipart/form-data" class="mt-4">
                @csrf
                @method('put')

                <input type="hidden" name="status" :value="status">

                {{-- Tombol Aksi --}}
                <div x-show="status === 'pending'" class="flex flex-wrap gap-2">
                  <x-action-button @click="showAccModal = true" class="bg-emerald-500 hover:bg-emerald-600">
                    <i class="fas fa-check-circle mr-1"></i> Acc
                  </x-action-button>
                  <x-action-button @click="status = 'revisi'" class="bg-amber-500 hover:bg-amber-600">
                    <i class="fas fa-pen mr-1"></i> Revisi
                  </x-action-button>
                  <x-action-button @click="status = 'reject'" class="bg-red-500 hover:bg-red-600">
                    <i class="fas fa-times-circle"></i> Tolak
                  </x-action-button>
                </div>

                {{-- Modal Konfirmasi Acc --}}
                <div x-show="showAccModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center">
                  <div class="fixed inset-0 bg-black/50" @click="showAccModal = false"></div>
                  <div class="relative bg-white rounded-xl shadow-xl w-full max-w-md mx-4 p-6" x-transition>
                    <div class="flex flex-col items-center text-center">
                      <div class="w-14 h-14 rounded-full bg-emerald-100 flex items-center justify-center mb-4">
                        <i class="fas fa-check-circle text-emerald-500 text-2xl"></i>
                      </div>
                      <h3 class="text-lg font-semibold text-gray-900 mb-2">Setujui Persyaratan?</h3>
                      <p class="text-sm text-gray-500 mb-6">Dokumen laporan tugas akhir akan ditandai sebagai disetujui.
                        Tindakan ini bisa dibatalkan.</p>
                      <div class="flex gap-3 w-full">
                        <button type="button" @click="showAccModal = false"
                          class="flex-1 px-4 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition-all cursor-pointer">
                          Batal
                        </button>
                        <button type="submit" @click="status = 'acc'; showAccModal = false"
                          class="flex-1 px-4 py-2.5 bg-emerald-500 text-white rounded-lg text-sm font-semibold hover:bg-emerald-600 transition-all flex items-center justify-center gap-1.5 cursor-pointer">
                          <i class="fas fa-check"></i> Ya, Setujui
                        </button>
                      </div>
                    </div>
                  </div>
                </div>

                {{-- Form Review (Revisi / Tolak) --}}
                <div x-show="status === 'revisi' || status === 'reject'" x-cloak class="p-4 rounded-lg border"
                  :class="status === 'revisi' ? 'bg-amber-50 border-amber-300' : 'bg-red-50 border-red-300'">
                  <div class="flex items-center gap-2 text-sm font-semibold mb-3"
                    :class="status === 'revisi' ? 'text-amber-700' : 'text-red-700'">
                    <i :class="status === 'revisi' ? 'fas fa-pen' : 'fas fa-times-circle'"></i>
                    <span x-text="status === 'revisi' ? 'Catatan Revisi' : 'Alasan Penolakan'"></span>
                  </div>

                  <div class="mb-5">
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Catatan Review <span
                        class="text-red-500">*</span></label>
                    <textarea name="review"
                      class="w-full px-3 py-3 border border-gray-300 rounded-lg text-[0.95rem] resize-y min-h-[80px] transition-colors focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-100"
                      placeholder="Tambahkan catatan review..." :required="status === 'revisi' || status === 'reject'"
                      :disabled="status === 'pending' || status === 'acc'"></textarea>
                  </div>

                  <x-file-upload name="files[]" accept=".pdf,.doc,.docx" :multiple="true"
                    label="Upload File Pendukung (Opsional)" :max-mb="10" class="mb-6" />

                  <div class="flex gap-2 justify-end">
                    <button type="button" @click="status = 'pending'"
                      class="px-3 py-1.5 bg-white border border-gray-300 text-gray-600 rounded-lg text-xs font-medium hover:bg-gray-50 transition-all cursor-pointer">
                      Batal
                    </button>
                    <button type="submit"
                      class="px-4 py-1.5 text-white rounded-lg text-xs font-semibold transition-all flex items-center gap-1.5 cursor-pointer"
                      :class="status === 'revisi' ? 'bg-amber-500 hover:bg-amber-600' : 'bg-red-500 hover:bg-red-600'">
                      <i class="fas fa-paper-plane"></i>
                      <span x-text="status === 'revisi' ? 'Kirim Revisi' : 'Tolak Persyaratan'"></span>
                    </button>
                  </div>
                </div>
              </form>
            @endif

            {{-- Read-only: Status sudah diverifikasi sebelumnya --}}
            @if ($permintaan->status === 'acc')
              <div class="mt-4 p-4 bg-emerald-50 border border-emerald-300 rounded-lg">
                <div class="flex items-center gap-2 text-emerald-700 text-sm font-semibold mb-1">
                  <i class="fas fa-check-circle"></i> Persyaratan Disetujui
                </div>
                <p class="text-xs text-emerald-600">Dokumen laporan tugas akhir telah diverifikasi dan disetujui.</p>
              </div>
            @elseif ($permintaan->status === 'revisi')
              <div class="mt-4 p-4 bg-amber-50 border border-amber-300 rounded-lg">
                <div class="flex items-center gap-2 text-amber-700 text-sm font-semibold mb-2">
                  <i class="fas fa-pen"></i> Catatan Revisi
                </div>
                @if ($permintaan->review)
                  <p class="text-sm text-gray-700 leading-relaxed">{{ $permintaan->review }}</p>
                @endif
                @foreach ($permintaan->kajurSubmissionFiles->where('uploaded_by', 'kajur') as $reviewFile)
                  <x-file-preview-item :path="$reviewFile->file_path" type="kajur-submission-file" :file-id="$reviewFile->id" :uploaded-at="$reviewFile->created_at"
                    class="rounded-lg mt-3" />
                @endforeach
              </div>
            @elseif ($permintaan->status === 'reject')
              <div class="mt-4 p-4 bg-red-50 border border-red-300 rounded-lg">
                <div class="flex items-center gap-2 text-red-700 text-sm font-semibold mb-2">
                  <i class="fas fa-times-circle"></i> Alasan Penolakan
                </div>
                @if ($permintaan->review)
                  <p class="text-sm text-gray-700 leading-relaxed">{{ $permintaan->review }}</p>
                @endif
                @foreach ($permintaan->kajurSubmissionFiles->where('uploaded_by', 'kajur') as $reviewFile)
                  <x-file-preview-item :path="$reviewFile->file_path" type="kajur-submission-file" :file-id="$reviewFile->id"
                    :uploaded-at="$reviewFile->created_at" class="rounded-lg mt-3" />
                @endforeach
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>

  @if ($permintaan->status === 'pending')
    {{-- Skeleton: rekomendasi muncul setelah verifikasi ACC --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6 border border-gray-200">
      <div class="px-4 py-3.5 border-b border-gray-200 flex items-center justify-between sm:px-6 sm:py-5">
        <div class="flex items-center gap-3">
          <i class="fas fa-users text-gray-300 text-xl"></i>
          <h3 class="text-base font-semibold text-gray-400 sm:text-lg">Rekomendasi Dosen Penguji</h3>
        </div>
        <span
          class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-400">
          <i class="fas fa-lock text-[10px]"></i> Menunggu Verifikasi
        </span>
      </div>
      <div class="p-4 sm:p-6">
        <div class="space-y-4 animate-pulse">
          @for ($i = 0; $i < 3; $i++)
            <div class="border border-gray-200 rounded-xl p-4">
              <div class="flex items-center gap-3 mb-3">
                <div class="w-7 h-7 bg-gray-200 rounded-full sm:w-9 sm:h-9"></div>
                <div class="h-3 bg-gray-200 rounded w-40"></div>
              </div>
              <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                <div class="w-9 h-9 bg-gray-200 rounded-full sm:w-11 sm:h-11"></div>
                <div class="flex-1 space-y-2">
                  <div class="h-3.5 bg-gray-200 rounded w-48"></div>
                  <div class="h-2.5 bg-gray-200 rounded w-64"></div>
                </div>
              </div>
            </div>
          @endfor
        </div>
        <p class="text-center text-xs text-gray-400 mt-4">
          <i class="fas fa-info-circle mr-1"></i>
          Hasil rekomendasi akan muncul setelah verifikasi persyaratan disetujui.
        </p>
      </div>
    </div>
  @endif

  @if (!$hasPenguji && $permintaan->status == 'acc')
    <!-- Form Penetapan Penguji -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
      <div
        class="px-4 py-3.5 border-b border-gray-200 flex items-center justify-between flex-wrap gap-3 sm:px-6 sm:py-5">
        <div class="flex items-center gap-3">
          <i class="fas fa-users text-blue-500 text-xl"></i>
          <h3 class="text-base font-semibold text-gray-900 sm:text-lg">Rekomendasi Dosen Penguji</h3>
        </div>
        <span
          class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-br from-violet-500 to-indigo-500 text-white">
          <i class="fas fa-magic"></i> Auto-Recommended
        </span>
      </div>
      <div class="p-3 sm:p-6">
        <form x-data="pengujiHandler(
            @js($rankedDosens->values()),
            @js($unrankedDosens->values()),
            @js($rankedDosens->take(3)->values())
        )" class="mt-2"
          action="{{ route('kajur.tetapkanPenguji', ['permintaan' => $permintaan->id]) }}" method="POST">
          @csrf

          <!-- Dosen Penguji Label -->
          <div class="mb-5">
            <label class="block text-sm font-semibold text-gray-700 mb-4">
              Dosen Penguji <span class="text-red-600">*</span>
              <span class="hidden text-xs text-gray-500 font-normal ml-1 sm:inline">(Direkomendasikan berdasarkan
                analisis sistem)</span>
            </label>

            <div class="flex flex-col gap-4 sm:gap-6" id="pengujiContainer">
              <template x-for="dosen in selected" :key="dosen.id">
                <input type="hidden" name="penguji_ids[]" :value="dosen.id">
              </template>

              <template x-for="(dosen, index) in selected" :key="dosen.id">
                <div
                  class="bg-gradient-to-br from-green-50 to-emerald-50 border border-green-300 rounded-xl p-3 transition-all sm:p-5">
                  <div class="flex items-center justify-between mb-3 gap-2 sm:mb-4 sm:gap-3">
                    <div class="flex min-w-0 items-center gap-2 sm:gap-3">
                      <span
                        class="w-7 h-7 bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-full flex items-center justify-center text-sm font-bold sm:w-9 sm:h-9 sm:text-base"
                        x-text="index + 1"></span>
                      <div class="min-w-0 truncate text-xs font-semibold text-gray-700 sm:text-sm">
                        <template x-if="dosen.has_detail">
                          <span>Penguji <span x-text="index + 1"></span> (Ranking #<span
                              x-text="dosen.rank"></span>)</span>
                        </template>
                        <template x-if="!dosen.has_detail">
                          <span>Penguji <span x-text="index + 1"></span></span>
                        </template>
                      </div>
                    </div>
                    <template x-if="dosen.has_detail">
                      <div class="flex gap-2">
                        <div
                          class="flex flex-col items-center px-2 py-1.5 rounded-lg bg-gradient-to-br from-green-200 to-green-300 min-w-[70px] sm:px-3 sm:py-2 sm:min-w-[90px]">
                          <span
                            class="text-[9px] font-semibold uppercase tracking-wide text-green-900 sm:text-[10px]">Skor</span>
                          <span class="text-base font-bold text-green-900 sm:text-lg"
                            x-text="formatScore(dosen.total_score)"></span>
                        </div>
                      </div>
                    </template>
                  </div>

                  <div
                    class="flex flex-col gap-3 p-3 bg-gradient-to-r from-white to-green-50 border border-green-300 rounded-lg mb-3 sm:flex-row sm:items-center sm:justify-between sm:gap-0 sm:p-4">
                    <div class="flex items-center gap-3">
                      <div
                        class="w-9 h-9 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-sm sm:w-11 sm:h-11 sm:text-base">
                        <span x-text="dosen.initials"></span>
                      </div>
                      <div>
                        <h4 class="text-sm font-semibold text-gray-900 mb-0.5 sm:text-[15px]"
                          x-text="dosen.nama_lengkap"></h4>
                        <div class="flex flex-wrap gap-x-3 gap-y-0.5 text-[11px] text-gray-600 sm:text-xs">
                          <span><i class="fas fa-id-badge"></i> <span x-text="dosen.nidn"></span></span>
                          <span><i class="fas fa-award"></i> <span x-text="dosen.jabatan_fungsional"></span></span>
                          <span><i class="fas fa-calendar-alt"></i> <span
                              x-text="dosen.total_pengujian_periode ?? 0"></span> Pengujian (Periode Aktif)</span>
                          <span><i class="fas fa-tasks"></i> <span x-text="dosen.total_pengujian_aktif ?? 0"></span>
                            Pengujian (Belum Lulus)</span>
                        </div>
                      </div>
                    </div>
                    <button type="button"
                      class="flex w-full items-center justify-center gap-1.5 px-4 py-2 bg-yellow-100 text-yellow-900 border border-yellow-300 rounded-lg text-xs font-semibold hover:bg-yellow-200 hover:border-yellow-400 transition-all sm:w-auto"
                      @click="openModal(index)">
                      <i class="fas fa-exchange-alt text-xs"></i> Ganti
                    </button>
                  </div>

                  <template x-if="dosen.has_detail">
                    <div x-cloak class="bg-white border border-gray-200 rounded-lg overflow-hidden"
                      x-data="{ open: false }">
                      <div
                        class="flex items-center gap-2 px-3 py-2.5 cursor-pointer hover:bg-gray-50 transition-colors sm:px-4 sm:py-3"
                        @click="open = !open">
                        <i class="fas fa-calculator text-purple-600"></i>
                        <span class="text-xs font-semibold text-gray-700">Lihat Detail Perhitungan</span>
                        <i class="fas fa-chevron-down text-xs text-gray-500 ml-auto transition-transform duration-200"
                          :class="{ 'rotate-180': open }"></i>
                      </div>
                      <div x-show="open" x-transition class="border-t border-gray-200 p-3 bg-gray-50 sm:p-4">
                        <div class="mb-4 pb-4 border-b border-dashed border-gray-300">
                          <div class="text-xs font-semibold text-indigo-600 mb-2 flex items-center gap-2">
                            <i class="fas fa-project-diagram text-[11px]"></i> Content-Based Filtering (Cosine
                            Similarity)
                          </div>
                          <div
                            class="flex justify-between items-center px-3 py-2 bg-blue-100 rounded-md text-xs font-semibold">
                            <span class="text-blue-900">Nilai Similarity (CBF)</span>
                            <span class="text-blue-900 text-sm" x-text="formatScore(dosen.similarity_score)"></span>
                          </div>
                        </div>

                        <div>
                          <div class="text-xs font-semibold text-indigo-600 mb-2 flex items-center gap-2">
                            <i class="fas fa-balance-scale text-[11px]"></i> Multi-Attribute Utility Theory (MAUT)
                          </div>
                          <div class="overflow-x-auto">
                            <table class="w-full text-[11px] mb-2 bg-white border-collapse sm:text-xs">
                              <thead>
                                <tr>
                                  <th
                                    class="bg-gray-100 px-2 py-2 text-left font-semibold text-gray-700 border border-gray-200">
                                    Atribut</th>
                                  <th
                                    class="bg-gray-100 px-2 py-2 text-center font-semibold text-gray-700 border border-gray-200">
                                    Nilai Ternormalisasi</th>
                                  <th
                                    class="bg-gray-100 px-2 py-2 text-center font-semibold text-gray-700 border border-gray-200">
                                    Bobot</th>
                                  <th
                                    class="bg-gray-100 px-2 py-2 text-right font-semibold text-gray-700 border border-gray-200">
                                    Utility</th>
                                </tr>
                              </thead>
                              <tbody>
                                <template x-for="criterion in dosen.criteria_details"
                                  :key="criterion.key + '-' + criterion.label">
                                  <tr>
                                    <td class="px-2 py-2 font-medium border border-gray-200" x-text="criterion.label">
                                    </td>
                                    <td class="px-2 py-2 text-center border border-gray-200"
                                      x-text="formatScore(criterion.normalized_value)"></td>
                                    <td class="px-2 py-2 text-center border border-gray-200" x-text="criterion.weight">
                                    </td>
                                    <td class="px-2 py-2 text-right border border-gray-200"
                                      x-text="formatScore(criterion.utility_score)"></td>
                                  </tr>
                                </template>
                              </tbody>
                            </table>
                          </div>
                          <div
                            class="flex justify-between items-center px-3 py-2 bg-gradient-to-br from-green-200 to-green-300 rounded-md text-xs font-semibold">
                            <span class="text-green-900">Total Skor MAUT</span>
                            <span class="text-green-900 text-base" x-text="formatScore(dosen.total_score)"></span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </template>
                </div>
              </template>
            </div>
          </div>

          <!-- Button Group -->
          <div
            class="flex flex-col-reverse gap-3 mt-6 pt-4 border-t border-gray-200 sm:flex-row sm:gap-4 sm:justify-end sm:pt-6">
            <a href="{{ route('kajur.permintaan-penguji.index') }}"
              class="flex items-center justify-center gap-2 px-4 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 hover:border-gray-400 transition-all no-underline sm:px-6 sm:py-3">
              <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <button type="button" @click="showModal = true"
              class="flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-br from-emerald-500 to-emerald-600 border-none text-white rounded-lg text-sm font-medium hover:-translate-y-0.5 hover:shadow-lg hover:shadow-emerald-500/30 transition-all cursor-pointer sm:px-6 sm:py-3">
              <i class="fas fa-check"></i> Tetapkan Penguji
            </button>
          </div>

          <x-modal-confirm model="showModal" title="Konfirmasi Penetapan Penguji" confirmText="Ya, Tetapkan Penguji"
            theme="blue">
            <p class="text-sm text-gray-500">
              Apakah Anda yakin dosen penguji yang dipilih sudah sesuai? Penetapan ini akan menyimpan penguji 1, penguji
              2, dan penguji 3 untuk mahasiswa terkait.
            </p>
          </x-modal-confirm>

          <div x-show="activeIndex !== null" x-cloak
            class="flex fixed inset-0 bg-black/50 items-center justify-center z-[1000] p-4">
            <div @click.outside="closeModal()"
              class="bg-white rounded-2xl w-full max-w-xl max-h-[90vh] overflow-hidden shadow-2xl">
              <div class="px-4 py-3.5 border-b border-gray-200 flex items-center justify-between sm:px-6 sm:py-5">
                <h3 class="text-base font-bold text-gray-900 flex items-center gap-2 sm:text-lg">
                  <i class="fas fa-exchange-alt"></i> Ganti Dosen Penguji
                  <span class="text-sm font-semibold text-blue-700"
                    x-text="activeIndex !== null ? '(Penguji ' + (activeIndex + 1) + ')' : ''"></span>
                </h3>
                <button type="button"
                  class="bg-transparent border-none text-xl text-gray-500 cursor-pointer p-1 hover:text-gray-900 transition-colors"
                  @click="closeModal()">
                  <i class="fas fa-times"></i>
                </button>
              </div>

              <div class="px-4 py-4 max-h-[60vh] overflow-y-auto sm:px-6 sm:py-6">
                <div class="mb-4">
                  <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="text" name="dosenSearch" x-model.trim="searchQuery"
                      placeholder="Cari nama, NIDN, atau bidang..."
                      class="w-full rounded-lg border border-gray-300 pl-10 pr-4 py-2.5 text-sm focus:border-blue-500 focus:ring-0">
                  </div>
                </div>

                <div class="flex flex-col gap-2" id="dosenList">
                  <div class="px-1 pb-1 text-[11px] font-semibold uppercase tracking-wide text-gray-500">Rekomendasi
                  </div>
                  <template x-for="dosen in filteredAvailableRanked" :key="'ranked-' + dosen.id">
                    <button type="button" @click="selectCandidate(dosen)"
                      class="w-full text-left relative flex items-center gap-3 p-3 bg-white border rounded-xl cursor-pointer transition-all hover:shadow-md hover:border-blue-300 hover:-translate-y-0.5 sm:gap-4 sm:p-4"
                      :class="isCandidateSelected(dosen.id) ? 'border-blue-400 ring-2 ring-blue-100' : 'border-gray-200'">
                      <div
                        class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center text-white font-semibold text-xs flex-shrink-0 sm:w-10 sm:h-10 sm:text-sm"
                        x-text="dosen.initials"></div>
                      <div class="flex-1 min-w-0">
                        <h5 class="text-sm font-semibold text-gray-900 truncate" x-text="dosen.nama_lengkap"></h5>
                        <p class="text-[0.7rem] text-gray-500 truncate">NIDN: <span x-text="dosen.nidn || '-' "></span>
                        </p>
                        <p class="mt-0.5 flex flex-wrap gap-x-1.5 gap-y-1 text-[0.7rem] text-gray-500">
                          <span class="truncate max-w-full" x-text="dosen.keahlian || 'Umum'"></span>
                          <span class="hidden sm:inline">·</span>
                          <span><span x-text="dosen.total_pengujian_periode ?? 0"></span> <span>pengujian (periode
                              aktif)</span></span>
                          <span class="hidden sm:inline">·</span>
                          <span><span x-text="dosen.total_pengujian_aktif ?? 0"></span> <span>pengujian (belum
                              lulus)</span></span>
                        </p>
                      </div>
                      <div
                        class="flex-shrink-0 w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center sm:w-7 sm:h-7">
                        <span class="text-[0.65rem] font-bold text-blue-600" x-text="'#' + dosen.rank"></span>
                      </div>
                      <div class="flex-shrink-0 text-right">
                        <p class="text-[0.55rem] text-gray-400 uppercase tracking-wide sm:text-[0.6rem]">Skor</p>
                        <p class="text-xs font-bold text-emerald-500 sm:text-sm"
                          x-text="formatScore(dosen.total_score)"></p>
                      </div>
                    </button>
                  </template>

                  <div class="mt-3 px-1 pb-1 text-[11px] font-semibold uppercase tracking-wide text-gray-500">Lainnya
                  </div>
                  <template x-for="dosen in filteredAvailableUnranked" :key="'unranked-' + dosen.id">
                    <button type="button" @click="selectCandidate(dosen)"
                      class="w-full text-left flex items-center p-3 border rounded-lg cursor-pointer transition-all hover:bg-blue-50 hover:border-blue-300 sm:p-4"
                      :class="isCandidateSelected(dosen.id) ? 'bg-blue-50 border-blue-300 ring-2 ring-blue-100' :
                          'bg-gray-50 border-gray-200'">
                      <div class="flex min-w-0 items-center gap-3">
                        <div
                          class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center text-white font-semibold text-xs flex-shrink-0 sm:w-10 sm:h-10 sm:text-sm"
                          x-text="dosen.initials"></div>
                        <div class="min-w-0 flex-1">
                          <h5 class="truncate text-[0.9rem] font-semibold text-gray-900 mb-0.5"
                            x-text="dosen.nama_lengkap"></h5>
                          <p class="truncate text-[0.7rem] text-gray-500">NIDN: <span
                              x-text="dosen.nidn || '-' "></span></p>
                          <p class="mt-0.5 flex flex-wrap gap-x-1.5 gap-y-1 text-[0.7rem] text-gray-500">
                            <span class="truncate max-w-full" x-text="dosen.keahlian || 'Umum'"></span>
                            <span class="hidden sm:inline">·</span>
                            <span><span x-text="dosen.total_pengujian_periode ?? 0"></span> <span>pengujian (periode
                                aktif)</span></span>
                            <span class="hidden sm:inline">·</span>
                            <span><span x-text="dosen.total_pengujian_aktif ?? 0"></span> <span>pengujian (belum
                                lulus)</span></span>
                          </p>
                        </div>
                      </div>
                    </button>
                  </template>

                  <template x-if="filteredAvailableRanked.length === 0 && filteredAvailableUnranked.length === 0">
                    <div class="rounded-lg border border-gray-200 bg-gray-50 px-4 py-3 text-xs text-gray-500">
                      Dosen tidak ditemukan. Coba kata kunci lain.
                    </div>
                  </template>
                </div>
              </div>

              <div class="px-4 py-3 border-t border-gray-200 flex justify-end gap-3 sm:px-6 sm:py-4">
                <button type="button"
                  class="px-5 py-2.5 bg-white border border-gray-300 rounded-lg text-gray-700 text-sm font-medium cursor-pointer hover:bg-gray-50 hover:border-gray-400 transition-all"
                  @click="closeModal()">
                  Batal
                </button>
                <button type="button"
                  class="px-5 py-2.5 rounded-lg text-sm font-medium flex items-center gap-2 transition-all"
                  :class="candidateDosen ?
                      'bg-gradient-to-br from-blue-500 to-blue-700 text-white hover:-translate-y-px hover:shadow-lg hover:shadow-blue-300/50' :
                      'bg-gray-200 text-gray-500 cursor-not-allowed'"
                  :disabled="!candidateDosen" @click="confirmSelectedDosen()">
                  <i class="fas fa-check"></i> Pilih Dosen
                </button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  @endif

  {{-- Success Modal --}}
  @if (session('show_success_modal'))
    <x-result-modal status="success" title="Penguji Berhasil Ditetapkan!"
      desc="Dosen penguji telah ditetapkan dan mahasiswa akan mendapatkan notifikasi." :href="route('kajur.permintaan-penguji.index')" />
  @endif

  <script>
    function pengujiHandler(rankedDosens, unrankedDosens, initialSelected) {
      return {
        rankedDosens,
        unrankedDosens,

        selected: [...initialSelected],
        showModal: false,
        activeIndex: null,
        candidateDosen: null,
        searchQuery: '',

        openModal(index) {
          this.activeIndex = index;
          this.candidateDosen = null;
          this.searchQuery = '';
        },

        closeModal() {
          this.activeIndex = null;
          this.candidateDosen = null;
          this.searchQuery = '';
        },

        selectCandidate(dosen) {
          this.candidateDosen = dosen;
        },

        isCandidateSelected(dosenId) {
          return this.candidateDosen?.id === dosenId;
        },

        pilihDosen(dosen) {
          this.selected[this.activeIndex] = dosen;
          this.closeModal();
        },

        confirmSelectedDosen() {
          if (!this.candidateDosen || this.activeIndex === null) {
            return;
          }

          this.pilihDosen(this.candidateDosen);
        },

        formatScore(value) {
          return Number(value ?? 0).toFixed(2);
        },

        matchesSearch(dosen) {
          const keyword = this.searchQuery.toLowerCase();
          if (!keyword) {
            return true;
          }

          const haystack = [
              dosen.nama_lengkap,
              dosen.nidn,
              dosen.keahlian,
            ]
            .filter(Boolean)
            .join(' ')
            .toLowerCase();

          return haystack.includes(keyword);
        },

        get availableRanked() {
          return this.rankedDosens.filter(d =>
            !this.selected.some(s => s.id === d.id)
          );
        },

        get filteredAvailableRanked() {
          return this.availableRanked.filter(d => this.matchesSearch(d));
        },

        get availableUnranked() {
          return this.unrankedDosens.filter(d =>
            !this.selected.some(s => s.id === d.id)
          );
        },

        get filteredAvailableUnranked() {
          return this.availableUnranked.filter(d => this.matchesSearch(d));
        }
      }
    }
  </script>

  <script>
    // Simpan posisi scroll saat form disubmit, restore setelah reload
    document.querySelectorAll('form').forEach(form => {
      form.addEventListener('submit', () => {
        sessionStorage.setItem('scrollY', window.scrollY);
      });
    });

    const savedScroll = sessionStorage.getItem('scrollY');
    if (savedScroll) {
      window.scrollTo(0, parseInt(savedScroll));
      sessionStorage.removeItem('scrollY');
    }
  </script>
@endsection
