<button
  {{ $attributes->merge(['type' => 'button', 'class' => 'px-4 py-2 text-white rounded-lg text-xs font-semibold transition-all flex items-center gap-1.5 cursor-pointer']) }}>
  {{ $slot }}
</button>
