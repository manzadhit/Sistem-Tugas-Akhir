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
    <div class="relative border py-1.5 px-3 rounded-xl cursor-pointer hover:text-slate-600">
      <i class="fa-regular fa-bell"></i>
      <span class="absolute -top-2 -right-2 text-sm bg-red-500 px-1.5 rounded-full text-white">3</span>
    </div>

    <x-dropdown-user :name="auth()->user()->display_name" :subtitle="auth()->user()->display_subtitle" />
  </div>
</header>
