@php
  $showImportExistingTaErrors = $errors->any() && old('form_context') === 'import_existing_ta';
@endphp

<x-modal name="import-existing-ta" :show="$showImportExistingTaErrors" maxWidth="lg" focusable>
  <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between gap-3">
    <div>
      <h2 class="text-lg font-semibold text-gray-900">Import Existing TA</h2>
      <p class="text-sm text-gray-500">Unggah file CSV, XLS, atau XLSX untuk migrasi snapshot tugas akhir mahasiswa.</p>
    </div>
    <button type="button" x-on:click="$dispatch('close-modal', 'import-existing-ta')"
      class="w-9 h-9 rounded-full bg-gray-100 text-gray-500 hover:bg-gray-200 transition-colors">
      <i class="fas fa-times text-sm"></i>
    </button>
  </div>

  <form method="POST" action="{{ route('admin.mahasiswa.import-existing-ta') }}" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="form_context" value="import_existing_ta">

    <div class="px-6 py-5 space-y-4">
      @if ($showImportExistingTaErrors)
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

      <div class="rounded-xl border border-violet-100 bg-violet-50 px-4 py-3 text-sm text-violet-800">
        Gunakan file dengan kolom header:
        <strong>nim</strong>, <strong>nama lengkap</strong>, <strong>judul ta</strong>, <strong>tahap</strong>,
        <strong>pembimbing 1 nidn</strong>, <strong>pembimbing 2 nidn</strong>, <strong>penguji 1 nidn</strong>,
        <strong>penguji 2 nidn</strong>, <strong>penguji 3 nidn</strong>, <strong>proposal periode aktif</strong>.
        <br>
        <span class="text-xs text-violet-700">Tahap yang didukung: proposal, hasil, skripsi, lulus. Data dosen harus sudah diimport lebih dulu.</span>
      </div>

      <div>
        <label class="block text-xs font-medium text-gray-600 mb-1.5">
          File Import <span class="text-red-500">*</span>
        </label>
        <input type="file" name="file" accept=".csv,.xls,.xlsx" required
          class="w-full px-4 py-3 border rounded-lg text-sm text-gray-800 file:mr-4 file:px-4 file:py-2 file:rounded-md file:border-0 file:bg-violet-50 file:text-violet-700 file:font-medium hover:file:bg-violet-100 focus:outline-none focus:border-violet-500 focus:ring-2 focus:ring-violet-500/10 transition-all @error('file') border-red-400 bg-red-50 @else border-gray-300 @enderror" />
        <p class="mt-2 text-xs text-gray-500">Format yang didukung: CSV, XLS, XLSX. Ukuran maksimal 2 MB.</p>
        @error('file')
          <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
        @enderror
      </div>
    </div>

    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-3">
      <button type="button" x-on:click="$dispatch('close-modal', 'import-existing-ta')"
        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-medium border border-gray-300 text-gray-600 hover:bg-gray-100 transition-all">
        Batal
      </button>
      <button type="submit"
        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-semibold bg-violet-600 text-white hover:bg-violet-700 transition-all shadow-sm">
        <i class="fas fa-database text-xs"></i> Import Existing TA
      </button>
    </div>
  </form>
</x-modal>
