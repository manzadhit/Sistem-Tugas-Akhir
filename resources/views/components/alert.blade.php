@props(['type' => 'success'])

@php
  $config = [
      'success' => [
          'bg' => 'bg-emerald-50',
          'border' => 'border-emerald-200',
          'text' => 'text-emerald-800',
          'icon' => 'fa-check-circle',
          'iconColor' => 'text-emerald-600',
      ],
      'error' => [
          'bg' => 'bg-red-50',
          'border' => 'border-red-200',
          'text' => 'text-red-800',
          'icon' => 'fa-times-circle',
          'iconColor' => 'text-red-600',
      ],
      'warning' => [
          'bg' => 'bg-amber-50',
          'border' => 'border-amber-200',
          'text' => 'text-amber-800',
          'icon' => 'fa-exclamation-triangle',
          'iconColor' => 'text-amber-600',
      ],
      'info' => [
          'bg' => 'bg-blue-50',
          'border' => 'border-blue-200',
          'text' => 'text-blue-800',
          'icon' => 'fa-info-circle',
          'iconColor' => 'text-blue-600',
      ],
  ];

  $style = $config[$type] ?? $config['success'];
@endphp

@if (session($type))
  <div
    {{ $attributes->merge(['class' => "mb-4 rounded-xl border {$style['bg']} {$style['border']} px-4 py-3 text-sm {$style['text']} flex items-start gap-3"]) }}>
    <i class="fas {{ $style['icon'] }} {{ $style['iconColor'] }} mt-0.5"></i>
    <div class="flex-1">
      {{ session($type) }}
    </div>
  </div>
@endif
