<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'UHO')</title>

  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-[#f3f4f6] text-slate-900 font-sans">
  @include('partials.header')

  <div class="flex min-h-screen pt-[73px]">
    @hasSection('sidebar')
      {{-- Sidebar --}}
      @yield('sidebar')

      {{-- Main Content (with sidebar offset) --}}
      <main class="flex-1 min-w-0 overflow-x-hidden px-4 md:px-8 py-8">
        @yield('content')
      </main>
    @else
      {{-- Main Content (full width, no sidebar) --}}
      <main class="flex-1 px-4 md:px-6 py-8">
        @yield('content')
      </main>
    @endif
  </div>

  @stack('scripts')
</body>

</html>
