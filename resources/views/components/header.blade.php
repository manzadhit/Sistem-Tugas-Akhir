<header class="fixed top-0 left-0 right-0 z-50 bg-white/90 backdrop-blur border-b border-slate-200">
  <div class="max-w-8xl mx-auto px-4 md:px-6 h-20 flex items-center justify-between">
    <div>
      <img src="{{ asset('images/logo.png') }}" alt="Logo Halu Oleo" class="h-10 w-auto">
    </div>

    <div class="flex items-center gap-3">
      <button type="button"
        class="relative h-10 w-10 rounded-xl border border-slate-200 bg-white text-slate-500 hover:text-slate-900 hover:bg-slate-50 transition"
        title="Notifikasi">
        <i class="far fa-bell"></i>
      </button>

      <x-dropdown-user :name="auth()->user()->display_name" :subtitle="auth()->user()->display_subtitle" />

    </div>
  </div>
</header>
