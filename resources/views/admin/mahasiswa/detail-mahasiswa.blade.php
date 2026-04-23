@extends('layouts.app')

@section('title', 'Detail Mahasiswa')

@section('sidebar')
  @include('admin.sidebar')
@endsection

@section('content')

  <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
    <a href="{{ route('admin.mahasiswa.index') }}" class="hover:text-blue-600 transition-colors">
      Kelola Mahasiswa
    </a>
    <i class="fas fa-chevron-right text-xs text-gray-400"></i>
    <span class="text-gray-800 font-medium truncate">{{ $mhs->nama_lengkap }}</span>
  </div>

  {{-- header --}}
  <div class="bg-white border border-gray-200 rounded-2xl shadow-sm mb-6">
    <div class="p-5 sm:p-6">
      <div class="flex flex-col lg:flex-row lg:items-center gap-5">
        <div class="flex items-center gap-4 sm:gap-5 flex-1 min-w-0">
          <x-avatar :src="$mhs->foto" :initials="$mhs->initials" size="2xl" class="!rounded-xl !bg-blue-50 !text-blue-700 border border-blue-100 !text-3xl !font-bold" />
          <div class="min-w-0">
            <h1 class="text-xl sm:text-2xl font-semibold text-gray-900 truncate">{{ $mhs->nama_lengkap }}</h1>
            <p class="text-sm text-gray-500 mt-0.5">NIM {{ $mhs->nim }} · Angkatan {{ $mhs->angkatan }}</p>
            <div class="mt-2 flex flex-wrap gap-2">
              <span
                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700 border border-green-200">
                {{ $mhs->status_akademik }}
              </span>
            </div>
          </div>
        </div>

        <div class="flex items-center gap-2 shrink-0">
          <a href="{{ route('admin.mahasiswa.edit', $mhs->id) }}"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold bg-blue-600 text-white hover:bg-blue-700 transition-colors">
            <i class="fas fa-edit text-xs"></i> Edit Data
          </a>
        </div>
      </div>
    </div>

  </div>

  <div class="space-y-5">
    {{-- data Pribadi --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
      <div class="px-5 py-3.5 border-b border-gray-100 flex items-center gap-2">
        <i class="fas fa-id-card text-blue-500 text-sm"></i>
        <h2 class="text-sm font-semibold text-gray-800">Data Pribadi</h2>
      </div>
      <div class="px-5 py-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <div class="flex items-start gap-3">
          <div
            class="w-8 h-8 rounded-lg bg-blue-50 text-blue-500 flex items-center justify-center text-xs shrink-0 mt-0.5">
            <i class="fas fa-id-badge"></i>
          </div>
          <div class="min-w-0">
            <div class="text-xs text-gray-400 mb-0.5">NIM</div>
            <div class="text-sm font-medium text-gray-800 break-words">{{ $mhs->nim }}</div>
          </div>
        </div>

        <div class="flex items-start gap-3">
          <div
            class="w-8 h-8 rounded-lg bg-blue-50 text-blue-500 flex items-center justify-center text-xs shrink-0 mt-0.5">
            <i class="fas fa-layer-group"></i>
          </div>
          <div class="min-w-0">
            <div class="text-xs text-gray-400 mb-0.5">Angkatan</div>
            <div class="text-sm font-medium text-gray-800 break-words">{{ $mhs->angkatan }}</div>
          </div>
        </div>

        <div class="flex items-start gap-3">
          <div
            class="w-8 h-8 rounded-lg bg-blue-50 text-blue-500 flex items-center justify-center text-xs shrink-0 mt-0.5">
            <i class="fas fa-building-columns"></i>
          </div>
          <div class="min-w-0">
            <div class="text-xs text-gray-400 mb-0.5">Jurusan</div>
            <div class="text-sm font-medium text-gray-800 break-words">{{ $mhs->jurusan }}</div>
          </div>
        </div>

        <div class="flex items-start gap-3">
          <div
            class="w-8 h-8 rounded-lg bg-blue-50 text-blue-500 flex items-center justify-center text-xs shrink-0 mt-0.5">
            <i class="fas fa-graduation-cap"></i>
          </div>
          <div class="min-w-0">
            <div class="text-xs text-gray-400 mb-0.5">Jurusan</div>
            <div class="text-sm font-medium text-gray-800 break-words">{{ $mhs->jurusan }}</div>
          </div>
        </div>

        <div class="flex items-start gap-3">
          <div
            class="w-8 h-8 rounded-lg bg-blue-50 text-blue-500 flex items-center justify-center text-xs shrink-0 mt-0.5">
            <i class="fas fa-star-half-alt"></i>
          </div>
          <div class="min-w-0">
            <div class="text-xs text-gray-400 mb-0.5">IPK</div>
            <div class="text-sm font-medium text-gray-800 break-words">{{ $mhs->ipk ?? '-' }}</div>
          </div>
        </div>

        <div class="flex items-start gap-3">
          <div
            class="w-8 h-8 rounded-lg bg-blue-50 text-blue-500 flex items-center justify-center text-xs shrink-0 mt-0.5">
            <i class="fas fa-phone"></i>
          </div>
          <div class="min-w-0">
            <div class="text-xs text-gray-400 mb-0.5">No. Telp</div>
            <div class="text-sm font-medium text-gray-800 break-words">{{ $mhs->no_telp }}</div>
          </div>
        </div>

        <div class="flex items-start gap-3">
          <div
            class="w-8 h-8 rounded-lg bg-blue-50 text-blue-500 flex items-center justify-center text-xs shrink-0 mt-0.5">
            <i class="fas fa-envelope"></i>
          </div>
          <div class="min-w-0">
            <div class="text-xs text-gray-400 mb-0.5">Email</div>
            <div class="text-sm font-medium text-gray-800 break-words">{{ $mhs->user->email }}</div>
          </div>
        </div>

        <div class="flex items-start gap-3">
          <div
            class="w-8 h-8 rounded-lg bg-blue-50 text-blue-500 flex items-center justify-center text-xs shrink-0 mt-0.5">
            <i class="fas fa-user-circle"></i>
          </div>
          <div class="min-w-0">
            <div class="text-xs text-gray-400 mb-0.5">Status</div>
            <div class="text-sm font-medium text-gray-800 break-words">{{ $mhs->status_akademik }}</div>
          </div>
        </div>
      </div>
    </div>

    {{-- data tugas akhir --}}
    @if ($mhs->tugasAkhir)
      <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-5 py-3.5 border-b border-gray-100 flex items-center justify-between">
          <div class="flex items-center gap-2">
            <i class="fas fa-book-open text-purple-500 text-sm"></i>
            <h2 class="text-sm font-semibold text-gray-800">Tugas Akhir</h2>
          </div>
          <div class="flex items-center gap-2">
            <span
              class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-purple-100 text-purple-700">
              {{ ucfirst($mhs->tugasAkhir->tahapan) }}
            </span>
          </div>
        </div>
        <div class="px-5 py-4">
          <h3 class="text-base font-semibold text-gray-900 mb-2 leading-snug">{{ $mhs->tugasAkhir->judul }}</h3>
          <p class="text-sm text-gray-600 mb-3 leading-relaxed">
            {{ $mhs->tugasAkhir->abstrak ?? '' }}
          </p>
          <div class="flex flex-wrap gap-1.5">
            @php
              $kataKunci = array_filter(array_map('trim', explode(',', $mhs->tugasAkhir->kata_kunci ?? '')));
            @endphp

            @forelse ($kataKunci as $keyword)
              <span class="px-2.5 py-0.5 bg-gray-100 text-gray-600 text-xs rounded-full">{{ $keyword }}</span>
            @empty
              <span class="text-xs text-gray-400"></span>
            @endforelse
          </div>
        </div>
      </div>
    @endif

    {{-- data dosen pembimbing --}}
    @if ($mhs->dosenPembimbing->isNotEmpty())
      <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-5 py-3.5 border-b border-gray-100 flex items-center gap-2">
          <i class="fas fa-chalkboard-teacher text-green-500 text-sm"></i>
          <h2 class="text-sm font-semibold text-gray-800">Dosen Pembimbing</h2>
        </div>
        <div class="px-5 py-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
          @foreach ($mhs->dosenPembimbing as $pembimbing)
            <div class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 border border-gray-100">
              <div
                class="w-9 h-9 rounded-full bg-green-100 text-green-600 flex items-center justify-center text-xs font-bold shrink-0">
                {{ $loop->iteration }}
              </div>
              <div class="min-w-0">
                <div class="text-xs text-gray-400 mb-0.5">Pembimbing {{ $loop->iteration }}</div>
                <div class="text-sm font-semibold text-gray-800">{{ $pembimbing->dosen->nama_lengkap }}</div>
                <div class="text-xs text-gray-400">{{ $pembimbing->dosen->nip ?? '' }}</div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    @endif

    {{-- data ujian --}}
    @if ($mhs->tugasAkhir && $ujianList->count())
      @php
        $statusCfg = [
            'selesai' => [
                'dot' => 'bg-green-100 text-green-600 border-green-300',
                'line' => 'bg-green-200',
                'check' => true,
                'badge' => 'bg-green-100 text-green-700',
                'icon' => 'fa-check-circle',
                'label' => 'Selesai',
            ],
            'dijadwalkan' => [
                'dot' => 'bg-blue-100 text-blue-600 border-blue-300',
                'line' => 'bg-gray-200',
                'check' => false,
                'badge' => 'bg-blue-100 text-blue-700',
                'icon' => 'fa-calendar-check',
                'label' => 'Dijadwalkan',
            ],
            'menunggu_verifikasi' => [
                'dot' => 'bg-blue-100 text-blue-600 border-blue-300',
                'line' => 'bg-gray-200',
                'check' => false,
                'badge' => 'bg-yellow-100 text-yellow-700',
                'icon' => 'fa-hourglass-half',
                'label' => 'Menunggu Verifikasi',
            ],
            'diajukan' => [
                'dot' => 'bg-blue-100 text-blue-600 border-blue-300',
                'line' => 'bg-gray-200',
                'check' => false,
                'badge' => 'bg-indigo-100 text-indigo-700',
                'icon' => 'fa-paper-plane',
                'label' => 'Diajukan',
            ],
            '_default' => [
                'dot' => 'bg-gray-100 text-gray-400 border-gray-200',
                'line' => 'bg-gray-200',
                'check' => false,
                'badge' => 'bg-gray-100 text-gray-400',
                'icon' => 'fa-minus',
                'label' => 'Draft',
            ],
        ];

        $ujianTypes = [
            ['key' => 'proposal', 'label' => 'Seminar Proposal', 'no' => 1, 'last' => false],
            ['key' => 'hasil', 'label' => 'Seminar Hasil', 'no' => 2, 'last' => false],
            ['key' => 'skripsi', 'label' => 'Ujian Skripsi', 'no' => 3, 'last' => true],
        ];
      @endphp

      <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-5 py-3.5 border-b border-gray-100 flex items-center gap-2">
          <i class="fas fa-clipboard-list text-orange-500 text-sm"></i>
          <h2 class="text-sm font-semibold text-gray-800">Riwayat Ujian</h2>
        </div>

        <div class="px-5 py-4">
          <div class="space-y-4">
            @foreach ($ujianTypes as $type)
              @php
                $ujian = $ujianList->get($type['key']);
                $cfg = $statusCfg[$ujian?->status ?? '_default'] ?? $statusCfg['_default'];
                $jadwal = $ujian?->jadwalUjian;
              @endphp

              <div class="flex gap-4">
                {{-- Dot & connector line --}}
                <div class="flex flex-col items-center">
                  <div
                    class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold shrink-0 border-2 {{ $cfg['dot'] }}">
                    @if ($cfg['check'])
                      <i class="fas fa-check text-xs"></i>
                    @else
                      {{ $type['no'] }}
                    @endif
                  </div>
                  @unless ($type['last'])
                    <div class="w-0.5 flex-1 mt-1 {{ $cfg['line'] }}"></div>
                  @endunless
                </div>

                {{-- Konten --}}
                <div class="flex-1 min-w-0 {{ $type['last'] ? 'pb-1' : 'pb-4' }}">
                  {{-- Header: nama & badge status --}}
                  <div class="flex items-center justify-between gap-3 mb-1.5">
                    <span class="text-sm font-semibold text-gray-800">{{ $type['label'] }}</span>
                    <span
                      class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold shrink-0 {{ $cfg['badge'] }}">
                      <i class="fas {{ $cfg['icon'] }} mr-1"></i>
                      {{ $cfg['label'] }}
                    </span>
                  </div>

                  {{-- Detail jadwal (jika ujian sudah ada) --}}
                  @if ($ujian)
                    @if ($jadwal)
                      <div class="flex flex-wrap gap-x-4 gap-y-1 text-xs text-gray-500 mb-1">
                        <span class="flex items-center gap-1.5">
                          <i class="fas fa-calendar text-gray-400"></i>
                          {{ $jadwal->tanggal_ujian->translatedFormat('d F Y') }}
                        </span>
                        <span class="flex items-center gap-1.5">
                          <i class="fas fa-clock text-gray-400"></i>
                          {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }}
                          – {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }} WITA
                        </span>
                        <span class="flex items-center gap-1.5">
                          <i class="fas fa-map-marker-alt text-gray-400"></i>
                          {{ $jadwal->ruangan }}
                        </span>
                      </div>
                    @else
                      <p class="text-xs text-gray-400 italic mb-1">Jadwal belum ditentukan</p>
                    @endif
                    @if ($ujian->catatan)
                      <p class="text-xs text-gray-500">{{ $ujian->catatan }}</p>
                    @endif
                  @else
                    <p class="text-xs text-gray-400 italic">Belum diajukan</p>
                  @endif
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    @endif
  </div>

@endsection
