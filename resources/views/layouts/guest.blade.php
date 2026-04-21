<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'UHO') }}</title>

  <!-- Scripts -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased">
  <div class="min-h-screen flex flex-col justify-center items-center px-4 py-6 sm:px-6 sm:py-0 bg-gradient-to-br from-gray-100 to-blue-50">
    <div>
      <a href="/">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-16 w-auto mx-auto sm:h-20">
      </a>
    </div>

    <div class="w-full sm:max-w-md mt-4 px-5 py-5 bg-white shadow-md overflow-hidden rounded-xl sm:mt-6 sm:px-6 sm:py-4 sm:rounded-lg">
      {{ $slot }}
    </div>
  </div>
</body>

</html>
