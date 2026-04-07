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
            <x-file-preview-item :path="$file->file_path" type="kajur-submission-file" :file-id="$file->id" :uploaded-at="$file->created_at" />
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

    <div>
      <x-file-upload name="files[]" accept=".pdf,.doc,.docx" :multiple="true" :required="true" label="Laporan"
        :max-mb="10"
        hint="Format yang didukung: PDF, DOC, DOCX (Maks 10MB) - Upload satu atau beberapa BAB sekaligus"
        class="mb-6" />

      @error('files')
        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
      @enderror
    </div>

    {{-- Abstrak --}}
    <div>
      <label class="block text-sm font-medium text-gray-700 mb-2">
        Abstrak <span class="text-red-500">*</span>
      </label>
      <textarea name="abstrak" required rows="5"
        class="w-full px-3 py-3 border border-gray-300 rounded-lg text-[0.95rem] resize-y transition-colors focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-100 @error('abstrak') border-red-400 @enderror"
        placeholder="Tuliskan abstrak dari tugas akhir Anda...">{{ old('abstrak', $tugasAkhir->abstrak ?? '') }}</textarea>
      @error('abstrak')
        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
      @enderror
    </div>

    {{-- Kata Kunci --}}
    @php
      $existingKeywords = old('kata_kunci')
          ? array_values(array_filter(array_map('trim', explode(',', old('kata_kunci')))))
          : array_values(array_filter(array_map('trim', explode(',', $tugasAkhir->kata_kunci ?? ''))));
    @endphp
    <div x-data="kataKunciInput(@js($existingKeywords))">
      <label class="block text-sm font-medium text-gray-700 mb-1.5">
        Kata Kunci <span class="text-red-500">*</span>
        <span class="text-xs font-normal text-gray-400 ml-1">(minimal 5 kata kunci)</span>
      </label>

      {{-- Tags container --}}
      <div
        class="rounded-xl border border-slate-200 bg-white shadow-sm focus-within:ring-4 focus-within:ring-blue-100 focus-within:border-blue-400">
        <div class="flex flex-wrap items-center gap-2 p-2" @click="$refs.kataKunciInput.focus()">

          {{-- Render chip untuk setiap tag --}}
          <template x-for="(tag, index) in tags" :key="index">
            <span
              class="inline-flex items-center gap-2 rounded-lg bg-slate-100 border border-slate-200 px-3 py-1.5 text-sm text-slate-700">
              <span x-text="tag"></span>
              <button type="button" @click.stop="removeTag(index)"
                class="text-slate-500 hover:text-slate-700 bg-transparent border-none cursor-pointer p-0">
                <i class="fas fa-times text-xs"></i>
              </button>
            </span>
          </template>

          {{-- Input teks untuk menambah tag --}}
          <input type="text" x-ref="kataKunciInput" x-model="currentTag" @keydown.enter.prevent="addTag()"
            @keydown.comma.prevent="addTag()" @keydown.backspace="currentTag === '' && removeTag(tags.length - 1)"
            placeholder="Ketik lalu tekan Enter atau koma..."
            class="flex-1 min-w-[180px] border-0 bg-transparent px-2 py-1.5 text-sm text-slate-900 placeholder:text-slate-400 focus:ring-0 focus:outline-none" />
        </div>
      </div>

      {{-- Nilai dikirim ke server sebagai string comma-separated --}}
      <input type="hidden" name="kata_kunci" :value="tags.join(', ')" />

      @error('kata_kunci')
        <p class="mt-1.5 text-xs text-red-500">{{ $message }}</p>
      @enderror
    </div>

    <!-- Notes/Description -->
    <div>
      <label class="block text-sm font-medium text-gray-700 mb-2">Catatan (opsional)</label>
      <textarea name="catatan"
        class="w-full px-3 py-3 border border-gray-300 rounded-lg text-[0.95rem] resize-y min-h-[100px] transition-colors focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-100"
        placeholder="Tambahkan catatan atau keterangan mengenai laporan yang diupload...">{{ old('catatan') }}</textarea>
    </div>

    <!-- Action Buttons -->
    <div class="flex justify-between gap-3 pt-4 border-t border-gray-200 mt-6">
      <a href="{{ route('mahasiswa.bimbingan.bimbingan', ['jenis' => $jenis]) }}"
        class="inline-flex items-center gap-2 px-4 py-2 md:px-6 md:py-3 rounded-lg text-sm font-medium transition-all border border-gray-300 bg-white text-gray-700 hover:bg-gray-100">
        <i class="fas fa-arrow-left"></i>
        Kembali
      </a>
      <button type="submit"
        class="inline-flex items-center gap-2 px-4 py-2 md:px-6 md:py-3 rounded-lg text-sm font-medium cursor-pointer transition-all border-0 bg-blue-600 text-white hover:bg-blue-800">
        <i class="fas fa-paper-plane"></i>
        {{ isset($kajurSubmission) && in_array($kajurSubmission->status, ['revisi', 'reject']) ? 'Upload & Kirim Ulang' : 'Upload & Kirim' }}
      </button>
    </div>
  </form>
</div>

<script>
  function kataKunciInput(initialTags) {
    return {
      tags: initialTags ?? [],
      currentTag: '',

      addTag() {
        const tag = this.currentTag.replace(/,/g, '').trim();
        if (tag && !this.tags.includes(tag)) {
          this.tags.push(tag);
        }
        this.currentTag = '';
      },

      removeTag(index) {
        if (index >= 0 && index < this.tags.length) {
          this.tags.splice(index, 1);
        }
      },
    }
  }
</script>
