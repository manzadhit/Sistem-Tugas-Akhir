@props([
  'bgColor' => '',
  'textColor' => '',
  'label' => '',
  'total' => 0
])

<div {{ $attributes->merge([
  'class' =>"flex items-center gap-4 rounded-xl border border-slate-200 bg-white p-5 shadow-sm"
]) }}>
  <div class="flex h-14 w-14 items-center justify-center rounded-xl {{ $bgColor }} text-xl {{ $textColor }}">
    {{ $icon }}
  </div>
  <div>
    <p class="text-sm text-slate-500">{{ $label }}</p>
    <p class="text-2xl font-bold text-slate-900">{{ $total }}</p>
  </div>
</div>
