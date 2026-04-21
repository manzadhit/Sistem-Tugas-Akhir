@php
  $showImportErrors = $errors->any() && old('form_context') === 'import';
@endphp

<x-modal name="import-publikasi" :show="$showImportErrors" maxWidth="lg" focusable>
  <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between gap-3">
    <div>
      <h2 class="text-lg font-semibold text-gray-900">Import Data Publikasi</h2>
      <p class="text-sm text-gray-500">Unggah file CSV, XLS, atau XLSX sesuai template publikasi dosen.</p>
    </div>
    <button type="button" x-on:click="$dispatch('close-modal', 'import-publikasi')"
      class="w-9 h-9 rounded-full bg-gray-100 text-gray-500 hover:bg-gray-200 transition-colors">
      <i class="fas fa-times text-sm"></i>
    </button>
  </div>

  <form method="POST" action="{{ route('admin.publikasi.import') }}" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="form_context" value="import">

    <div class="px-6 py-5 space-y-4">
      @if ($showImportErrors)
        <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3">
          <div class="flex items-center gap-2 text-red-700 font-semibold text-sm mb-2">
            <i class="fas fa-circle-exclamation"></i> Terdapat kesalahan saat import
          </div>
          <ul class="list-disc list-inside space-y-1">
            @foreach ($errors->all() as $error)
              <li class="text-sm text-red-600">{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <div class="rounded-xl border border-blue-100 bg-blue-50 px-4 py-3 text-sm text-blue-800">
        Gunakan file dengan kolom header:
        <strong>tahun</strong>, <strong>title</strong>, <strong>jenis_publikasi</strong>, <strong>url</strong>,
        <strong>abstrak</strong>.
        Nilai <strong>jenis_publikasi</strong> di file harus berisi <strong>jurnal</strong>, <strong>buku</strong>,
        atau <strong>haki</strong>.
      </div>

      <div>
        <label class="block text-xs font-medium text-gray-600 mb-1.5">
          Dosen Penulis <span class="text-red-500">*</span>
        </label>
        <select name="dosen_id" required
          class="w-full pl-3 pr-8 py-3 border rounded-lg text-sm text-gray-800 bg-white focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all @error('dosen_id') border-red-400 bg-red-50 @else border-gray-300 @enderror">
          <option value="">-- Pilih Dosen --</option>
          @foreach ($daftarDosen as $dosen)
            <option value="{{ $dosen->id }}" {{ old('dosen_id') == $dosen->id ? 'selected' : '' }}>
              {{ $dosen->nama_lengkap }}
            </option>
          @endforeach
        </select>
        @error('dosen_id')
          <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-xs font-medium text-gray-600 mb-1.5">
          File Import <span class="text-red-500">*</span>
        </label>
        <input type="file" name="file" accept=".csv,.xls,.xlsx" required
          class="w-full px-4 py-3 border rounded-lg text-sm text-gray-800 file:mr-4 file:px-4 file:py-2 file:rounded-md file:border-0 file:bg-emerald-50 file:text-emerald-700 file:font-medium hover:file:bg-emerald-100 focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 transition-all @error('file') border-red-400 bg-red-50 @else border-gray-300 @enderror" />
        <p class="mt-2 text-xs text-gray-500">Format yang didukung: CSV, XLS, XLSX. Ukuran maksimal 2 MB.</p>
        @error('file')
          <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
        @enderror
      </div>
    </div>

    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-3">
      <button type="button" x-on:click="$dispatch('close-modal', 'import-publikasi')"
        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-medium border border-gray-300 text-gray-600 hover:bg-gray-100 transition-all">
        Batal
      </button>
      <button type="submit"
        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-semibold bg-emerald-600 text-white hover:bg-emerald-700 transition-all shadow-sm">
        <i class="fas fa-file-import text-xs"></i> Import Sekarang
      </button>
    </div>
  </form>
</x-modal>
