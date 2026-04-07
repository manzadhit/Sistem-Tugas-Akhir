@props([
    'name' => 'file',
    'accept' => '.pdf,.doc,.docx',
    'maxMb' => 10,
    'multiple' => false,
    'required' => false,
    'label' => '',
    'hint' => null,
])

@php
  $maxBytes = (int) $maxMb * 1024 * 1024;
  $defaultHint =
      'Format yang didukung: ' .
      strtoupper(str_replace(',', ', ', str_replace('.', '', $accept))) .
      " (Maks {$maxMb}MB)";
@endphp

<div x-data="fileUpload({ maxMb: {{ $maxMb }}, maxBytes: {{ $maxBytes }}, accept: @js($accept), multiple: @js($multiple) })" {{ $attributes->class(['space-y-3']) }}>
  @if ($label !== '')
    <label class="block text-sm font-medium text-gray-700">
      {{ $label }}
      @if ($required)
        <span class="text-red-600">*</span>
      @endif
    </label>
  @endif

  <div @click="$refs.fileInput.click()" @dragover.prevent="dragging = true" @dragleave.prevent="dragging = false"
    @drop.prevent="handleDrop($event)" :class="dragging ? 'border-blue-600 bg-blue-50' : 'border-gray-300'"
    class="border-2 border-dashed rounded-xl p-6 text-center transition-all cursor-pointer hover:border-blue-600 hover:bg-blue-50">
    <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-3"></i>
    <p class="text-gray-500 mb-1">Drag and drop file Anda di sini, atau <span class="text-blue-600 font-medium">browse
        files</span></p>
    <p class="text-xs text-gray-400">{{ $hint ?? $defaultHint }}</p>

    <input x-ref="fileInput" type="file" name="{{ $name }}" accept="{{ $accept }}" class="hidden"
      @change="handleFiles($event.target.files)" @if ($multiple) multiple @endif
      @if ($required) required @endif />
  </div>

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
  <p x-show="errorMessage" x-text="errorMessage" class="text-xs text-red-600" x-cloak></p>
</div>
