@extends('layouts.app')

@section('title', 'Semua Notifikasi')

@section('sidebar')
  @php $role = auth()->user()->role; @endphp
  @if ($role === 'kajur')
    @include('kajur.sidebar')
  @elseif ($role === 'dosen')
    @include('dosen.sidebar')
  @elseif ($role === 'mahasiswa')
    @include('mahasiswa.sidebar')
  @elseif ($role === 'admin')
    @include('admin.sidebar')
  @endif
@endsection

@section('content')
  <div class="bg-gradient-to-br from-blue-800 to-blue-500 rounded-2xl p-5 sm:p-8 mb-6 text-white">
    <div class="flex justify-between items-center flex-wrap gap-4">
      <div>
        <h1 class="text-xl sm:text-[1.75rem] font-bold mb-1 sm:mb-2">
          <i class="fa-regular fa-bell mr-2 sm:mr-3"></i>Semua Notifikasi
        </h1>
        <p class="opacity-90 text-sm sm:text-[0.95rem]">Riwayat seluruh notifikasi yang masuk ke akun Anda</p>
      </div>
      @if (auth()->user()->unreadNotifications->isNotEmpty())
        <form action="{{ route('notifications.markAllRead') }}" method="POST">
          @csrf
          <button type="submit"
            class="inline-flex items-center gap-2 rounded-xl border border-white/30 bg-white/10 px-4 py-2 text-sm font-medium text-white backdrop-blur-sm hover:bg-white/20 transition">
            <i class="fas fa-check-double text-xs"></i> Tandai semua sudah dibaca
          </button>
        </form>
      @endif
    </div>
  </div>

  <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    @forelse ($notifications as $notif)
      <div
        class="flex items-start gap-4 px-5 py-4 border-b border-slate-100 last:border-b-0 transition hover:bg-slate-50 {{ is_null($notif->read_at) ? 'bg-orange-50/40' : '' }}">
        {{-- Icon --}}
        <div
          class="mt-0.5 flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full {{ $notif->data['icon_bg'] ?? 'bg-slate-100' }}">
          <i
            class="{{ $notif->data['icon'] ?? 'fas fa-bell' }} text-sm {{ $notif->data['icon_color'] ?? 'text-slate-500' }}"></i>
        </div>

        {{-- Content --}}
        <div class="min-w-0 flex-1">
          <div class="flex items-start justify-between gap-2">
            <p class="text-sm font-semibold text-slate-800">
              {{ $notif->data['title'] ?? 'Notifikasi' }}
              @if (is_null($notif->read_at))
                <span
                  class="ml-2 inline-block rounded-full bg-orange-100 px-2 py-0.5 text-[10px] font-medium text-orange-600">Baru</span>
              @endif
            </p>
            <p class="shrink-0 text-xs text-slate-400">
              <i class="far fa-clock mr-1"></i>{{ $notif->created_at->diffForHumans() }}
            </p>
          </div>
          <p class="mt-1 text-sm leading-relaxed text-slate-500">{{ $notif->data['message'] ?? '' }}</p>
        </div>

        {{-- Action --}}
        @if (!empty($notif->data['action_url']))
          <a href="{{ $notif->data['action_url'] }}"
            class="mt-1 flex-shrink-0 rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-medium text-slate-600 hover:bg-slate-100 transition">
            Lihat
          </a>
        @endif
      </div>
    @empty
      <div class="px-5 py-16 text-center">
        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-slate-100 text-slate-400">
          <i class="fa-regular fa-bell-slash text-xl"></i>
        </div>
        <p class="text-sm font-medium text-slate-700">Belum ada notifikasi</p>
        <p class="mt-1 text-xs text-slate-500">Semua pembaruan akan muncul di sini saat tersedia.</p>
      </div>
    @endforelse
  </div>

  {{-- Pagination --}}
  @if ($notifications->hasPages())
    <div class="mt-4">
      {{ $notifications->links() }}
    </div>
  @endif
@endsection
