<div {{ $attributes->merge([
  'class' => 'mb-4 flex gap-3 items-center rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 text-blue-800'
]) }}>
  <div class="h-9 w-9 rounded-xl bg-blue-100 flex items-center justify-center shrink-0">
    <i class="fas fa-circle-exclamation"></i>
  </div>
  <div class="text-sm leading-relaxed">
    {{ $slot }}
  </div>
</div>
