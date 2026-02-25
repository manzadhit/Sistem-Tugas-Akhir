<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'UHO')</title>

  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

  {{-- Font --}}
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
    rel="stylesheet">
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
</body>

</html>
