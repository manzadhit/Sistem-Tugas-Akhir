@props([
  'name' => 'User',
  'subtitle' => '',
])

<div class="relative" x-data="{ open: false }" @click.outside="open = false">
  <button type="button"
    class="flex items-center gap-2 text-slate-700 hover:text-slate-900 transition"
    @click="open = !open"
    :aria-expanded="open.toString()"
  >
    <span class="hidden md:block text-sm font-medium">Hi, {{ $name }}</span>
    <i class="far fa-user-circle text-xl"></i>
    <i class="fas fa-chevron-down text-xs transition" :class="open ? 'rotate-180' : ''"></i>
  </button>

  <div
    x-show="open"
    x-transition.origin.top.right
    class="absolute right-0 mt-2 w-48 rounded-xl border border-slate-200 bg-white shadow-lg overflow-hidden"
  >
    <div class="px-4 py-3 border-b border-slate-100">
      <p class="text-sm font-semibold text-slate-900">{{ $name }}</p>
      @if($subtitle)
        <p class="text-xs text-slate-500">{{ $subtitle }}</p>
      @endif
    </div>

    <a href="#" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50">
      <i class="far fa-user w-4"></i> Profile
    </a>
    <a href="#" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50">
      <i class="fas fa-cog w-4"></i> Pengaturan
    </a>

    <div class="h-px bg-slate-100"></div>

    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50">
        <i class="fas fa-sign-out-alt w-4"></i> Logout
      </button>
    </form>
  </div>
</div>
