@props([
    'title',
    'icon' => 'fas fa-question',
    'theme' => 'blue', // Supports blue, red, etc. Default is blue.
    'confirmText' => 'Ya, Simpan',
    'cancelText' => 'Batal',
    'model' => 'showModal',
])

@php
  $iconColor = $theme === 'red' ? 'text-red-600' : 'text-blue-600';
  $iconBg = $theme === 'red' ? 'bg-red-100' : 'bg-blue-100';
  $confirmButtonClass = $theme === 'red' ? 'bg-red-600 hover:bg-red-500' : 'bg-blue-600 hover:bg-blue-500';
@endphp

<div x-show="{{ $model }}" style="display: none;" class="relative z-50">
  <div x-show="{{ $model }}" x-transition.opacity class="fixed inset-0 transition-opacity bg-black/50"></div>

  <div class="fixed inset-0 z-10 overflow-y-auto">
    <div class="flex items-center justify-center min-h-full p-4 text-center sm:p-0">
      <div x-show="{{ $model }}" x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        @click.away="{{ $model }} = false"
        class="relative px-4 pt-5 pb-4 text-left transition-all transform bg-white shadow-xl overflow-hidden rounded-xl sm:my-8 sm:w-full sm:max-w-lg sm:p-6">

        <div class="sm:flex sm:items-start">
          <div
            class="flex items-center justify-center flex-shrink-0 w-10 h-10 mx-auto {{ $iconBg }} rounded-full sm:mx-0 sm:h-10 sm:w-10">
            <i class="text-lg {{ $iconColor }} {{ $icon }} sm:text-xl"></i>
          </div>
          <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
            <h3 class="text-base font-semibold leading-6 text-gray-900 sm:text-lg">
              {{ $title }}
            </h3>
            <div class="mt-2 text-sm text-gray-500">
              {{ $slot }}
            </div>
          </div>
        </div>

        <div class="mt-5 flex flex-col-reverse gap-2 sm:flex-row sm:justify-end sm:gap-3">
          <button type="button" @click="{{ $model }} = false"
            class="inline-flex justify-center w-full px-4 py-2 text-sm font-semibold text-gray-900 bg-white shadow-sm ring-1 ring-inset ring-gray-300 rounded-lg hover:bg-gray-50 sm:w-auto">
            {{ $cancelText }}
          </button>
          <button type="submit"
            class="inline-flex justify-center w-full px-4 py-2 text-sm font-semibold text-white shadow-sm rounded-lg {{ $confirmButtonClass }} sm:w-auto">
            {{ $confirmText }}
          </button>
        </div>
      </div>
    </div>
  </div>
</div>
