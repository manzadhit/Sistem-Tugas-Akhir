@props([
  'name' => 'file',
  'accept' => '',
  'maxMb' => 2,
])

@php
  $maxBytes = (int) $maxMb * 1024 * 1024;
@endphp

<div
  x-data="fileUpload({ maxBytes: {{ $maxBytes }}, accept: @js($accept) })"
  class="space-y-3"
>
  <div
    class="rounded-xl border-2 border-dashed border-slate-300 bg-white/80 p-5 text-center
           min-h-[128px] flex flex-col items-center justify-center gap-2
           hover:border-blue-500 hover:bg-blue-50/40 transition cursor-pointer"
    :class="dragover ? 'border-blue-600 bg-blue-50/70 scale-[1.01]' : ''"
    @dragenter.prevent="dragover = true"
    @dragover.prevent="dragover = true"
    @dragleave.prevent="dragover = false"
    @drop.prevent="onDrop($event)"
    @click="$refs.input.click()"
  >
    <div
      class="h-11 w-11 rounded-2xl flex items-center justify-center text-white shadow-lg"
      :class="file ? 'bg-emerald-500 shadow-emerald-500/20' : 'bg-gradient-to-br from-blue-800 to-blue-600 shadow-blue-600/20'"
    >
      <i class="fas" :class="file ? 'fa-check' : 'fa-upload'"></i>
    </div>

    <p class="text-sm font-extrabold text-slate-900">Klik upload / drag &amp; drop</p>
    <p class="text-xs text-slate-500">PDF, JPG, PNG • Maks. {{ $maxMb }}MB</p>

    <input
      x-ref="input"
      type="file"
      name="{{ $name }}"
      accept="{{ $accept }}"
      class="hidden"
      @change="onChange($event)"
    />
  </div>

  <template x-if="file">
    <div class="flex items-center gap-2 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-800">
      <i class="fas fa-circle-check"></i>
      <span class="text-sm font-semibold" x-text="file.name"></span>
    </div>
  </template>

  <template x-if="error">
    <p class="text-sm text-red-600" x-text="error"></p>
  </template>
</div>

<script>
  function fileUpload({ maxBytes, accept }) {
    const allowed = (accept || "")
      .split(",")
      .map(s => s.trim().replace(".", "").toLowerCase())
      .filter(Boolean);

    return {
      dragover: false,
      file: null,
      error: "",
      validate(file) {
        if (!file) return true;
        if (file.size > maxBytes) {
          this.error = "Ukuran file melebihi batas.";
          return false;
        }
        const ext = (file.name.split(".").pop() || "").toLowerCase();
        if (allowed.length && !allowed.includes(ext)) {
          this.error = "Format file tidak sesuai.";
          return false;
        }
        this.error = "";
        return true;
      },
      setFile(file) {
        if (!this.validate(file)) {
          this.file = null;
          // reset input biar bisa pilih ulang file yg sama
          this.$refs.input.value = "";
          return;
        }
        this.file = file;
      },
      onChange(e) {
        const f = e.target.files && e.target.files[0];
        this.setFile(f);
      },
      onDrop(e) {
        this.dragover = false;
        const f = e.dataTransfer?.files?.[0];
        if (!f) return;
        // inject ke input supaya ikut terkirim saat submit
        const dt = new DataTransfer();
        dt.items.add(f);
        this.$refs.input.files = dt.files;
        this.setFile(f);
      },
    }
  }
</script>
