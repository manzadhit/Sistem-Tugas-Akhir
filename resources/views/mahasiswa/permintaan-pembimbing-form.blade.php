@extends('layouts.app')

@section('title', 'Pengajuan Dosen Pembimbing')

@section('content')
  @if (session('warning'))
    <div class="mb-4 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
      {{ session('warning') }}
    </div>
  @endif

  @if ($permintaanPembimbing?->catatan)
    <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
      <p class="font-semibold">Pengajuan sebelumnya ditolak</p>
      <p class="mt-1">{{ $permintaanPembimbing->catatan }}</p>
    </div>
  @endif

  <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-3 mb-5">
    <div>
      <h1 class="text-xl sm:text-2xl font-extrabold tracking-tight text-slate-900">
        Pengajuan Dosen Pembimbing
      </h1>
      <p class="text-slate-500 mt-1 text-xs sm:text-sm">Isi judul & unggah bukti ACC (maks. 2MB)</p>
    </div>

    <span
      class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-bold
                 bg-blue-50 text-blue-700 border border-blue-100 w-fit">
      <i class="fas fa-sparkles"></i> Form Pengajuan
    </span>
  </div>

  <section class="rounded-2xl border border-slate-200 bg-white/80 backdrop-blur shadow-sm">
    <div class="p-6 border-b border-slate-200 flex items-start justify-between gap-4">
      <div>
        <h3 class="font-extrabold text-slate-900">📝 Form Pengajuan</h3>
        <p class="text-sm text-slate-500 mt-1">Pastikan data sesuai dengan yang sudah disetujui.</p>
      </div>

    </div>

    <div class="p-6">
      <x-alert-info>
        <strong>Penting:</strong> Judul harus sudah ACC. File: PDF/JPG/PNG (maks. 2MB).
      </x-alert-info>

      <form method="POST" action="{{ route('mahasiswa.permintaan-pembimbing.store') }}" enctype="multipart/form-data"
        class="mt-5" x-data="{ judul_ta: @js(old('judul_ta', $permintaanPembimbing?->judul_ta ?? '')) }">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
          <div>
            <label class="flex items-center gap-2 font-bold text-slate-900 mb-2">
              <i class="fas fa-book"></i>
              Judul Tugas Akhir <span class="text-red-500">*</span>
            </label>

            <textarea name="judul_ta"
              class="w-full min-h-[128px] rounded-xl border border-slate-200 bg-white px-4 py-3
                     text-sm focus:outline-none focus:ring-4 focus:ring-blue-100 focus:border-blue-400"
              maxlength="500" placeholder="Contoh: Sistem Rekomendasi Pemilihan Dosen Pembimbing Berbasis Machine Learning"
              x-model="judul_ta">{{ old('judul_ta', $permintaanPembimbing?->judul_ta) }}</textarea>

            <div class="mt-2 text-right text-xs text-slate-500">
              <span x-text="judul_ta.length"></span>/500 karakter
            </div>

            @error('judul_ta')
              <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>

          <div>
            <label class="flex items-center gap-2 font-bold text-slate-900 mb-2">
              <i class="fas fa-file-arrow-up"></i>
              Bukti ACC <span class="text-red-500">*</span>
            </label>

            <x-file-upload name="bukti_acc" accept=".pdf,.jpg,.jpeg,.png" max-mb="2" />

            @error('bukti_acc')
              <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
          </div>
        </div>

        <div class="mt-5">
          {{-- Section: Label field mata kuliah --}}
          <label class="flex items-center gap-2 font-bold text-slate-900 mb-2">
            <i class="fas fa-list-check"></i>
            Mata Kuliah Relevan
          </label>

          {{-- Section: Root komponen Alpine (state options + selected) --}}
          <div class="w-full" x-data="mataKuliahMultiSelect({
            options: @js($mataKuliahOptions),
            selected: @js(old('mata_kuliah_ids', [])),
          })" @click.outside="open = false">
            {{-- Section: Hidden input untuk kirim array `mata_kuliah_ids[]` ke backend --}}
            <template x-for="id in selected" :key="`selected-${id}`">
              <input type="hidden" name="mata_kuliah_ids[]" :value="id">
            </template>

            {{-- Section: Wrapper field custom (chip + input search + trigger dropdown) --}}
            <div class="rounded-xl border border-slate-200 bg-white shadow-sm focus-within:ring-4 focus-within:ring-blue-100 focus-within:border-blue-400"
              :class="open ? 'border-blue-400' : ''">
              <div class="flex flex-wrap items-center gap-2 p-2" @click="focusSearch()">
                {{-- Section: Chip item yang sudah dipilih --}}
                <template x-for="item in selectedItems" :key="`chip-${item.id}`">
                  <span class="inline-flex items-center gap-2 rounded-lg bg-slate-100 border border-slate-200 px-3 py-1.5 text-sm text-slate-700">
                    <span x-text="item.label"></span>
                    <button type="button" class="text-slate-500 hover:text-slate-700" @click.stop="remove(item.id)">
                      <i class="fas fa-times"></i>
                    </button>
                  </span>
                </template>

                {{-- Section: Input pencarian; Enter memilih item teratas hasil filter --}}
                <input type="text" x-ref="searchInput" x-model="query" @focus="open = true"
                  @input="open = true" @keydown.escape.prevent="open = false"
                  @keydown.enter.prevent="selectFirstFiltered()"
                  class="flex-1 min-w-[180px] border-0 bg-transparent px-2 py-1.5 text-sm text-slate-900 placeholder:text-slate-400 focus:ring-0 focus:outline-none"
                  placeholder="Cari mata kuliah..." />

                {{-- Section: Tombol toggle dropdown --}}
                <button type="button" class="px-2 text-slate-400 hover:text-slate-700" @click.stop="toggleDropdown()">
                  <i class="fas fa-chevron-down text-xs"></i>
                </button>
              </div>

              {{-- Section: Panel dropdown hasil pencarian --}}
              <div x-show="showDropdown" x-transition.origin.top.left class="border-t border-slate-200 max-h-64 overflow-auto">
                {{-- Section: State saat tidak ada hasil filter --}}
                <template x-if="filteredOptions.length === 0">
                  <div class="px-4 py-3 text-sm text-slate-500">Mata kuliah tidak ditemukan.</div>
                </template>

                {{-- Section: Daftar opsi hasil filter; klik untuk select/unselect --}}
                <template x-for="option in filteredOptions" :key="`option-${option.id}`">
                  <button type="button" class="w-full flex items-center justify-between px-4 py-3 text-left text-sm hover:bg-slate-50"
                    @click.stop="toggle(option.id)">
                    <span x-text="option.label"></span>
                    <i class="fas fa-check text-blue-600" x-show="isSelected(option.id)"></i>
                  </button>
                </template>
              </div>
            </div>
          </div>

          {{-- Section: Bantuan penggunaan dan error validasi --}}
          <p class="mt-2 text-xs text-slate-500">Ketik untuk mencari, klik item untuk menambah atau menghapus pilihan.</p>

          @error('mata_kuliah_ids')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
          @enderror
          @error('mata_kuliah_ids.*')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <div class="mt-6 flex justify-end">
          <x-primary-button class="inline-flex justify-center items-center gap-2">
            <span>Kirim</span>
            <i class="fas fa-paper-plane"></i>
          </x-primary-button>
        </div>
      </form>
    </div>
  </section>

  <script>
    function mataKuliahMultiSelect(config) {
      return {
        open: false,
        query: '',
        options: config.options ?? [],
        selected: (config.selected ?? []).map(String),
        get filteredOptions() {
          const q = this.query.toLowerCase().trim()
          if (!q) return this.options
          return this.options.filter((option) => option.label.toLowerCase().includes(q))
        },
        get showDropdown() {
          return this.open && this.query.trim().length > 0
        },
        get selectedItems() {
          return this.options.filter((option) => this.selected.includes(option.id))
        },
        isSelected(id) {
          return this.selected.includes(String(id))
        },
        toggle(id, shouldClose = true) {
          const key = String(id)
          if (this.isSelected(key)) {
            this.selected = this.selected.filter((selectedId) => selectedId !== key)
            if (shouldClose) {
              this.open = false
              this.query = ''
            }
            return
          }
          this.selected = [...this.selected, key]
          if (shouldClose) {
            this.open = false
            this.query = ''
          }
        },
        remove(id) {
          const key = String(id)
          this.selected = this.selected.filter((selectedId) => selectedId !== key)
        },
        selectFirstFiltered() {
          if (!this.filteredOptions.length) return
          this.toggle(this.filteredOptions[0].id, false)
          this.query = ''
          this.open = true
          this.$nextTick(() => this.$refs.searchInput?.focus())
        },
        focusSearch() {
          this.open = true
          this.$nextTick(() => this.$refs.searchInput?.focus())
        },
        toggleDropdown() {
          this.open = !this.open
          if (this.open) {
            this.$nextTick(() => this.$refs.searchInput?.focus())
          }
        },
      }
    }
  </script>
@endsection
