<header
  class="fixed top-0 left-0 right-0 z-50 border-b border-slate-200 shadow-sm flex justify-between items-center bg-white py-3 px-4 md:px-8">
  <div class="flex items-center gap-3">
    {{-- Hamburger (mobile only) --}}
    @hasSection('sidebar')
      <button type="button"
        class="inline-flex items-center justify-center rounded-lg border border-slate-200 p-2 text-slate-600 hover:bg-slate-50 md:hidden"
        onclick="window.dispatchEvent(new CustomEvent('toggle-sidebar'))">
        <i class="fas fa-bars text-lg"></i>
      </button>
    @endif

    <img class="h-12 w-auto" src="{{ asset('images/logo.png') }}" alt="Logo UHO">
  </div>

  <div class="flex items-center gap-x-4">
    {{-- Notification Bell --}}
    @php
      $notifications = auth()->user()->notifications()->latest()->limit(3)->get();
      $unreadNotifications = auth()->user()->unreadNotifications;
    @endphp
    <div class="relative" x-cloak x-data="{ open: false }" @click.outside="open = false">
      <button type="button" @click="open = !open"
        class="relative border py-1.5 px-3 rounded-xl cursor-pointer hover:text-slate-600 transition">
        <i class="fa-regular fa-bell"></i>
        @if ($unreadNotifications->isNotEmpty())
          <span
            class="absolute -top-2 -right-2 w-4 h-4 flex items-center justify-center text-[10px] bg-red-500 rounded-full text-white leading-none">{{ $unreadNotifications->count() }}</span>
        @endif
      </button>

      <div x-show="open" x-transition.origin.top.right
        class="absolute right-0 mt-2 w-80 rounded-xl border border-slate-200 bg-white shadow-lg overflow-hidden z-50">

        {{-- Header --}}
        <div class="flex items-center justify-between px-4 py-3 border-b border-slate-100">
          <p class="text-sm font-semibold text-slate-900">Notifikasi</p>
          @if ($unreadNotifications->isNotEmpty())
            <span
              class="text-xs bg-red-100 text-red-600 font-medium px-2 py-0.5 rounded-full">{{ $unreadNotifications->count() }}
              baru</span>
          @endif
        </div>

        {{-- Notification Items --}}
        <ul class="divide-y divide-slate-100">
          @forelse ($notifications as $notif)
            <li>
              <a href="{{ $notif->data['action_url'] ?? '#' }}"
                class="flex items-start gap-3 px-4 py-3 transition hover:bg-slate-50">
                <div
                  class="mt-0.5 flex h-8 w-8 flex-shrink-0 items-center justify-center rounded-full {{ $notif->data['icon_bg'] ?? 'bg-slate-100' }}">
                  <i
                    class="{{ $notif->data['icon'] ?? 'fas fa-bell' }} text-xs {{ $notif->data['icon_color'] ?? 'text-slate-500' }}"></i>
                </div>
                <div class="min-w-0 flex-1">
                  <p class="text-sm font-medium text-slate-800">{{ $notif->data['title'] }}</p>
                  <p class="mt-0.5 text-xs leading-relaxed text-slate-500">{{ $notif->data['message'] }}</p>
                  <p class="mt-1 text-xs text-slate-400"><i
                      class="far fa-clock mr-1"></i>{{ $notif->created_at->diffForHumans() }}</p>
                </div>
                @if (is_null($notif->read_at))
                  <span class="mt-2 h-2 w-2 flex-shrink-0 rounded-full bg-blue-500"></span>
                @else
                  <i class="fas fa-chevron-right mt-2 flex-shrink-0 text-[11px] text-slate-400"></i>
                @endif
              </a>
            </li>
          @empty
            <li class="px-4 py-8 text-center">
              <div
                class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                <i class="fa-regular fa-bell-slash text-lg"></i>
              </div>
              <p class="text-sm font-medium text-slate-700">Belum ada notifikasi</p>
              <p class="mt-1 text-xs leading-relaxed text-slate-500">Semua pembaruan akan muncul di sini saat tersedia.
              </p>
            </li>
          @endforelse
        </ul>

        {{-- Footer --}}
        <div class="px-4 py-2.5 border-t border-slate-100 text-center">
          <a href="{{ route('notifications.index') }}" class="text-xs text-blue-600 hover:underline font-medium">Lihat
            semua notifikasi</a>
        </div>
      </div>
    </div>

    <x-dropdown-user :name="auth()->user()->display_name" :subtitle="auth()->user()->display_subtitle" />
  </div>
</header>
