@props([
    'path' => null,
    'uploadedAt' => null,
])

@php
  $filename = basename($path ?? '');
  $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

  // Icon & color mapping based on extension
  [$icon, $color] = match ($extension) {
      'pdf' => ['fa-file-pdf', 'text-red-600'],
      'doc', 'docx' => ['fa-file-word', 'text-blue-600'],
      'jpg', 'jpeg', 'png', 'gif', 'svg', 'webp' => ['fa-file-image', 'text-purple-600'],
      default => ['fa-file', 'text-gray-400'],
  };

  $fileUrl = $path ? Storage::url($path) : '#';
@endphp

<div
  {{ $attributes->class([
      'flex flex-col items-start justify-between gap-3 px-3 py-3',
      'bg-white border border-gray-200 rounded-md mb-2 last:mb-0',
      'sm:flex-row sm:items-center',
  ]) }}>
  <div class="flex min-w-0 items-center gap-3">
    <i class="fa-solid {{ $icon }} {{ $color }} text-xl"></i>

    <div class="min-w-0">
      <div class="truncate text-sm font-medium text-gray-900">
        {{ $filename ?: '-' }}
      </div>
      <div class="text-xs text-gray-500">Diupload {{ $uploadedAt->translatedFormat('d M Y') }}</div>
    </div>
  </div>

  <div class="flex w-full gap-2 sm:w-auto sm:justify-end">
    <a href="{{ $fileUrl }}"
      class="text-xs sm:text-[0.8rem] no-underline flex items-center gap-1 text-blue-600 hover:underline">
      <i class="fas fa-eye"></i> View
    </a>

    <a href="{{ $fileUrl }}" download
      class="text-xs sm:text-[0.8rem] no-underline flex items-center gap-1 text-green-600 hover:underline">
      <i class="fas fa-download"></i> Download
    </a>
  </div>
</div>
