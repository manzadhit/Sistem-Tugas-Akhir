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

  @if (!isset($kajurSubmission))
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
  @endif

  <!-- Flash Messages -->
  <x-alert type="success" />
  <x-alert type="error" />
  <x-alert type="warning" />


  @if (isset($kajurSubmission) && $kajurSubmission->status === 'pending')
    @include('mahasiswa.minta-penguji.menunggu-verifikasi')
  @elseif (isset($kajurSubmission) && $kajurSubmission->status === 'acc' && $dosenPenguji->isNotEmpty())
    @include('mahasiswa.minta-penguji.penguji-ditetapkan')
  @else
    @include('mahasiswa.minta-penguji.upload-laporan')
  @endif

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
