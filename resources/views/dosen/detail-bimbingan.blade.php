@extends('layouts.app')

@section('title', 'Detail Bimbingan')

@section('sidebar')
  @include('dosen.sidebar')
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

  <div class="{{ $isPending ? 'grid grid-cols-1 lg:grid-cols-3 gap-6' : 'grid grid-cols-1 gap-6 max-w-5xl' }}">
    <!-- Left Column - Student Info & Files -->
    <div class="{{ $isPending ? 'lg:col-span-2' : 'w-full' }} space-y-6">
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
              <x-file-preview-item :path="$file->file_path" :uploaded-at="$file->created_at" />
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
    </div>

    <!-- Right Column - Review Form -->
    @if ($isPending)
      <div class="lg:col-span-1">
        <div class="bg-white rounded-xl shadow-sm overflow-hidden sticky top-6">
          <div class="border-b border-gray-200 px-6 py-4">
            <h3 class="font-semibold text-gray-900">Form Review</h3>
          </div>
          <div class="px-6">
            <form action="{{ route('dosen.bimbingan.review', ['submission' => $submission->id]) }}" method="POST"
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
              <div x-data="fileUpload()">
                <label class="block text-sm font-medium text-gray-700 mb-2">Upload File (Opsional)</label>

                <div @click="$refs.fileInput.click()" @dragover.prevent="dragging = true"
                  @dragleave.prevent="dragging = false" @drop.prevent="handleDrop($event)"
                  :class="dragging ? 'border-blue-600 bg-blue-50' : 'border-gray-300'"
                  class="border-2 border-dashed rounded-xl p-8 text-center transition-all cursor-pointer mb-4 hover:border-blue-600 hover:bg-blue-50">
                  <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-3"></i>
                  <p class="text-gray-500 mb-2">
                    Drag and drop file Anda di sini, atau <span class="text-blue-600 font-medium">browse files</span>
                  </p>
                  <p class="text-xs text-gray-400">
                    Format yang didukung: PDF, DOC, DOCX (Maks 10MB)
                  </p>
                </div>

                <input type="file" name="files[]" x-ref="fileInput" @change="handleFiles($event.target.files)"
                  class="hidden" accept=".pdf,.doc,.docx" multiple />

                <div x-show="files.length > 0">
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

              <!-- Status Selection -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status Review</label>
                <div class="grid grid-cols-1 gap-2">
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
                      <span
                        class="w-6 h-6 rounded-full bg-red-100 text-red-600 flex items-center justify-center text-xs">
                        <i class="fas fa-times"></i>
                      </span>
                      <span class="text-sm font-medium text-gray-900">Ditolak</span>
                    </span>
                  </label>
                </div>
              </div>

              <!-- Submit Button -->
              <div class="pt-2">
                <button type="submit"
                  class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-blue-700 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2">
                  <i class="fas fa-paper-plane mr-2"></i>Kirim Review
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    @endif

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
            if (file.size > maxSize) {
              alert(`File ${file.name} terlalu besar. Maksimal 10MB`);
              return;
            }

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
