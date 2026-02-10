@extends('layouts.app')

@section('title', 'Bimbingan')

@section('sidebar')
  @include('mahasiswa.sidebar')
@endsection

@section('content')
  <!-- Page Banner -->
  <div class="relative h-40 rounded-xl overflow-hidden mb-8 bg-gradient-to-br from-blue-600 to-blue-800">
    <div class="absolute inset-0 flex items-center justify-center flex-col text-white text-center p-4">
      <h1 class="text-3xl font-bold mb-2">Bimbingan Proposal</h1>
      <p class="text-base opacity-90">
        Upload dan kelola laporan bimbingan proposal Anda
      </p>
    </div>
  </div>

  <!-- Progress Bar -->
  <div class="bg-white rounded-xl p-6 shadow-sm mb-8">
    <div class="flex justify-between relative">
      <!-- Progress Line -->
      <div class="absolute top-5 left-[10%] right-[10%] h-0.5 bg-gray-200 z-0"></div>

      <!-- Step 1 - Active -->
      <div class="flex flex-col items-center relative z-10 flex-1">
        <div
          class="w-10 h-10 rounded-full flex items-center justify-center text-base mb-2 bg-blue-600 text-white shadow-[0_0_0_4px_rgba(37,99,235,0.2)] transition-all duration-300">
          <i class="fas fa-comments"></i>
        </div>
        <span class="text-xs font-semibold text-blue-600 text-center">Bimbingan</span>
      </div>

      <!-- Step 2 - Pending -->
      <div class="flex flex-col items-center relative z-10 flex-1">
        <div
          class="w-10 h-10 rounded-full flex items-center justify-center text-base mb-2 bg-gray-200 text-gray-400 transition-all duration-300">
          <i class="fas fa-check-double"></i>
        </div>
        <span class="text-xs font-medium text-gray-500 text-center">ACC Pembimbing</span>
      </div>

      <!-- Step 3 - Pending -->
      <div class="flex flex-col items-center relative z-10 flex-1">
        <div
          class="w-10 h-10 rounded-full flex items-center justify-center text-base mb-2 bg-gray-200 text-gray-400 transition-all duration-300">
          <i class="fas fa-user-check"></i>
        </div>
        <span class="text-xs font-medium text-gray-500 text-center">Minta Penguji</span>
      </div>
    </div>
  </div>

  <!-- Upload Form Card -->
  <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-8">
    <div class="px-6 py-5 border-b border-gray-200 flex justify-between items-center">
      <h3 class="text-lg font-semibold text-gray-900">Upload Laporan Bimbingan</h3>
    </div>
    <div class="p-6">
      <!-- Select Pembimbing -->
      <div class="mb-5">
        <label class="block text-sm font-medium text-gray-700 mb-2">
          Kirim ke Pembimbing <span class="text-red-600">*</span>
        </label>
        <select
          class="w-full px-3 py-3 border border-gray-300 rounded-lg text-[0.95rem] bg-white cursor-pointer transition-colors focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-100 disabled:bg-gray-100 disabled:cursor-not-allowed disabled:text-gray-400"
          id="selectPembimbing">
          <option value="">-- Pilih Pembimbing --</option>
          @foreach ($pembimbing as $index => $p)
            <option value="{{ $p->dosen->nama_lengkap }}">
              {{ 'Pembimbing ' . $index + 1 . ' - ' . $p->dosen->nama_lengkap }}
            </option>
          @endforeach

          <option value="pembimbing2" disabled>
            Pembimbing 2 - Dr. Siti Rahayu, M.T. (Menunggu Review)
          </option>
        </select>
        <p class="text-xs text-amber-600 mt-1.5">
          <i class="fas fa-info-circle"></i> Pembimbing 2 belum bisa
          menerima laporan baru karena masih ada laporan yang menunggu
          review
        </p>
      </div>

      <!-- Alert for Revision -->
      <div class="px-4 py-4 rounded-lg mb-6 flex items-start gap-3 bg-amber-50 border border-amber-200 text-amber-800">
        <i class="fas fa-exclamation-triangle text-lg mt-0.5"></i>
        <div class="flex-1">
          <div class="font-semibold mb-1">
            Revisi Diperlukan dari Pembimbing 1
          </div>
          <div class="text-sm">
            Silakan perbaiki laporan sesuai catatan pembimbing dan upload
            kembali.
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
        <input type="file" x-ref="fileInput" @change="handleFiles($event.target.files)" class="hidden"
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
        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan untuk Pembimbing</label>
        <textarea
          class="w-full px-3 py-3 border border-gray-300 rounded-lg text-[0.95rem] resize-y min-h-[100px] transition-colors focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-100"
          placeholder="Tambahkan catatan atau keterangan mengenai laporan yang diupload..."></textarea>
      </div>

      <!-- Action Buttons -->
      <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 mt-6">
        <button
          class="inline-flex items-center gap-2 px-4 py-2 md:px-6 md:py-3 rounded-lg text-sm font-medium cursor-pointer transition-all border-0 bg-blue-600 text-white hover:bg-blue-800 disabled:bg-blue-300 disabled:cursor-not-allowed">
          <i class="fas fa-paper-plane"></i>
          Upload & Kirim
        </button>
      </div>
    </div>
  </div>

  <!-- History Section -->
  <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-8">
    <div class="p-4 sm:p-6">
      <div class="bg-gray-50 rounded-xl p-4 sm:p-6">
        <h3 class="text-base font-semibold text-gray-900 mb-4 flex items-center gap-2">
          <i class="fas fa-history text-blue-600"></i>
          Riwayat Pengajuan
        </h3>

        <!-- History Item 0 - Menunggu Review -->
        <div class="bg-white border border-gray-200 rounded-lg mb-3 overflow-hidden last:mb-0">
          <div class="p-4 cursor-pointer transition-colors hover:bg-gray-50" onclick="toggleHistory(0)">
            <div class="flex flex-col items-start gap-3 lg:flex-row lg:items-center lg:justify-between">
              <div class="w-full lg:flex-1">
                <h4 class="text-[0.95rem] font-medium text-gray-900 mb-2 flex flex-wrap items-center gap-2 sm:gap-3">
                  Laporan Proposal
                  <span
                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-600">Menunggu
                    Review</span>
                </h4>
                <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-[0.75rem] sm:text-[0.8rem] text-gray-500">
                  <span><i class="far fa-clock"></i> 22 Des 2024, 09:00</span>
                  <span><i class="far fa-file"></i> 1 file</span>
                  <span><i class="fas fa-user"></i> Dikirim ke: Pembimbing 2</span>
                </div>
              </div>
              <div class="flex w-full items-center justify-between gap-2 sm:gap-3 lg:w-auto lg:justify-end">
                <span
                  class="inline-flex items-center gap-1.5 px-3 py-1.5 md:px-6 md:py-3 rounded-lg text-xs md:text-md font-medium bg-amber-500 text-white">
                  <i class="fas fa-hourglass-half"></i>
                  Menunggu
                </span>
                <i class="fas fa-chevron-down text-sm sm:text-base text-gray-400 transition-transform"
                  id="chevron-0"></i>
              </div>
            </div>
          </div>
          <div class="hidden border-t border-gray-200 bg-gray-50 p-3 sm:p-4" id="files-0">
            <div class="text-[0.8rem] font-medium text-gray-500 mb-3">Files:</div>
            <div
              class="flex flex-col items-start justify-between gap-3 px-3 py-3 bg-white border border-gray-200 rounded-md mb-2 last:mb-0 sm:flex-row sm:items-center">
              <div class="flex min-w-0 items-center gap-3">
                <i class="fas fa-file-pdf text-xl text-red-600"></i>
                <div class="min-w-0">
                  <div class="truncate text-sm font-medium text-gray-900">
                    Laporan_Proposal_v2.pdf
                  </div>
                  <div class="text-xs text-gray-500">3.5 MB</div>
                </div>
              </div>
              <div class="flex w-full gap-3 sm:w-auto sm:justify-end">
                <a href="#"
                  class="text-xs sm:text-[0.8rem] no-underline flex items-center gap-1 text-blue-600 hover:underline">
                  <i class="fas fa-eye"></i> View
                </a>
                <a href="#"
                  class="text-xs sm:text-[0.8rem] no-underline flex items-center gap-1 text-green-600 hover:underline">
                  <i class="fas fa-download"></i> Download
                </a>
              </div>
            </div>
          </div>
        </div>

        <!-- History Item 1 - Revisi -->
        <div class="bg-white border border-gray-200 rounded-lg mb-3 overflow-hidden last:mb-0">
          <div class="p-4 cursor-pointer transition-colors hover:bg-gray-50" onclick="toggleHistory(1)">
            <div class="flex flex-col items-start gap-3 lg:flex-row lg:items-center lg:justify-between">
              <div class="w-full lg:flex-1">
                <h4 class="text-[0.95rem] font-medium text-gray-900 mb-2 flex flex-wrap items-center gap-2 sm:gap-3">
                  Laporan Proposal
                  <span
                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-800">Revisi</span>
                </h4>
                <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-[0.75rem] sm:text-[0.8rem] text-gray-500">
                  <span><i class="far fa-clock"></i> 20 Des 2024, 14:30</span>
                  <span><i class="far fa-file"></i> 1 file</span>
                  <span><i class="fas fa-user"></i> Dari: Pembimbing 1</span>
                </div>
              </div>
              <div class="flex w-full items-center justify-between gap-2 sm:gap-3 lg:w-auto lg:justify-end">
                <a href="detail-bimbingan.html"
                  class="inline-flex items-center gap-1.5 px-3 py-1.5 md:px-6 md:py-3 rounded-lg text-xs md:text-md font-medium bg-blue-600 text-white hover:bg-blue-800"
                  onclick="event.stopPropagation()">
                  <i class="fas fa-eye"></i>
                  Lihat Review
                </a>
                <i class="fas fa-chevron-down text-sm sm:text-base text-gray-400 transition-transform"
                  id="chevron-1"></i>
              </div>
            </div>
          </div>
          <div class="hidden border-t border-gray-200 bg-gray-50 p-3 sm:p-4" id="files-1">
            <div class="text-[0.8rem] font-medium text-gray-500 mb-3">Files:</div>
            <div
              class="flex flex-col items-start justify-between gap-3 px-3 py-3 bg-white border border-gray-200 rounded-md mb-2 last:mb-0 sm:flex-row sm:items-center">
              <div class="flex min-w-0 items-center gap-3">
                <i class="fas fa-file-pdf text-xl text-red-600"></i>
                <div class="min-w-0">
                  <div class="truncate text-sm font-medium text-gray-900">
                    Laporan_Proposal_v1.pdf
                  </div>
                  <div class="text-xs text-gray-500">3.2 MB</div>
                </div>
              </div>
              <div class="flex w-full gap-3 sm:w-auto sm:justify-end">
                <a href="#"
                  class="text-xs sm:text-[0.8rem] no-underline flex items-center gap-1 text-blue-600 hover:underline">
                  <i class="fas fa-eye"></i> View
                </a>
                <a href="#"
                  class="text-xs sm:text-[0.8rem] no-underline flex items-center gap-1 text-green-600 hover:underline">
                  <i class="fas fa-download"></i> Download
                </a>
              </div>
            </div>
          </div>
        </div>

        <!-- History Item 2 - Lanjutkan -->
        <div class="bg-white border border-gray-200 rounded-lg mb-3 overflow-hidden last:mb-0">
          <div class="p-4 cursor-pointer transition-colors hover:bg-gray-50" onclick="toggleHistory(2)">
            <div class="flex flex-col items-start gap-3 lg:flex-row lg:items-center lg:justify-between">
              <div class="w-full lg:flex-1">
                <h4 class="text-[0.95rem] font-medium text-gray-900 mb-2 flex flex-wrap items-center gap-2 sm:gap-3">
                  Laporan Proposal
                  <span
                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">Lanjutkan</span>
                </h4>
                <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-[0.75rem] sm:text-[0.8rem] text-gray-500">
                  <span><i class="far fa-clock"></i> 15 Des 2024, 10:00</span>
                  <span><i class="far fa-file"></i> 1 file</span>
                  <span><i class="fas fa-user"></i> Dari: Pembimbing 1</span>
                </div>
              </div>
              <div class="flex w-full items-center justify-between gap-2 sm:gap-3 lg:w-auto lg:justify-end">
                <a href="detail-bimbingan.html"
                  class="inline-flex items-center gap-1.5 px-3 py-1.5 md:px-6 md:py-3 rounded-lg text-xs md:text-md font-medium bg-sky-500 text-white hover:bg-sky-700"
                  onclick="event.stopPropagation()">
                  <i class="fas fa-arrow-right"></i>
                  Lanjutkan
                </a>
                <i class="fas fa-chevron-down text-sm sm:text-base text-gray-400 transition-transform"
                  id="chevron-2"></i>
              </div>
            </div>
          </div>
          <div class="hidden border-t border-gray-200 bg-gray-50 p-3 sm:p-4" id="files-2">
            <div class="text-[0.8rem] font-medium text-gray-500 mb-3">Files:</div>
            <div
              class="flex flex-col items-start justify-between gap-3 px-3 py-3 bg-white border border-gray-200 rounded-md mb-2 last:mb-0 sm:flex-row sm:items-center">
              <div class="flex min-w-0 items-center gap-3">
                <i class="fas fa-file-pdf text-xl text-red-600"></i>
                <div class="min-w-0">
                  <div class="truncate text-sm font-medium text-gray-900">
                    Laporan_Proposal_Draft.pdf
                  </div>
                  <div class="text-xs text-gray-500">2.8 MB</div>
                </div>
              </div>
              <div class="flex w-full gap-3 sm:w-auto sm:justify-end">
                <a href="#"
                  class="text-xs sm:text-[0.8rem] no-underline flex items-center gap-1 text-blue-600 hover:underline">
                  <i class="fas fa-eye"></i> View
                </a>
                <a href="#"
                  class="text-xs sm:text-[0.8rem] no-underline flex items-center gap-1 text-green-600 hover:underline">
                  <i class="fas fa-download"></i> Download
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
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

            this.files.push(file);
          });

          // Reset input agar file yang sama bisa diupload lagi setelah dihapus
          this.$refs.fileInput.value = '';
        },

        handleDrop(e) {
          this.dragging = false;
          const files = e.dataTransfer.files;
          this.handleFiles(files);
        },

        removeFile(index) {
          this.files.splice(index, 1);
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
