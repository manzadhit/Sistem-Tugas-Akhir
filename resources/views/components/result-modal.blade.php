@props([
    'title' => '',
    'desc' => '',
    'href' => '#',
    'status' => null,
])

@php
  $statusConfig = [
      'success' => [
          'icon' => 'fas fa-check',
          'iconBg' => 'bg-emerald-100 text-emerald-600',
          'btnBg' => 'bg-emerald-600 hover:bg-emerald-500',
          'title' => 'Berhasil!',
          'desc' => 'Aksi berhasil diproses dan notifikasi telah dikirim.',
      ],
      'acc' => [
          'icon' => 'fas fa-check',
          'iconBg' => 'bg-emerald-100 text-emerald-600',
          'btnBg' => 'bg-emerald-600 hover:bg-emerald-500',
          'title' => 'Laporan Disetujui!',
          'desc' => 'Persetujuan telah diberikan dan mahasiswa akan mendapatkan notifikasi.',
      ],
      'revisi' => [
          'icon' => 'fas fa-pen',
          'iconBg' => 'bg-amber-100 text-amber-600',
          'btnBg' => 'bg-amber-500 hover:bg-amber-400',
          'title' => 'Catatan Revisi Dikirim!',
          'desc' => 'Catatan revisi telah dikirim. Mahasiswa akan diminta memperbaiki laporan.',
      ],
      'reject' => [
          'icon' => 'fas fa-times-circle',
          'iconBg' => 'bg-red-100 text-red-600',
          'btnBg' => 'bg-red-600 hover:bg-red-500',
          'title' => 'Laporan Ditolak!',
          'desc' => 'Laporan ditolak dan mahasiswa akan mendapatkan notifikasi.',
      ],
  ];

  $statusKey = is_string($status) ? strtolower($status) : null;
  $resolvedConfig = $statusKey ? $statusConfig[$statusKey] ?? null : null;

  if ($resolvedConfig) {
      if ($title !== '') {
          $resolvedConfig['title'] = $title;
      }

      if ($desc !== '') {
          $resolvedConfig['desc'] = $desc;
      }
  }
@endphp

@if ($resolvedConfig)
  <div x-data="{ show: true }">
    {{-- Overlay --}}
    <div x-show="show" x-transition.opacity class="fixed inset-0 bg-black/50 z-[100]" style="display: none;"></div>

    {{-- Modal --}}
    <div x-show="show" class="fixed inset-0 z-[101] overflow-y-auto" style="display: none;">
      <div class="flex items-center justify-center min-h-full p-4 text-center sm:p-0">
        <div x-show="show" x-transition:enter="ease-out duration-300"
          x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
          x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
          class="relative px-4 pt-5 pb-4 text-left transition-all transform bg-white shadow-xl overflow-hidden rounded-xl sm:my-8 sm:w-full sm:max-w-sm sm:p-6">
          <div>
            <div
              class="mx-auto flex h-12 w-12 items-center justify-center rounded-full mb-4 {{ $resolvedConfig['iconBg'] }}">
              <i class="text-xl {{ $resolvedConfig['icon'] }}"></i>
            </div>
            <div class="text-center">
              <h3 class="text-lg font-semibold leading-6 text-gray-900">{{ $resolvedConfig['title'] }}</h3>
              <p class="mt-2 text-sm text-gray-500">{{ $resolvedConfig['desc'] }}</p>
            </div>
          </div>
          <div class="mt-5 sm:mt-6">
            <a href="{{ $href }}"
              class="inline-flex w-full justify-center rounded-lg px-3 py-2 text-sm font-semibold text-white shadow-sm transition-colors {{ $resolvedConfig['btnBg'] }}">
              Oke, Kembali ke Daftar
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
@endif
