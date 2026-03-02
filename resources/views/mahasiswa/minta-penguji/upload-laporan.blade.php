{{-- Tampilan form upload laporan (belum ada KajurSubmission, atau revisi/reject) --}}

@if (isset($kajurSubmission) && in_array($kajurSubmission->status, ['revisi', 'reject']))
  @php
    $isRevisi = $kajurSubmission->status === 'revisi';
    $kajurFiles = $kajurSubmission->kajurSubmissionFiles->where('uploaded_by', 'kajur');
  @endphp
  <div
    class="mb-6 overflow-hidden rounded-xl border {{ $isRevisi ? 'border-amber-200 bg-amber-50' : 'border-red-200 bg-red-50' }}">
    <div
      class="flex items-center gap-3 border-b px-6 py-4 {{ $isRevisi ? 'border-amber-200 bg-amber-100' : 'border-red-200 bg-red-100' }}">
      <i
        class="fas {{ $isRevisi ? 'fa-exclamation-triangle text-amber-600' : 'fa-times-circle text-red-600' }} text-lg"></i>
      <h3 class="font-semibold {{ $isRevisi ? 'text-amber-800' : 'text-red-800' }}">
        {{ $isRevisi ? 'Perlu Revisi dari Ketua Jurusan' : 'Pengajuan Ditolak oleh Ketua Jurusan' }}
      </h3>
    </div>
    <div class="p-6">
      @if ($kajurSubmission->review)
        <p class="mb-4 text-sm {{ $isRevisi ? 'text-amber-900' : 'text-red-900' }}">{{ $kajurSubmission->review }}</p>
      @endif

      @if ($kajurFiles->isNotEmpty())
        <div class="space-y-2">
          <p class="text-xs font-semibold uppercase tracking-wide {{ $isRevisi ? 'text-amber-700' : 'text-red-700' }}">
            File dari Kajur:</p>
          @foreach ($kajurFiles as $file)
            <x-file-preview-item :path="$file->file_path" :uploaded-at="$file->created_at" />
          @endforeach
        </div>
      @endif
    </div>
  </div>
@endif

<div class="mb-8 overflow-hidden rounded-xl bg-white shadow-sm">
  <div class="border-b border-gray-200 px-6 py-5">
    <h3 class="text-lg font-semibold text-gray-900">
      {{ isset($kajurSubmission) && in_array($kajurSubmission->status, ['revisi', 'reject']) ? 'Upload Ulang Laporan TA' : 'Upload Laporan TA' }}
    </h3>
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
      <a href="{{ route('mahasiswa.bimbingan.bimbingan', ['jenis' => $jenis]) }}"
        class="inline-flex items-center gap-2 px-4 py-2 md:px-6 md:py-3 rounded-lg text-sm font-medium transition-all border border-gray-300 bg-white text-gray-700 hover:bg-gray-100">
        <i class="fas fa-arrow-left"></i>
        Kembali
      </a>
      <button type="submit"
        class="inline-flex items-center gap-2 px-4 py-2 md:px-6 md:py-3 rounded-lg text-sm font-medium cursor-pointer transition-all border-0 bg-blue-600 text-white hover:bg-blue-800 disabled:bg-blue-300 disabled:cursor-not-allowed">
        <i class="fas fa-paper-plane"></i>
        {{ isset($kajurSubmission) && in_array($kajurSubmission->status, ['revisi', 'reject']) ? 'Upload & Kirim Ulang' : 'Upload & Kirim' }}
      </button>
    </div>
  </form>
</div>
