@extends('layouts.app')

@section('title', 'Minta Penguji')

@section('sidebar')
  @include('mahasiswa.sidebar')
@endsection

@section('content')
  <!-- Page Banner -->
  <div class="relative mb-8 h-40 overflow-hidden rounded-xl bg-gradient-to-br from-blue-600 to-blue-700">
    <div class="absolute inset-0 flex flex-col items-center justify-center px-4 text-center text-white">
      <h1 class="mb-2 text-xl sm:text-[1.75rem] md:text-[2rem] font-bold">Pengajuan Penguji</h1>
      <p class="text-xs sm:text-sm md:text-base opacity-90">
        Upload laporan Tugas Akhir untuk pengajuan penguji ke Ketua Jurusan
      </p>
    </div>
  </div>

  <!-- Progress -->
  <div class="mb-8 rounded-xl bg-white p-6 shadow-sm">
    <div class="relative flex justify-between">
      <div class="absolute left-[25%] right-[25%] top-5 h-0.5 bg-emerald-400"></div>

      {{-- Step 1: Bimbingan (selesai) --}}
      <div class="relative z-10 flex flex-1 flex-col items-center">
        <div
          class="mb-2 flex h-10 w-10 items-center justify-center rounded-full bg-emerald-500 text-white shadow-[0_0_0_4px_rgba(16,185,129,0.2)]">
          <i class="fas fa-check text-base"></i>
        </div>
        <span class="text-center text-[10px] sm:text-xs font-semibold text-emerald-600">Bimbingan</span>
      </div>

      {{-- Step 2: Minta Penguji (aktif) --}}
      <div class="relative z-10 flex flex-1 flex-col items-center">
        <div
          class="mb-2 flex h-10 w-10 items-center justify-center rounded-full bg-blue-600 text-white shadow-[0_0_0_4px_rgba(37,99,235,0.2)]">
          <i class="fas fa-user-check text-base"></i>
        </div>
        <span class="text-center text-[10px] sm:text-xs font-semibold text-blue-600">Minta Penguji</span>
      </div>
    </div>
  </div>

  <!-- Alert -->
  <div
    class="mb-6 flex items-start gap-3 rounded-xl border border-[#28a745] bg-gradient-to-br from-[#d4edda] to-[#c3e6cb] p-5">
    <i class="fas fa-check-circle mt-0.5 text-2xl text-[#28a745]"></i>
    <div class="flex-1">
      <div class="mb-1 font-semibold text-[#155724]">
        Selamat! Bimbingan Proposal Anda Telah Selesai
      </div>
      <div class="text-sm text-[#155724]">
        Kedua pembimbing telah menyetujui proposal Anda. Silakan upload Laporan TA untuk mengajukan penguji.
      </div>
    </div>
  </div>

  <!-- Flash Messages -->
  <x-alert type="success" />
  <x-alert type="error" />
  <x-alert type="warning" />

  <!-- Card Upload -->
  <div class="mb-8 overflow-hidden rounded-xl bg-white shadow-sm">
    <div class="border-b border-gray-200 px-6 py-5">
      <h3 class="text-lg font-semibold text-gray-900">Upload Laporan TA</h3>
    </div>

    <form action="{{ route('mahasiswa.bimbingan.createKajurSubmission', ['jenis' => $jenis]) }}" method="POST"
      enctype="multipart/form-data" class="space-y-5 p-6">
      @csrf

      <!-- Dikirim ke -->
      <div>
        <label class="mb-2 block text-sm font-medium text-gray-700">Dikirim ke</label>
        <div class="flex items-center gap-4 rounded-lg border border-gray-200 bg-gray-50 p-4">
          <div
            class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-500 text-xl font-semibold text-black">
            KJ
          </div>
          <div>
            <div class="font-semibold text-slate-800">Ketua Jurusan Informatika</div>
            <div class="text-sm text-slate-500">{{ $kajur->profileDosen->nama_lengkap }}</div>
          </div>
        </div>
      </div>

      <div x-data="fileUpload()">
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
        <input type="file" name="files[]" x-ref="fileInput" @change="handleFiles($event.target.files)" class="hidden"
          accept=".pdf,.doc,.docx" multiple />

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
        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan untuk Ketua Jurusan</label>
        <textarea name="catatan"
          class="w-full px-3 py-3 border border-gray-300 rounded-lg text-[0.95rem] resize-y min-h-[100px] transition-colors focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-100"
          placeholder="Tambahkan catatan atau keterangan mengenai laporan yang diupload..."></textarea>
      </div>

      <!-- Action Buttons -->
      <div class="flex justify-between gap-3 pt-4 border-t border-gray-200 mt-6">
        <a href="{{ route('mahasiswa.bimbingan.index', ['jenis' => $jenis]) }}"
          class="inline-flex items-center gap-2 px-4 py-2 md:px-6 md:py-3 rounded-lg text-sm font-medium transition-all border border-gray-300 bg-white text-gray-700 hover:bg-gray-100">
          <i class="fas fa-arrow-left"></i>
          Kembali
        </a>
        <button type="submit"
          class="inline-flex items-center gap-2 px-4 py-2 md:px-6 md:py-3 rounded-lg text-sm font-medium cursor-pointer transition-all border-0 bg-blue-600 text-white hover:bg-blue-800 disabled:bg-blue-300 disabled:cursor-not-allowed">
          <i class="fas fa-paper-plane"></i>
          Upload & Kirim
        </button>
      </div>
    </form>
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
