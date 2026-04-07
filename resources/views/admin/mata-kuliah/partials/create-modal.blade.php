@php
  $showCreateErrors = $errors->any() && old('form_context') === 'create';
@endphp

<x-modal name="create-mata-kuliah" :show="$showCreateErrors" maxWidth="lg" focusable>
  <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between gap-3">
    <div>
      <h2 class="text-lg font-semibold text-gray-900">Tambah Mata Kuliah</h2>
      <p class="text-sm text-gray-500">Masukkan data mata kuliah baru.</p>
    </div>
    <button type="button" x-on:click="$dispatch('close-modal', 'create-mata-kuliah')"
      class="w-9 h-9 rounded-full bg-gray-100 text-gray-500 hover:bg-gray-200 transition-colors">
      <i class="fas fa-times text-sm"></i>
    </button>
  </div>

  <form method="POST" action="{{ route('admin.mata-kuliah.store') }}">
    @csrf
    <input type="hidden" name="form_context" value="create">

    <div class="px-6 py-5 space-y-4">
      @if ($showCreateErrors)
        <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3">
          <div class="flex items-center gap-2 text-red-700 font-semibold text-sm mb-2">
            <i class="fas fa-circle-exclamation"></i> Terdapat kesalahan input
          </div>
          <ul class="list-disc list-inside space-y-1">
            @foreach ($errors->all() as $error)
              <li class="text-sm text-red-600">{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <div>
        <label class="block text-xs font-medium text-gray-600 mb-1.5">
          Kode Mata Kuliah <span class="text-red-500">*</span>
        </label>
        <input type="text" name="kode" value="{{ old('kode') }}" placeholder="Contoh: IF201" required
          class="w-full px-4 py-2.5 border rounded-lg text-sm text-gray-800 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 transition-all @error('kode') border-red-400 bg-red-50 @else border-gray-300 @enderror" />
        @error('kode')
          <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
        @enderror
      </div>

      <div>
        <label class="block text-xs font-medium text-gray-600 mb-1.5">
          Nama Mata Kuliah <span class="text-red-500">*</span>
        </label>
        <input type="text" name="nama" value="{{ old('nama') }}" placeholder="Contoh: Struktur Data" required
          class="w-full px-4 py-2.5 border rounded-lg text-sm text-gray-800 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 transition-all @error('nama') border-red-400 bg-red-50 @else border-gray-300 @enderror" />
        @error('nama')
          <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
        @enderror
      </div>
    </div>

    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-3">
      <button type="button" x-on:click="$dispatch('close-modal', 'create-mata-kuliah')"
        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-medium border border-gray-300 text-gray-600 hover:bg-gray-100 transition-all">
        Batal
      </button>
      <button type="submit"
        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-semibold bg-blue-600 text-white hover:bg-blue-700 transition-all shadow-sm">
        <i class="fas fa-floppy-disk text-xs"></i> Simpan Mata Kuliah
      </button>
    </div>
  </form>
</x-modal>
