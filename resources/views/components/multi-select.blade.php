@props([
    'name',
    'options' => [],
    'selected' => [],
    'placeholder' => 'Pilih data...',
    'searchPlaceholder' => 'Cari...',
    'emptyText' => 'Data tidak ditemukan.',
])

<div class="w-full" x-data="multiSelectComponent({
    options: @js($options),
    selected: @js($selected),
})" @click.outside="open = false">
  <template x-for="id in selected" :key="`${name}-${id}`">
    <input type="hidden" name="{{ $name }}[]" :value="id">
  </template>

  <div class="rounded-xl border border-slate-200 bg-white shadow-sm focus-within:ring-4 focus-within:ring-blue-100 focus-within:border-blue-400"
    :class="open ? 'border-blue-400' : ''">
    <div class="flex flex-wrap items-center gap-2 p-2" @click="focusSearch()">
      <template x-for="item in selectedItems" :key="`chip-${item.id}`">
        <span
          class="inline-flex items-center gap-2 rounded-lg bg-slate-100 border border-slate-200 px-3 py-1.5 text-sm text-slate-700">
          <span x-text="item.label"></span>
          <button type="button" class="text-slate-500 hover:text-slate-700" @click.stop="remove(item.id)">
            <i class="fas fa-times"></i>
          </button>
        </span>
      </template>

      <input type="text" x-ref="searchInput" x-model="query" @focus="open = true" @input="open = true"
        @keydown.escape.prevent="open = false; showAllOnOpen = false"
        @keydown.enter.prevent="selectFirstFiltered()"
        @keydown.backspace="removeLastSelected()"
        class="flex-1 min-w-[180px] border-0 bg-transparent px-2 py-1.5 text-sm text-slate-900 placeholder:text-slate-400 focus:ring-0 focus:outline-none"
        :placeholder="selectedItems.length ? '{{ $searchPlaceholder }}' : '{{ $placeholder }}'" />

      <button type="button" class="px-2 text-slate-400 hover:text-slate-700" @click.stop="toggleDropdown()">
        <i class="fas fa-chevron-down text-xs"></i>
      </button>
    </div>

    <div x-show="showDropdown" x-transition.origin.top.left class="border-t border-slate-200 max-h-64 overflow-auto">
      <template x-if="filteredOptions.length === 0">
        <div class="px-4 py-3 text-sm text-slate-500">{{ $emptyText }}</div>
      </template>

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

@once
  @push('scripts')
    <script>
      function multiSelectComponent(config) {
        return {
          open: false,
          showAllOnOpen: false,
          query: '',
          options: config.options ?? [],
          selected: (config.selected ?? []).map(String),
          get filteredOptions() {
            const q = this.query.toLowerCase().trim()
            if (!q) return this.options
            return this.options.filter((option) => option.label.toLowerCase().includes(q))
          },
          get showDropdown() {
            return this.open && (this.showAllOnOpen || this.query.trim().length > 0)
          },
          get selectedItems() {
            return this.options.filter((option) => this.selected.includes(String(option.id)))
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
                this.showAllOnOpen = false
                this.query = ''
              }
              return
            }

            this.selected = [...this.selected, key]

            if (shouldClose) {
              this.open = false
              this.showAllOnOpen = false
              this.query = ''
              return
            }

            this.open = true
            this.showAllOnOpen = false
            this.query = ''
            this.$nextTick(() => this.$refs.searchInput?.focus())
          },
          remove(id) {
            const key = String(id)
            this.selected = this.selected.filter((selectedId) => selectedId !== key)
          },
          removeLastSelected() {
            if (this.query.length > 0 || this.selected.length === 0) return
            this.selected = this.selected.slice(0, -1)
          },
          selectFirstFiltered() {
            if (!this.filteredOptions.length) return
            this.toggle(this.filteredOptions[0].id, true)
          },
          focusSearch() {
            this.open = true
            this.showAllOnOpen = true
            this.$nextTick(() => this.$refs.searchInput?.focus())
          },
          toggleDropdown() {
            this.open = !this.open
            if (this.open) {
              this.showAllOnOpen = true
              this.$nextTick(() => this.$refs.searchInput?.focus())
              return
            }

            this.showAllOnOpen = false
          },
        }
      }
    </script>
  @endpush
@endonce
