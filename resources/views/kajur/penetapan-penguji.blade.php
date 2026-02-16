@extends('layouts.app')

@section('title', 'Penetapan Penguji')

@section('sidebar')
  @include('kajur.sidebar')
@endsection

@section('content')
  <!-- Breadcrumb -->
  <nav class="flex items-center gap-2 mb-6 text-sm">
    <a href="{{ route('kajur.dashboard') }}" class="text-gray-500 hover:text-blue-600 transition-colors"><i
        class="fas fa-home"></i></a>
    <span class="text-gray-400">/</span>
    <a href="{{ route('kajur.permintaan-penguji.index') }}"
      class="text-gray-500 hover:text-blue-600 transition-colors">Permintaan Penguji</a>
    <span class="text-gray-400">/</span>
    <span class="text-gray-900 font-medium">Penetapan Penguji</span>
  </nav>

  <!-- Page Header -->
  <div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-1">Penetapan Dosen Penguji</h1>
    <p class="text-gray-500">Tetapkan dosen penguji untuk seminar/ujian mahasiswa</p>
  </div>

  <!-- Flash Messages -->
  <x-alert type="success" />
  <x-alert type="error" />
  <x-alert type="warning" />

  <!-- Data Mahasiswa Card -->
  <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
    <div class="px-6 py-5 border-b border-gray-200 flex items-center gap-3">
      <i class="fas fa-user-graduate text-blue-500 text-xl"></i>
      <h3 class="text-lg font-semibold text-gray-900">Data Mahasiswa</h3>
    </div>
    <div class="p-6">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @php
          $mahasiswa = $permintaan->tugasAkhir->mahasiswa;
        @endphp
        <!-- Nama -->
        <div class="flex flex-col gap-1">
          <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Nama Mahasiswa</span>
          <span class="text-base font-semibold text-blue-600">{{ $mahasiswa->nama_lengkap }}</span>
        </div>
        <!-- NIM -->
        <div class="flex flex-col gap-1">
          <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">NIM</span>
          <span class="text-base text-gray-900">{{ $mahasiswa->nim }}</span>
        </div>
        <!-- Prodi -->
        <div class="flex flex-col gap-1">
          <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Program Studi</span>
          <span class="text-base text-gray-900">{{ $mahasiswa->program_studi }}</span>
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
          <span class="text-base text-gray-900">{{ $permintaan->tugasAkhir->judul }}</span>
        </div>
        <!-- Abstrak -->
        <div class="flex flex-col gap-1 md:col-span-2">
          <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Abstrak</span>
          <span class="text-base text-gray-900 text-justify leading-relaxed">{{ $permintaan->tugasAkhir->abstrak }}</span>
        </div>
        <!-- Kata Kunci -->
        <div class="flex flex-col gap-1 md:col-span-2">
          <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Kata Kunci</span>
          <div class="flex flex-wrap gap-2 mt-1">
            <span class="px-3 py-1 bg-indigo-100 text-indigo-800 rounded-md text-sm font-medium">K-Means</span>
            <span class="px-3 py-1 bg-indigo-100 text-indigo-800 rounded-md text-sm font-medium">DBSCAN</span>
            <span class="px-3 py-1 bg-indigo-100 text-indigo-800 rounded-md text-sm font-medium">Clustering</span>
            <span class="px-3 py-1 bg-indigo-100 text-indigo-800 rounded-md text-sm font-medium">Data Mining</span>
            <span class="px-3 py-1 bg-indigo-100 text-indigo-800 rounded-md text-sm font-medium">Silhouette Score</span>
            <span class="px-3 py-1 bg-indigo-100 text-indigo-800 rounded-md text-sm font-medium">Data Mahasiswa</span>
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

  <!-- Verifikasi Persyaratan Card -->
  <div x-data="{ status: @js($permintaan->status), catatan: '', files: [], showAccModal: false }" class="bg-white rounded-xl shadow-sm overflow-hidden mb-6 border-2"
    :class="status === 'acc' ? 'border-emerald-400' : status === 'reject' ? 'border-red-400' : 'border-amber-400'">
    <div class="px-6 py-5 border-b border-gray-200 flex items-center justify-between flex-wrap gap-3">
      <div class="flex items-center gap-3">
        <i class="fas fa-clipboard-check text-emerald-500 text-xl"></i>
        <h3 class="text-lg font-semibold text-gray-900">Verifikasi Persyaratan</h3>
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
    <div class="p-6">
      <div class="flex flex-col gap-4">
        <div class="flex items-start gap-4 p-4 rounded-lg border"
          :class="status === 'acc' ? 'bg-emerald-50 border-emerald-300' : status === 'reject' ?
              'bg-red-50 border-red-300' : 'bg-amber-50 border-amber-300'">
          <div class="w-8 h-8 rounded-full text-white flex items-center justify-center flex-shrink-0"
            :class="status === 'acc' ? 'bg-emerald-500' : status === 'reject' ? 'bg-red-500' : 'bg-amber-500'">
            <i class="text-sm"
              :class="status === 'acc' ? 'fas fa-check' : status === 'reject' ? 'fas fa-times' : 'fas fa-exclamation'"></i>
          </div>
          <div class="flex-1">
            <div class="text-sm font-semibold text-gray-700 mb-1">File Laporan Tugas Akhir</div>
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
              <x-file-preview-item :path="$file->file_path" :uploaded-at="$file->created_at" class="rounded-lg mb-3" />
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
                  <x-action-button @click="showAccModal = true"
                    class="bg-emerald-500 hover:bg-emerald-600">
                    <i class="fas fa-check-circle mr-1"></i> Acc
                  </x-action-button>
                  <x-action-button @click="status = 'revisi'"
                    class="bg-amber-500 hover:bg-amber-600">
                    <i class="fas fa-pen mr-1"></i> Revisi
                  </x-action-button>
                  <x-action-button @click="status = 'reject'"
                    class="bg-red-500 hover:bg-red-600">
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

                  <div x-data="fileUpload()">
                    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Upload File Pendukung <span
                        class="text-xs text-gray-400 font-normal">(Opsional)</span></label>
                    <div @click="$refs.fileInput.click()" @dragover.prevent="dragging = true"
                      @dragleave.prevent="dragging = false" @drop.prevent="handleDrop($event)"
                      :class="dragging ? 'border-blue-600 bg-blue-50' : 'border-gray-300'"
                      class="border-2 border-dashed rounded-xl p-2 text-center transition-all cursor-pointer mb-6 hover:border-blue-600 hover:bg-blue-50">
                      <i class="fas fa-cloud-upload-alt text-2xl text-gray-400 mb-4"></i>
                      <p class="text-gray-500 mb-2 text-sm">
                        Drag and drop file Anda di sini, atau <span
                          class="text-blue-600 font-medium cursor-pointer">browse
                          files</span>
                      </p>
                      <p class="text-xs text-gray-400">Format yang didukung: PDF, DOC, DOCX (Maks 10MB)</p>
                    </div>
                    <input type="file" name="files[]" x-ref="fileInput" @change="handleFiles($event.target.files)"
                      class="hidden" accept=".pdf,.doc,.docx" multiple />

                    <div class="mb-6" x-show="files.length > 0">
                      <div class="text-xs font-medium text-gray-700 mb-3">File yang dipilih:</div>
                      <template x-for="(file, index) in files" :key="index">
                        <div class="flex items-center justify-between px-4 py-2 bg-gray-50 rounded-lg mb-2">
                          <div class="flex items-center gap-3">
                            <div :class="getFileIconClass(file.name)"
                              class="w-5 h-4 rounded-md flex items-center justify-center text-base">
                              <i :class="getFileIcon(file.name)"></i>
                            </div>
                            <div>
                              <div class="text-sm font-medium text-gray-900" x-text="file.name"></div>
                              <div class="text-xs text-gray-500" x-text="formatFileSize(file.size)"></div>
                            </div>
                          </div>
                          <div class="flex gap-2">
                            <button @click="viewFile(file)" type="button"
                              class="w-4 h-4 border-0 rounded-md cursor-pointer flex items-center justify-center transition-all bg-blue-100 text-blue-600 hover:bg-blue-200"
                              title="Lihat">
                              <i class="fas fa-eye"></i>
                            </button>
                            <button @click="removeFile(index)" type="button"
                              class="w-4 h-4 border-0 rounded-md cursor-pointer flex items-center justify-center transition-all bg-red-100 text-red-600 hover:bg-red-200"
                              title="Hapus">
                              <i class="fas fa-trash"></i>
                            </button>
                          </div>
                        </div>
                      </template>
                    </div>
                  </div>

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
                  <x-file-preview-item :path="$reviewFile->file_path" :uploaded-at="$reviewFile->created_at" class="rounded-lg mt-3" />
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
                  <x-file-preview-item :path="$reviewFile->file_path" :uploaded-at="$reviewFile->created_at" class="rounded-lg mt-3" />
                @endforeach
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Form Penetapan Penguji -->
  <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
    <div class="px-6 py-5 border-b border-gray-200 flex items-center justify-between flex-wrap gap-3">
      <div class="flex items-center gap-3">
        <i class="fas fa-users text-blue-500 text-xl"></i>
        <h3 class="text-lg font-semibold text-gray-900">Rekomendasi Dosen Penguji</h3>
      </div>
      <span
        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-br from-violet-500 to-indigo-500 text-white">
        <i class="fas fa-magic"></i> Auto-Recommended
      </span>
    </div>
    <div class="p-6">
      <form class="mt-2" id="formPenguji">
        <!-- Dosen Penguji Label -->
        <div class="mb-5">
          <label class="block text-sm font-semibold text-gray-700 mb-4">
            Dosen Penguji <span class="text-red-600">*</span>
            <span class="text-xs text-gray-500 font-normal ml-1">(Direkomendasikan berdasarkan analisis sistem)</span>
          </label>

          <div class="flex flex-col gap-6" id="pengujiContainer">

            <!-- ==================== Penguji 1 ==================== -->
            <div
              class="bg-gradient-to-br from-green-50 to-emerald-50 border border-green-300 rounded-xl p-5 transition-all"
              id="pengujiCard1" data-dosen-id="6">
              <!-- Header -->
              <div class="flex items-center justify-between flex-wrap gap-3 mb-4">
                <div class="flex items-center gap-3">
                  <span
                    class="w-9 h-9 bg-gradient-to-br from-emerald-500 to-emerald-600 text-white rounded-full flex items-center justify-center text-base font-bold flex-shrink-0">1</span>
                  <span class="text-sm font-semibold text-gray-700">Penguji 1 (Ranking #1)</span>
                </div>
                <div class="flex gap-2 flex-wrap">
                  <div
                    class="flex flex-col items-center px-3 py-2 rounded-lg bg-gradient-to-br from-green-100 to-emerald-100">
                    <span class="text-[10px] font-semibold uppercase tracking-wide text-emerald-800">Skor
                      Rekomendasi</span>
                    <span class="text-lg font-bold text-emerald-600">0.847</span>
                  </div>
                </div>
              </div>

              <!-- Info Display -->
              <div
                class="flex items-center justify-between p-4 bg-gradient-to-r from-white to-green-50 border border-green-200 rounded-lg mb-3">
                <div class="flex items-center gap-3">
                  <div
                    class="w-11 h-11 bg-gradient-to-br from-indigo-500 to-violet-500 rounded-full flex items-center justify-center text-white font-bold text-base">
                    NR</div>
                  <div>
                    <h4 class="text-[15px] font-semibold text-gray-800">Natalis Ransi, S.Kom., M.Cs.</h4>
                    <div class="text-xs text-gray-500 mt-0.5">
                      <span class="mr-3"><i class="fas fa-id-badge"></i> 197605152005011001</span>
                      <span><i class="fas fa-award"></i> Lektor</span>
                    </div>
                  </div>
                </div>
                <button type="button"
                  class="px-4 py-2 bg-amber-100 text-amber-800 border border-amber-300 rounded-lg text-xs font-semibold hover:bg-amber-200 hover:border-amber-400 transition-all flex items-center gap-1.5 cursor-pointer"
                  onclick="openGantiModal(1)">
                  <i class="fas fa-exchange-alt text-[11px]"></i> Ganti
                </button>
              </div>
              <input type="hidden" id="penguji1" name="penguji1" value="6" required />

              <!-- Detail Perhitungan -->
              <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                <div class="flex items-center gap-2 px-4 py-3 cursor-pointer hover:bg-gray-50 transition-colors"
                  onclick="toggleReason('reason1')">
                  <i class="fas fa-calculator text-violet-500"></i>
                  <span class="text-xs font-semibold text-gray-700">Lihat Detail Perhitungan</span>
                  <i class="fas fa-chevron-down text-gray-400 text-xs ml-auto transition-transform" id="toggle1"></i>
                </div>
                <div class="hidden border-t border-gray-200 bg-gray-50 p-4" id="reason1">
                  <!-- CBF -->
                  <div class="mb-4 pb-4 border-b border-dashed border-gray-200">
                    <div class="text-xs font-semibold text-indigo-500 mb-2 flex items-center gap-2">
                      <i class="fas fa-project-diagram text-[11px]"></i> Content-Based Filtering (Cosine Similarity)
                    </div>
                    <div
                      class="flex items-center justify-between px-3 py-2 bg-blue-100 rounded-md text-sm font-semibold">
                      <span class="text-blue-800">Nilai Similarity (CBF)</span>
                      <span class="text-blue-800 text-base">0.90</span>
                    </div>
                  </div>
                  <!-- MAUT -->
                  <div>
                    <div class="text-xs font-semibold text-indigo-500 mb-2 flex items-center gap-2">
                      <i class="fas fa-balance-scale text-[11px]"></i> Multi-Attribute Utility Theory (MAUT)
                    </div>
                    <table class="w-full border-collapse text-xs mb-2 bg-white">
                      <thead>
                        <tr>
                          <th class="bg-gray-100 px-2 py-2 text-left font-semibold text-gray-700 border border-gray-200">
                            Atribut</th>
                          <th
                            class="bg-gray-100 px-2 py-2 text-center font-semibold text-gray-700 border border-gray-200">
                            Nilai</th>
                          <th
                            class="bg-gray-100 px-2 py-2 text-center font-semibold text-gray-700 border border-gray-200">
                            Bobot</th>
                          <th
                            class="bg-gray-100 px-2 py-2 text-right font-semibold text-gray-700 border border-gray-200">
                            Utility</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td class="px-2 py-2 border border-gray-200 font-medium text-gray-600">Similarity (CBF)</td>
                          <td class="px-2 py-2 border border-gray-200 text-center text-gray-600">0.90</td>
                          <td class="px-2 py-2 border border-gray-200 text-center text-gray-600">0.35</td>
                          <td class="px-2 py-2 border border-gray-200 text-right text-gray-600">0.315</td>
                        </tr>
                        <tr>
                          <td class="px-2 py-2 border border-gray-200 font-medium text-gray-600">Beban Bimbingan</td>
                          <td class="px-2 py-2 border border-gray-200 text-center text-gray-600">0.80</td>
                          <td class="px-2 py-2 border border-gray-200 text-center text-gray-600">0.25</td>
                          <td class="px-2 py-2 border border-gray-200 text-right text-gray-600">0.200</td>
                        </tr>
                        <tr>
                          <td class="px-2 py-2 border border-gray-200 font-medium text-gray-600">Jabatan Fungsional</td>
                          <td class="px-2 py-2 border border-gray-200 text-center text-gray-600">0.85</td>
                          <td class="px-2 py-2 border border-gray-200 text-center text-gray-600">0.20</td>
                          <td class="px-2 py-2 border border-gray-200 text-right text-gray-600">0.170</td>
                        </tr>
                        <tr>
                          <td class="px-2 py-2 border border-gray-200 font-medium text-gray-600">Pengalaman Menguji</td>
                          <td class="px-2 py-2 border border-gray-200 text-center text-gray-600">0.81</td>
                          <td class="px-2 py-2 border border-gray-200 text-center text-gray-600">0.20</td>
                          <td class="px-2 py-2 border border-gray-200 text-right text-gray-600">0.162</td>
                        </tr>
                      </tbody>
                    </table>
                    <div
                      class="flex items-center justify-between px-3 py-2 bg-gradient-to-r from-green-100 to-emerald-100 rounded-md text-sm font-semibold">
                      <span class="text-emerald-800">Total Skor MAUT</span>
                      <span class="text-emerald-800 text-base">0.847</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- ==================== Penguji 2 ==================== -->
            <div
              class="bg-gradient-to-br from-green-50 to-emerald-50 border border-green-300 rounded-xl p-5 transition-all"
              id="pengujiCard2" data-dosen-id="5">
              <!-- Header -->
              <div class="flex items-center justify-between flex-wrap gap-3 mb-4">
                <div class="flex items-center gap-3">
                  <span
                    class="w-9 h-9 bg-gradient-to-br from-emerald-500 to-emerald-600 text-white rounded-full flex items-center justify-center text-base font-bold flex-shrink-0">2</span>
                  <span class="text-sm font-semibold text-gray-700">Penguji 2 (Ranking #2)</span>
                </div>
                <div class="flex gap-2 flex-wrap">
                  <div
                    class="flex flex-col items-center px-3 py-2 rounded-lg bg-gradient-to-br from-green-100 to-emerald-100">
                    <span class="text-[10px] font-semibold uppercase tracking-wide text-emerald-800">Skor
                      Rekomendasi</span>
                    <span class="text-lg font-bold text-emerald-600">0.793</span>
                  </div>
                </div>
              </div>

              <!-- Info Display -->
              <div
                class="flex items-center justify-between p-4 bg-gradient-to-r from-white to-green-50 border border-green-200 rounded-lg mb-3">
                <div class="flex items-center gap-3">
                  <div
                    class="w-11 h-11 bg-gradient-to-br from-indigo-500 to-violet-500 rounded-full flex items-center justify-center text-white font-bold text-base">
                    MY</div>
                  <div>
                    <h4 class="text-[15px] font-semibold text-gray-800">Muh. Yamin, S.T., M.Eng.</h4>
                    <div class="text-xs text-gray-500 mt-0.5">
                      <span class="mr-3"><i class="fas fa-id-badge"></i> 197803252006041002</span>
                      <span><i class="fas fa-award"></i> Lektor</span>
                    </div>
                  </div>
                </div>
                <button type="button"
                  class="px-4 py-2 bg-amber-100 text-amber-800 border border-amber-300 rounded-lg text-xs font-semibold hover:bg-amber-200 hover:border-amber-400 transition-all flex items-center gap-1.5 cursor-pointer"
                  onclick="openGantiModal(2)">
                  <i class="fas fa-exchange-alt text-[11px]"></i> Ganti
                </button>
              </div>
              <input type="hidden" id="penguji2" name="penguji2" value="5" required />

              <!-- Detail Perhitungan -->
              <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                <div class="flex items-center gap-2 px-4 py-3 cursor-pointer hover:bg-gray-50 transition-colors"
                  onclick="toggleReason('reason2')">
                  <i class="fas fa-calculator text-violet-500"></i>
                  <span class="text-xs font-semibold text-gray-700">Lihat Detail Perhitungan</span>
                  <i class="fas fa-chevron-down text-gray-400 text-xs ml-auto transition-transform" id="toggle2"></i>
                </div>
                <div class="hidden border-t border-gray-200 bg-gray-50 p-4" id="reason2">
                  <!-- CBF -->
                  <div class="mb-4 pb-4 border-b border-dashed border-gray-200">
                    <div class="text-xs font-semibold text-indigo-500 mb-2 flex items-center gap-2">
                      <i class="fas fa-project-diagram text-[11px]"></i> Content-Based Filtering (Cosine Similarity)
                    </div>
                    <div
                      class="flex items-center justify-between px-3 py-2 bg-blue-100 rounded-md text-sm font-semibold">
                      <span class="text-blue-800">Nilai Similarity (CBF)</span>
                      <span class="text-blue-800 text-base">0.81</span>
                    </div>
                  </div>
                  <!-- MAUT -->
                  <div>
                    <div class="text-xs font-semibold text-indigo-500 mb-2 flex items-center gap-2">
                      <i class="fas fa-balance-scale text-[11px]"></i> Multi-Attribute Utility Theory (MAUT)
                    </div>
                    <table class="w-full border-collapse text-xs mb-2 bg-white">
                      <thead>
                        <tr>
                          <th class="bg-gray-100 px-2 py-2 text-left font-semibold text-gray-700 border border-gray-200">
                            Atribut</th>
                          <th
                            class="bg-gray-100 px-2 py-2 text-center font-semibold text-gray-700 border border-gray-200">
                            Nilai</th>
                          <th
                            class="bg-gray-100 px-2 py-2 text-center font-semibold text-gray-700 border border-gray-200">
                            Bobot</th>
                          <th
                            class="bg-gray-100 px-2 py-2 text-right font-semibold text-gray-700 border border-gray-200">
                            Utility</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td class="px-2 py-2 border border-gray-200 font-medium text-gray-600">Similarity (CBF)</td>
                          <td class="px-2 py-2 border border-gray-200 text-center text-gray-600">0.81</td>
                          <td class="px-2 py-2 border border-gray-200 text-center text-gray-600">0.35</td>
                          <td class="px-2 py-2 border border-gray-200 text-right text-gray-600">0.284</td>
                        </tr>
                        <tr>
                          <td class="px-2 py-2 border border-gray-200 font-medium text-gray-600">Beban Bimbingan</td>
                          <td class="px-2 py-2 border border-gray-200 text-center text-gray-600">0.90</td>
                          <td class="px-2 py-2 border border-gray-200 text-center text-gray-600">0.25</td>
                          <td class="px-2 py-2 border border-gray-200 text-right text-gray-600">0.225</td>
                        </tr>
                        <tr>
                          <td class="px-2 py-2 border border-gray-200 font-medium text-gray-600">Jabatan Fungsional</td>
                          <td class="px-2 py-2 border border-gray-200 text-center text-gray-600">0.72</td>
                          <td class="px-2 py-2 border border-gray-200 text-center text-gray-600">0.20</td>
                          <td class="px-2 py-2 border border-gray-200 text-right text-gray-600">0.144</td>
                        </tr>
                        <tr>
                          <td class="px-2 py-2 border border-gray-200 font-medium text-gray-600">Pengalaman Menguji</td>
                          <td class="px-2 py-2 border border-gray-200 text-center text-gray-600">0.70</td>
                          <td class="px-2 py-2 border border-gray-200 text-center text-gray-600">0.20</td>
                          <td class="px-2 py-2 border border-gray-200 text-right text-gray-600">0.140</td>
                        </tr>
                      </tbody>
                    </table>
                    <div
                      class="flex items-center justify-between px-3 py-2 bg-gradient-to-r from-green-100 to-emerald-100 rounded-md text-sm font-semibold">
                      <span class="text-emerald-800">Total Skor MAUT</span>
                      <span class="text-emerald-800 text-base">0.793</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- ==================== Penguji 3 ==================== -->
            <div
              class="bg-gradient-to-br from-green-50 to-emerald-50 border border-green-300 rounded-xl p-5 transition-all"
              id="pengujiCard3" data-dosen-id="2">
              <!-- Header -->
              <div class="flex items-center justify-between flex-wrap gap-3 mb-4">
                <div class="flex items-center gap-3">
                  <span
                    class="w-9 h-9 bg-gradient-to-br from-emerald-500 to-emerald-600 text-white rounded-full flex items-center justify-center text-base font-bold flex-shrink-0">3</span>
                  <span class="text-sm font-semibold text-gray-700">Penguji 3 (Ranking #3)</span>
                </div>
                <div class="flex gap-2 flex-wrap">
                  <div
                    class="flex flex-col items-center px-3 py-2 rounded-lg bg-gradient-to-br from-green-100 to-emerald-100">
                    <span class="text-[10px] font-semibold uppercase tracking-wide text-emerald-800">Skor
                      Rekomendasi</span>
                    <span class="text-lg font-bold text-emerald-600">0.756</span>
                  </div>
                </div>
              </div>

              <!-- Info Display -->
              <div
                class="flex items-center justify-between p-4 bg-gradient-to-r from-white to-green-50 border border-green-200 rounded-lg mb-3">
                <div class="flex items-center gap-3">
                  <div
                    class="w-11 h-11 bg-gradient-to-br from-indigo-500 to-violet-500 rounded-full flex items-center justify-center text-white font-bold text-base">
                    RA</div>
                  <div>
                    <h4 class="text-[15px] font-semibold text-gray-800">Rizal Adi Saputra, S.Kom., M.Kom.</h4>
                    <div class="text-xs text-gray-500 mt-0.5">
                      <span class="mr-3"><i class="fas fa-id-badge"></i> 198507122010121003</span>
                      <span><i class="fas fa-award"></i> Asisten Ahli</span>
                    </div>
                  </div>
                </div>
                <button type="button"
                  class="px-4 py-2 bg-amber-100 text-amber-800 border border-amber-300 rounded-lg text-xs font-semibold hover:bg-amber-200 hover:border-amber-400 transition-all flex items-center gap-1.5 cursor-pointer"
                  onclick="openGantiModal(3)">
                  <i class="fas fa-exchange-alt text-[11px]"></i> Ganti
                </button>
              </div>
              <input type="hidden" id="penguji3" name="penguji3" value="2" required />

              <!-- Detail Perhitungan -->
              <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                <div class="flex items-center gap-2 px-4 py-3 cursor-pointer hover:bg-gray-50 transition-colors"
                  onclick="toggleReason('reason3')">
                  <i class="fas fa-calculator text-violet-500"></i>
                  <span class="text-xs font-semibold text-gray-700">Lihat Detail Perhitungan</span>
                  <i class="fas fa-chevron-down text-gray-400 text-xs ml-auto transition-transform" id="toggle3"></i>
                </div>
                <div class="hidden border-t border-gray-200 bg-gray-50 p-4" id="reason3">
                  <!-- CBF -->
                  <div class="mb-4 pb-4 border-b border-dashed border-gray-200">
                    <div class="text-xs font-semibold text-indigo-500 mb-2 flex items-center gap-2">
                      <i class="fas fa-project-diagram text-[11px]"></i> Content-Based Filtering (Cosine Similarity)
                    </div>
                    <div
                      class="flex items-center justify-between px-3 py-2 bg-blue-100 rounded-md text-sm font-semibold">
                      <span class="text-blue-800">Nilai Similarity (CBF)</span>
                      <span class="text-blue-800 text-base">0.76</span>
                    </div>
                  </div>
                  <!-- MAUT -->
                  <div>
                    <div class="text-xs font-semibold text-indigo-500 mb-2 flex items-center gap-2">
                      <i class="fas fa-balance-scale text-[11px]"></i> Multi-Attribute Utility Theory (MAUT)
                    </div>
                    <table class="w-full border-collapse text-xs mb-2 bg-white">
                      <thead>
                        <tr>
                          <th class="bg-gray-100 px-2 py-2 text-left font-semibold text-gray-700 border border-gray-200">
                            Atribut</th>
                          <th
                            class="bg-gray-100 px-2 py-2 text-center font-semibold text-gray-700 border border-gray-200">
                            Nilai</th>
                          <th
                            class="bg-gray-100 px-2 py-2 text-center font-semibold text-gray-700 border border-gray-200">
                            Bobot</th>
                          <th
                            class="bg-gray-100 px-2 py-2 text-right font-semibold text-gray-700 border border-gray-200">
                            Utility</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td class="px-2 py-2 border border-gray-200 font-medium text-gray-600">Similarity (CBF)</td>
                          <td class="px-2 py-2 border border-gray-200 text-center text-gray-600">0.76</td>
                          <td class="px-2 py-2 border border-gray-200 text-center text-gray-600">0.35</td>
                          <td class="px-2 py-2 border border-gray-200 text-right text-gray-600">0.266</td>
                        </tr>
                        <tr>
                          <td class="px-2 py-2 border border-gray-200 font-medium text-gray-600">Beban Bimbingan</td>
                          <td class="px-2 py-2 border border-gray-200 text-center text-gray-600">0.85</td>
                          <td class="px-2 py-2 border border-gray-200 text-center text-gray-600">0.25</td>
                          <td class="px-2 py-2 border border-gray-200 text-right text-gray-600">0.213</td>
                        </tr>
                        <tr>
                          <td class="px-2 py-2 border border-gray-200 font-medium text-gray-600">Jabatan Fungsional</td>
                          <td class="px-2 py-2 border border-gray-200 text-center text-gray-600">0.70</td>
                          <td class="px-2 py-2 border border-gray-200 text-center text-gray-600">0.20</td>
                          <td class="px-2 py-2 border border-gray-200 text-right text-gray-600">0.140</td>
                        </tr>
                        <tr>
                          <td class="px-2 py-2 border border-gray-200 font-medium text-gray-600">Pengalaman Menguji</td>
                          <td class="px-2 py-2 border border-gray-200 text-center text-gray-600">0.69</td>
                          <td class="px-2 py-2 border border-gray-200 text-center text-gray-600">0.20</td>
                          <td class="px-2 py-2 border border-gray-200 text-right text-gray-600">0.137</td>
                        </tr>
                      </tbody>
                    </table>
                    <div
                      class="flex items-center justify-between px-3 py-2 bg-gradient-to-r from-green-100 to-emerald-100 rounded-md text-sm font-semibold">
                      <span class="text-emerald-800">Total Skor MAUT</span>
                      <span class="text-emerald-800 text-base">0.756</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>

        <!-- Catatan -->
        <div class="mb-5">
          <label class="block text-sm font-semibold text-gray-700 mb-2">
            Catatan <span class="text-xs text-gray-500 font-normal">(Opsional)</span>
          </label>
          <textarea
            class="w-full px-4 py-3 border border-gray-300 rounded-lg text-sm text-gray-700 resize-y min-h-[100px] font-inherit transition-all focus:outline-none focus:border-blue-500 focus:ring-[3px] focus:ring-blue-500/10"
            id="catatan" placeholder="Tambahkan catatan jika diperlukan..."></textarea>
        </div>

        <!-- Button Group -->
        <div class="flex gap-4 justify-end mt-6 pt-6 border-t border-gray-200">
          <a href="permintaan-penguji.html"
            class="px-6 py-3 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 hover:border-gray-400 transition-all flex items-center gap-2 no-underline">
            <i class="fas fa-arrow-left"></i> Kembali
          </a>
          <button type="submit"
            class="px-6 py-3 bg-gradient-to-br from-emerald-500 to-emerald-600 border-none text-white rounded-lg text-sm font-medium hover:-translate-y-0.5 hover:shadow-lg hover:shadow-emerald-500/30 transition-all flex items-center gap-2 cursor-pointer">
            <i class="fas fa-check"></i> Tetapkan Penguji
          </button>
        </div>
      </form>
    </div>
  </div>

  <script>
    // Alpine.js Component for File Upload
    function fileUpload() {
      return {
        files: [],
        dragging: false,

        handleFiles(fileList) {
          const maxSize = 10 * 1024 * 1024; // 10MB
          const allowedTypes = ['application/pdf', 'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
          ];

          Array.from(fileList).forEach(file => {
            // Validate file size
            if (file.size > maxSize) {
              alert(`File ${file.name} terlalu besar. Maksimal 10MB`);
              return;
            }

            // Validate file type
            if (!allowedTypes.includes(file.type) && !file.name.match(/\.(pdf|doc|docx)$/i)) {
              alert(`File ${file.name} format tidak didukung. Hanya PDF, DOC, DOCX`);
              return;
            }

            const isDuplicate = this.files.some(existingFile =>
              existingFile.name === file.name &&
              existingFile.size === file.size &&
              existingFile.lastModified === file.lastModified
            );

            if (isDuplicate) {
              return;
            }

            this.files.push(file);
          });
          this.syncInputFiles();
        },

        handleDrop(e) {
          this.dragging = false;
          const files = e.dataTransfer.files;
          this.handleFiles(files);
        },

        removeFile(index) {
          this.files.splice(index, 1);
          this.syncInputFiles();
        },

        viewFile(file) {
          const url = URL.createObjectURL(file);
          window.open(url, '_blank');
        },

        getFileIcon(filename) {
          const ext = filename.split('.').pop().toLowerCase();
          return ext === 'pdf' ? 'fas fa-file-pdf' : 'fas fa-file-word';
        },

        getFileIconClass(filename) {
          const ext = filename.split('.').pop().toLowerCase();
          return ext === 'pdf' ? 'bg-red-100 text-red-600' : 'bg-blue-100 text-blue-600';
        },

        formatFileSize(bytes) {
          return (bytes / 1024 / 1024).toFixed(2) + ' MB';
        },

        syncInputFiles() {
          const dataTransfer = new DataTransfer();

          this.files.forEach(file => {
            dataTransfer.items.add(file);
          });

          this.$refs.fileInput.files = dataTransfer.files;
        }
      }
    }
  </script>
@endsection
