@props([
    'title' => 'Menu',
    'subtitle' => '',
    'items' => [],
])

<div x-data="{ sidebarOpen: false }" @toggle-sidebar.window="sidebarOpen = !sidebarOpen" class="md:w-64 md:shrink-0">
  {{-- Mobile overlay --}}
  <div class="fixed inset-0 z-40 bg-slate-900/40 md:hidden" x-show="sidebarOpen" x-transition.opacity
    @click="sidebarOpen = false"></div>

  {{-- Sidebar --}}
  <aside
    class="fixed left-0 top-[73px] z-40 h-[calc(100vh-73px)] w-64 overflow-y-auto border-r border-slate-200 bg-white px-4 py-6 transition-transform duration-200 ease-in-out md:translate-x-0"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full md:translate-x-0'">
    <div class="mb-1 flex items-center justify-between">
      <h2 class="text-xl font-bold text-slate-900">{{ $title }}</h2>
      <button type="button" class="rounded p-1.5 text-slate-400 hover:text-slate-600 md:hidden"
        @click="sidebarOpen = false">
        <i class="fas fa-times"></i>
      </button>
    </div>

    @if ($subtitle)
      <p class="mb-5 text-xs text-slate-500">{{ $subtitle }}</p>
    @else
      <div class="mb-5"></div>
    @endif

    <nav class="space-y-1">
      @foreach ($items as $item)
        @if (!empty($item['section']))
          {{-- Section title --}}
          <p class="mt-4 mb-1 px-3 text-[0.7rem] font-semibold uppercase tracking-wider text-slate-400">
            {{ $item['section'] }}
          </p>
        @else
          {{-- Nav link --}}
          <a href="{{ $item['href'] }}"
            class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm transition
                            {{ !empty($item['active'])
                                ? 'bg-blue-50 font-semibold text-blue-700'
                                : 'text-slate-600 hover:bg-blue-50 hover:text-blue-700' }}"
            @click="sidebarOpen = false">
            <i class="{{ $item['icon'] }} w-4 text-center"></i>
            <span>{{ $item['label'] }}</span>
          </a>
        @endif
      @endforeach
    </nav>
  </aside>
</div>
