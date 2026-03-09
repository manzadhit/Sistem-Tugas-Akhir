@extends('layouts.app')

@section('title', 'Bimbingan')

@section('sidebar')
  @include('mahasiswa.sidebar')
@endsection

@section('content')
  @php
    $kajurStepLabel = $jenis === 'proposal' ? 'Minta Penguji' : 'Persetujuan Kajur';
  @endphp

  <!-- Page Banner -->
  @if ($tahapanSelesai)
    <div class="relative h-40 rounded-xl overflow-hidden mb-8 bg-gradient-to-br from-emerald-500 to-emerald-700">
      <div class="absolute inset-0 flex items-center justify-center flex-col text-white text-center p-4">
        <div class="flex items-center gap-2 mb-2">
          <i class="fas fa-check-circle text-2xl"></i>
          <h1 class="text-xl sm:text-2xl md:text-3xl font-bold">Bimbingan {{ ucfirst($jenis) }} Selesai</h1>
        </div>
        <p class="text-xs sm:text-sm md:text-base opacity-90">
          Anda telah menyelesaikan tahap {{ $jenis }} dan lanjut ke tahap {{ ucfirst($tugasAkhir->tahapan) }}
        </p>
      </div>
    </div>
  @else
    <div class="relative h-40 rounded-xl overflow-hidden mb-8 bg-gradient-to-br from-blue-600 to-blue-800">
      <div class="absolute inset-0 flex items-center justify-center flex-col text-white text-center p-4">
        <h1 class="text-xl sm:text-2xl md:text-3xl font-bold mb-2">Bimbingan {{ ucfirst($jenis) }}</h1>
        <p class="text-xs sm:text-sm md:text-base opacity-90">
          Upload dan kelola laporan bimbingan {{ $jenis }} Anda
        </p>
      </div>
    </div>
  @endif

  <!-- Status Alerts per Pembimbing -->
  @foreach ($latestPerPembimbing as $latest)
    <x-status-alert :status="$latest->status" :pembimbing-label="$latest->dosenPembimbing->getJenisPembimbing()" />
  @endforeach

  <!-- Progress Bar -->
  <div class="bg-white rounded-xl p-6 shadow-sm mb-8">
    <div class="flex justify-between relative">
      <!-- Progress Line -->
      <div
        class="absolute top-5 left-[25%] right-[25%] h-0.5 {{ $tahapanSelesai ? 'bg-emerald-400' : 'bg-gray-200' }} z-0">
      </div>

      <!-- Step 1: Bimbingan -->
      <div class="flex flex-col items-center relative z-10 flex-1">
        <div
          class="w-10 h-10 rounded-full flex items-center justify-center text-base mb-2 transition-all duration-300
          {{ $tahapanSelesai ? 'bg-emerald-500 text-white shadow-[0_0_0_4px_rgba(16,185,129,0.2)]' : 'bg-blue-600 text-white shadow-[0_0_0_4px_rgba(37,99,235,0.2)]' }}">
          <i class="{{ $tahapanSelesai ? 'fas fa-check' : 'fas fa-comments' }}"></i>
        </div>
        <span
          class="text-[10px] sm:text-xs font-semibold text-center {{ $tahapanSelesai ? 'text-emerald-600' : 'text-blue-600' }}">Bimbingan</span>
      </div>

      <!-- Step 2: Tahap Kajur (selalu abu di halaman ini) -->
      <div class="flex flex-col items-center relative z-10 flex-1">
        <div
          class="w-10 h-10 rounded-full flex items-center justify-center text-base mb-2 transition-all duration-300
          {{ $tahapanSelesai ? 'bg-emerald-500 text-white shadow-[0_0_0_4px_rgba(16,185,129,0.2)]' : 'bg-gray-200 text-gray-400' }}">
          <i class="{{ $tahapanSelesai ? 'fas fa-check' : 'fas fa-user-check' }}"></i>
        </div>
        <span
          class="text-[10px] sm:text-xs font-medium text-center {{ $tahapanSelesai ? 'text-emerald-600 font-semibold' : 'text-gray-500' }}">{{ $kajurStepLabel }}</span>
      </div>
    </div>
  </div>

  <!-- Flash Messages -->
  <x-alert type="success" />
  <x-alert type="error" />
  <x-alert type="warning" />

  @if ($hasTwoAccPembimbing && !$tahapanSelesai)
    <div class="mb-4 flex justify-center">
      <a href="{{ route('mahasiswa.bimbingan.mintaPenguji', ['jenis' => $jenis]) }}"
        class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium bg-blue-600 text-white hover:bg-blue-800 transition-all">
        <i class="fas fa-user-check"></i>
        {{ $kajurStepLabel }}
      </a>
    </div>
  @endif

  <!-- Upload Form Card -->
  <form action="{{ route('mahasiswa.bimbingan.createSubmission', ['jenis' => $jenis]) }}" method="POST"
    enctype="multipart/form-data" x-data="fileUpload()"
    class="bg-white rounded-xl shadow-sm overflow-hidden mb-8 {{ $hasTwoAccPembimbing || $tahapanSelesai ? 'hidden' : '' }}">
    @csrf

    <div class="px-6 py-5 border-b border-gray-200 flex justify-between items-center">
      <h3 class="text-lg font-semibold text-gray-900">Upload Laporan Bimbingan</h3>
    </div>
    <div class="p-6">
      <!-- Select Pembimbing -->
      <div class="mb-5">
        <label class="block text-sm font-medium text-gray-700 mb-2">
          Kirim ke Pembimbing <span class="text-red-600">*</span>
        </label>
        <select name="pembimbing" x-model="selectedPembimbing"
          class="w-full px-3 py-3 border border-gray-300 rounded-lg text-[0.95rem] bg-white cursor-pointer transition-colors focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-100 disabled:bg-gray-100 disabled:cursor-not-allowed disabled:text-gray-400">
          <option value="" disabled selected>-- Pilih Pembimbing --</option>
          @foreach ($pembimbing as $p)
            <option value="{{ $p->id }}" {{ $p->hasSubmission || $p->isAcc ? 'disabled' : '' }}>
              Pembimbing {{ $loop->iteration }} - {{ $p->dosen->nama_lengkap }}
              @if ($p->hasSubmission)
                (Menunggu Review)
              @elseif ($p->isAcc)
                (Sudah ACC)
              @endif
            </option>
          @endforeach
        </select>
      </div>

      <div>
        <!-- Upload Area -->
        <div @click="$refs.fileInput.click()" @dragover.prevent="dragging = true" @dragleave.prevent="dragging = false"
          @drop.prevent="handleDrop($event)" :class="dragging ? 'border-blue-600 bg-blue-50' : 'border-gray-300'"
          class="border-2 border-dashed rounded-xl p-10 text-center transition-all cursor-pointer mb-6 hover:border-blue-600 hover:bg-blue-50">
          <i class="fas fa-cloud-upload-alt text-5xl text-gray-400 mb-4"></i>
          <p class="text-gray-500 mb-2">
            Drag and drop file Anda di sini, atau <span class="text-blue-600 font-medium cursor-pointer">browse
              files</span>
          </p>
          <p class="text-xs text-gray-400">
            Format yang didukung: PDF, DOC, DOCX (Maks 10MB) - Upload satu
            atau beberapa BAB sekaligus
          </p>
        </div>
        <input type="file" name="file_submission[]" x-ref="fileInput" @change="handleFiles($event.target.files)"
          class="hidden" accept=".pdf,.doc,.docx" multiple />

        <!-- Selected Files -->
        <div class="mb-6" x-show="files.length > 0">
          <div class="text-sm font-medium text-gray-700 mb-3">File yang dipilih:</div>

          <template x-for="(file, index) in files" :key="index">
            <div class="flex items-center justify-between px-4 py-3 bg-gray-50 rounded-lg mb-2">
              <div class="flex items-center gap-3">
                <div :class="getFileIconClass(file.name)"
                  class="w-9 h-9 rounded-md flex items-center justify-center text-base">
                  <i :class="getFileIcon(file.name)"></i>
                </div>
                <div>
                  <div class="text-sm font-medium text-gray-900" x-text="file.name"></div>
                  <div class="text-xs text-gray-500" x-text="formatFileSize(file.size)"></div>
                </div>
              </div>
              <div class="flex gap-2">
                <button @click="viewFile(file)" type="button"
                  class="w-8 h-8 border-0 rounded-md cursor-pointer flex items-center justify-center transition-all bg-blue-100 text-blue-600 hover:bg-blue-200"
                  title="Lihat">
                  <i class="fas fa-eye"></i>
                </button>
                <button @click="removeFile(index)" type="button"
                  class="w-8 h-8 border-0 rounded-md cursor-pointer flex items-center justify-center transition-all bg-red-100 text-red-600 hover:bg-red-200"
                  title="Hapus">
                  <i class="fas fa-trash"></i>
                </button>
              </div>
            </div>
          </template>
        </div>
      </div>

      <!-- Notes/Description -->
      <div class="mb-5">
        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan untuk Pembimbing</label>
        <textarea name="catatan"
          class="w-full px-3 py-3 border border-gray-300 rounded-lg text-[0.95rem] resize-y min-h-[100px] transition-colors focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-100"
          placeholder="Tambahkan catatan atau keterangan mengenai laporan yang diupload..."></textarea>
      </div>

      <!-- Action Buttons -->
      <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 mt-6">
        <button type="submit" :disabled="files.length === 0 || !selectedPembimbing"
          class="inline-flex items-center gap-2 px-4 py-2 md:px-6 md:py-3 rounded-lg text-sm font-medium transition-all border-0 bg-blue-600 text-white hover:bg-blue-800 disabled:bg-blue-300 disabled:cursor-not-allowed disabled:pointer-events-none">
          <i class="fas fa-paper-plane"></i>
          Upload & Kirim
        </button>
      </div>
    </div>
  </form>

  <!-- History Section -->
  <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-8">
    <div class="p-4 sm:p-6">
      @if ($tahapanSelesai)
        <div class="flex items-center gap-3 mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3">
          <i class="fas fa-check-circle text-emerald-600 text-lg"></i>
          <div>
            <p class="text-sm font-semibold text-emerald-800">Tahap {{ ucfirst($jenis) }} Selesai</p>
            <p class="text-xs text-emerald-700">Riwayat bimbingan {{ $jenis }} ini hanya dapat dilihat. Lanjutkan
              ke tahap
              <span class="font-bold">{{ ucfirst($tugasAkhir->tahapan) }}</span>.
            </p>
          </div>
        </div>
      @endif
      <div class="bg-gray-50 rounded-xl p-4 sm:p-6">
        <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center gap-2">
          <i class="fas fa-history text-blue-600"></i>
          Riwayat Pengajuan
        </h3>

        @php
          $statusConfig = [
              'pending' => ['label' => 'Menunggu Review', 'badge_class' => 'bg-amber-50 text-amber-600'],
              'revisi' => ['label' => 'Revisi', 'badge_class' => 'bg-amber-50 text-amber-800'],
              'acc' => ['label' => 'ACC', 'badge_class' => 'bg-emerald-50 text-emerald-700'],
              'reject' => ['label' => 'Ditolak', 'badge_class' => 'bg-red-50 text-red-700'],
          ];
        @endphp

        @forelse ($allSubmission as $submission)
          @php
            $historyId = $loop->index;
            $status = $statusConfig[$submission->status] ?? $statusConfig['pending'];
            $fileCount = $submission->submissionFiles->count();
            $fileMahasiswa = $submission->submissionFiles->where('uploaded_by', 'mahasiswa');
            $fileDosen = $submission->submissionFiles->where('uploaded_by', 'dosen');
          @endphp

          <div class="bg-white border border-gray-200 rounded-lg mb-3 overflow-hidden last:mb-0">
            <div class="p-4 cursor-pointer transition-colors hover:bg-gray-50"
              onclick="toggleHistory({{ $historyId }})">
              <div class="flex flex-col items-start gap-3 lg:flex-row lg:items-center lg:justify-between">
                <div class="w-full lg:flex-1">
                  <h4 class="text-[0.95rem] font-medium text-gray-900 mb-2 flex flex-wrap items-center gap-2 sm:gap-3">
                    Bimbingan {{ ucfirst($jenis) }}
                    <span
                      class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $status['badge_class'] }}">
                      {{ $status['label'] }}
                    </span>
                  </h4>
                  <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-[0.75rem] sm:text-[0.8rem] text-gray-500">
                    <span><i class="far fa-clock"></i> {{ $submission->created_at?->format('d M Y, H:i') }}</span>
                    <span><i class="far fa-file"></i> {{ $fileCount }} file</span>
                    <span><i class="fas fa-user"></i> {{ $submission->status == 'pending' ? 'Dikirim ke:' : 'Dari: ' }}
                      {{ $submission->dosenPembimbing->getJenisPembimbing() }}</span>
                  </div>
                </div>
                <div class="flex w-full items-center justify-end gap-2 sm:gap-3 lg:w-auto">
                  <i class="fas fa-chevron-down text-sm sm:text-base text-gray-400 transition-transform"
                    id="chevron-{{ $historyId }}"></i>
                </div>
              </div>
            </div>

            <div class="hidden border-t border-gray-200 bg-gray-50 p-3 sm:p-4" id="files-{{ $historyId }}">

              {{-- Dosen   --}}
              @if ($fileDosen->isNotEmpty())
                <div class="text-[0.8rem] font-medium text-gray-500 mb-3">Files dari Dosen:</div>
                @foreach ($fileDosen as $file)
                  <x-file-preview-item :path="$file->file_path" :uploaded-at="$file->created_at" />
                @endforeach
              @endif

              @if (!empty($submission->review))
                <div class="mb-3 rounded-md border border-yellow-200 bg-yellow-50 px-3 py-2 text-sm text-yellow-900">
                  <div class="font-semibold">Catatan Pembimbing:</div>
                  <div>{{ $submission->review }}</div>
                </div>
              @endif

              {{-- Mahasiswa --}}
              @if ($fileMahasiswa->isNotEmpty())
                <div class="text-[0.8rem] font-medium text-gray-500 mb-3">Files Mahasiswa:</div>
                @foreach ($fileMahasiswa as $file)
                  <x-file-preview-item :path="$file->file_path" :uploaded-at="$file->created_at" />
                @endforeach
              @endif

              @if (!empty($submission->catatan))
                <div class="mb-3 rounded-md border   px-3 py-2 text-sm ">
                  <div class="font-semibold">Catatan :</div>
                  <div>{{ $submission->catatan }}</div>
                </div>
              @endif
            </div>
          </div>
        @empty
          <div
            class="rounded-lg border border-dashed border-gray-300 bg-white px-4 py-6 text-center text-sm text-gray-500">
            Belum ada riwayat pengajuan bimbingan.
          </div>
        @endforelse
      </div>
    </div>
  </div>

  <script>
    // Alpine.js Component for File Upload
    function fileUpload() {
      return {
        files: [],
        dragging: false,
        selectedPembimbing: '',

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

    // Toggle History Files
    function toggleHistory(id) {
      const filesDiv = document.getElementById(`files-${id}`);
      const chevron = document.getElementById(`chevron-${id}`);

      if (filesDiv.classList.contains('hidden')) {
        filesDiv.classList.remove('hidden');
        chevron.classList.add('rotate-180');
      } else {
        filesDiv.classList.add('hidden');
        chevron.classList.remove('rotate-180');
      }
    }
  </script>
@endsection
