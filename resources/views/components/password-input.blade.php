@props(['disabled' => false])

<div x-data="{ visible: false }" class="relative">
  <input x-bind:type="visible ? 'text' : 'password'" @disabled($disabled)
    {{ $attributes->merge(['class' => 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm pr-10']) }}>

  <button type="button" x-on:click="visible = !visible"
    class="absolute inset-y-0 right-0 flex items-center px-3 text-sm text-gray-400 hover:text-gray-600 focus:outline-none"
    x-bind:aria-label="visible ? 'Sembunyikan password' : 'Tampilkan password'">
    <i class="fas" x-bind:class="visible ? 'fa-eye-slash' : 'fa-eye'"></i>
  </button>
</div>
