@props([
    'src' => null,
    'initials' => '',
    'size' => 'md',
])

@php
  $sizes = [
      'xs' => 'w-6 h-6 text-[10px]',
      'sm' => 'w-8 h-8 text-xs',
      'md' => 'w-9 h-9 text-sm',
      'lg' => 'w-10 h-10 text-sm',
      'xl' => 'w-14 h-14 text-xl',
      '2xl' => 'w-16 h-16 text-2xl',
  ];
  $s = $sizes[$size] ?? $sizes['md'];
@endphp

@if ($src)
  <img src="{{ Storage::url($src) }}" alt=""
    {{ $attributes->merge(['class' => "$s rounded-full object-cover shrink-0"]) }}
    onerror="this.style.display='none';this.nextElementSibling.style.display='flex';" />
  <div {{ $attributes->merge(['class' => "$s rounded-full bg-blue-100 text-blue-600 font-semibold items-center justify-center shrink-0"]) }}
    style="display: none;">{{ $initials }}</div>
@else
  <div {{ $attributes->merge(['class' => "$s rounded-full bg-blue-100 text-blue-600 font-semibold flex items-center justify-center shrink-0"]) }}>
    {{ $initials }}</div>
@endif
