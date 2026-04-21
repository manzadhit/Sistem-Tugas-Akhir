@props([
    'path' => null,
    'uploadedAt' => null,
    'type' => null,
    'fileId' => null,
    'id' => null,
])

@php
  $fileId = $fileId ?? $id;
  $filename = basename($path ?? '');
  $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

  // Icon & color mapping based on extension
  [$icon, $color] = match ($extension) {
      'pdf' => ['fa-file-pdf', 'text-red-600'],
      'doc', 'docx' => ['fa-file-word', 'text-blue-600'],
      'jpg', 'jpeg', 'png', 'gif', 'svg', 'webp' => ['fa-file-image', 'text-purple-600'],
      default => ['fa-file', 'text-gray-400'],
  };

  $viewUrl = $type && $fileId ? route('files.view', ['type' => $type, 'id' => $fileId]) : '#';
  $downloadUrl = $type && $fileId ? route('files.download', ['type' => $type, 'id' => $fileId]) : '#';
@endphp

<div
  {{ $attributes->class([
      'flex flex-col items-start justify-between gap-3 px-3 py-3',
      'bg-white border border-gray-200 rounded-md mb-2 last:mb-0',
      'sm:flex-row sm:items-center sm:gap-4',
  ]) }}>
  <div class="flex w-full min-w-0 items-center gap-3 overflow-hidden sm:w-auto sm:flex-1">
    <i class="fa-solid {{ $icon }} {{ $color }} text-lg flex-shrink-0 sm:text-xl"></i>

    <div class="min-w-0 flex-1">
      <div class="truncate text-[13px] font-medium text-gray-900 sm:text-sm">
        {{ $filename ?: '-' }}
      </div>
      @if ($uploadedAt)
        <div class="text-[11px] text-gray-500 sm:text-xs">Diupload {{ $uploadedAt->translatedFormat('d M Y') }}</div>
      @endif
    </div>
  </div>

  <div class="flex w-full flex-shrink-0 gap-2 sm:w-auto sm:justify-end">
    <a href="{{ $viewUrl }}"
      class="inline-flex items-center justify-center gap-1.5 rounded-md bg-blue-50 px-3 py-1.5 text-xs font-medium text-blue-700 no-underline transition-colors hover:bg-blue-100 sm:rounded-none sm:bg-transparent sm:px-0 sm:py-0 sm:text-[0.8rem] sm:text-blue-600 sm:hover:bg-transparent sm:hover:underline">
      <i class="fas fa-eye text-[10px] sm:text-xs"></i> View
    </a>

    <a href="{{ $downloadUrl }}"
      class="inline-flex items-center justify-center gap-1.5 rounded-md bg-green-50 px-3 py-1.5 text-xs font-medium text-green-700 no-underline transition-colors hover:bg-green-100 sm:rounded-none sm:bg-transparent sm:px-0 sm:py-0 sm:text-[0.8rem] sm:text-green-600 sm:hover:bg-transparent sm:hover:underline">
      <i class="fas fa-download text-[10px] sm:text-xs"></i> Download
    </a>
  </div>
</div>
