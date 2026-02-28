@props([
    'title' => 'Menu',
    'subtitle' => '',
    'items' => [],
])

<div x-data="{ sidebarOpen: false }" @toggle-sidebar.window="sidebarOpen = !sidebarOpen" class="md:w-[16rem] md:shrink-0">
  {{-- Mobile overlay --}}
  <div class="fixed inset-0 z-40 bg-gray-900/40 md:hidden" x-show="sidebarOpen" x-transition.opacity
    @click="sidebarOpen = false"></div>

  {{-- Sidebar --}}
  <aside
    class="fixed left-0 top-[73px] z-40 h-[calc(100vh-73px)] w-[16rem] overflow-y-auto bg-white shadow-[2px_0_4px_rgba(0,0,0,0.05)] transition-transform duration-200 ease-in-out md:translate-x-0"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'">
    <div class="p-6">
      <div class="mb-2 flex items-center justify-between">
        <h2 class="text-xl font-bold text-gray-900">{{ $title }}</h2>
        <button type="button" class="rounded p-1.5 text-gray-400 hover:text-gray-600 md:hidden"
          @click="sidebarOpen = false">
          <i class="fas fa-times"></i>
        </button>
      </div>

      @if ($subtitle)
        <p class="mb-5 text-sm text-gray-500">{{ $subtitle }}</p>
      @endif

      <nav class="flex flex-col gap-1">
        @foreach ($items as $item)
          @if (!empty($item['section']))
            {{-- Section title --}}
            <div class="mt-3 mb-1 px-4 text-xs font-bold uppercase tracking-wider text-gray-400">
              {{ $item['section'] }}
            </div>
          @else
            {{-- Nav link --}}
            <a href="{{ $item['href'] }}"
              class="{{ !empty($item['active'])
                  ? 'bg-blue-50 font-semibold text-blue-600'
                  : 'text-gray-600 hover:bg-blue-50 hover:text-blue-600' }}
                  flex items-center rounded-lg px-4 py-2.5 text-[0.95rem] transition-all duration-200"
              @click="sidebarOpen = false">
              <i class="{{ $item['icon'] }} w-5 text-center mr-3"></i>
              <span>{{ $item['label'] }}</span>
              @if (!empty($item['badge']))
                  <span class="ml-auto rounded-full bg-red-500 px-2 py-0.5 text-[0.7rem] font-semibold text-white">{{ $item['badge'] }}</span>
              @endif
            </a>
          @endif
        @endforeach
      </nav>
    </div>
  </aside>
</div>
