@extends('layouts.app')

@section('title', 'Penetapan Pembimbing')

@section('sidebar')
  @include('kajur.sidebar')
@endsection

@section('content')

  <!-- Breadcrumb -->
  <nav class="flex items-center gap-1.5 mb-4 text-xs sm:gap-2 sm:mb-6 sm:text-sm">
    <a href="{{ route('kajur.dashboard') }}" class="text-gray-500 hover:text-blue-600 transition-colors">
      <i class="fas fa-home"></i>
    </a>
    <span class="text-gray-400">/</span>
    <a href="{{ route('kajur.permintaan-pembimbing') }}"
      class="text-gray-500 hover:text-blue-600 transition-colors">Permintaan
      Pembimbing</a>
    <span class="text-gray-400">/</span>
    <span class="text-gray-900 font-medium">Penetapan Pembimbing</span>
  </nav>

  <!-- Page Header -->
  <div class="mb-6 sm:mb-8">
    <h1 class="text-2xl font-bold text-gray-900 mb-1 sm:text-3xl sm:mb-2">Penetapan Dosen Pembimbing</h1>
    <p class="text-sm text-gray-600 sm:text-[15px]">Tetapkan dosen pembimbing untuk tugas akhir mahasiswa</p>
  </div>

  <!-- Flash Messages -->
  <x-alert type="error" />
  <x-alert type="warning" />


  <!-- Data Mahasiswa Card -->
  <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
    <div class="px-4 py-3.5 border-b border-gray-200 sm:px-6 sm:py-5">
      <div class="flex items-center gap-3">
        <i class="fas fa-user-graduate text-blue-500 text-xl"></i>
        <h3 class="text-base font-semibold text-gray-900 sm:text-lg">Data Mahasiswa</h3>
      </div>
    </div>
    <div class="p-4 sm:p-6">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
        <div class="flex flex-col gap-1">
          <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Nama Mahasiswa</span>
          <span
            class="text-sm font-semibold text-blue-600 sm:text-[15px]">{{ $permintaan->mahasiswa->nama_lengkap }}</span>
        </div>
        <div class="flex flex-col gap-1">
          <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">NIM</span>
          <span class="text-sm text-gray-900 sm:text-[15px]">{{ $permintaan->mahasiswa->nim }}</span>
        </div>
        <div class="flex flex-col gap-1">
          <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Jurusan</span>
          <span class="text-sm text-gray-900 sm:text-[15px]">{{ $permintaan->mahasiswa->jurusan }}</span>
        </div>
        <div class="flex flex-col gap-1">
          <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Angkatan</span>
          <span
            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 w-fit">{{ $permintaan->mahasiswa->angkatan }}</span>
        </div>
        <div class="flex flex-col gap-1 md:col-span-2">
          <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Judul Tugas Akhir</span>
          <span class="text-sm text-gray-900 sm:text-[15px]">{{ $permintaan->judul_ta }}</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Verifikasi Persyaratan Card -->
  @php
    $statusBukti = $permintaan->status_verifikasi_bukti;

    $cardBorder = match ($statusBukti) {
        'disetujui' => 'border-green-400',
        'ditolak' => 'border-red-400',
        default => 'border-yellow-400',
    };
    $badgeClass = match ($statusBukti) {
        'disetujui' => 'bg-green-100 text-green-800',
        'ditolak' => 'bg-red-100 text-red-800',
        default => 'bg-yellow-100 text-yellow-800',
    };
    $badgeIcon = match ($statusBukti) {
        'disetujui' => 'fa-check-circle',
        'ditolak' => 'fa-times-circle',
        default => 'fa-exclamation-circle',
    };
    $badgeText = match ($statusBukti) {
        'disetujui' => 'Terverifikasi',
        'ditolak' => 'Ditolak',
        default => 'Perlu Verifikasi',
    };
    $iconBg = match ($statusBukti) {
        'disetujui' => 'bg-green-500',
        'ditolak' => 'bg-red-500',
        default => 'bg-yellow-500',
    };
    $iconSymbol = match ($statusBukti) {
        'disetujui' => 'fa-check',
        'ditolak' => 'fa-times',
        default => 'fa-exclamation',
    };
    $rowBg = match ($statusBukti) {
        'disetujui' => 'bg-green-50 border-green-200',
        'ditolak' => 'bg-red-50 border-red-200',
        default => 'bg-yellow-50 border-yellow-200',
    };
    $iconVerify = match ($statusBukti) {
        'disetujui' => 'text-green-500',
        'ditolak' => 'text-red-500',
        default => 'text-yellow-500',
    };
  @endphp

  <x-alert type="success" />

  <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6 border-2 {{ $cardBorder }}"
    id="verificationCardPembimbing">
    <div class="px-4 py-3.5 border-b border-gray-200 flex items-center justify-between flex-wrap gap-3 sm:px-6 sm:py-5">
      <div class="flex items-center gap-3">
        <i class="fas fa-clipboard-check {{ $iconVerify }} text-xl"></i>
        <h3 class="text-base font-semibold text-gray-900 sm:text-lg">Verifikasi Persyaratan</h3>
      </div>
      <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-semibold {{ $badgeClass }}">
        <i class="fas {{ $badgeIcon }}"></i> {{ $badgeText }}
      </span>
    </div>
    <div class="p-3 sm:p-6">
      <div class="flex flex-col gap-4">
        <!-- Syarat: Bukti ACC -->
        <div class="flex items-start gap-3 p-3 border rounded-lg {{ $rowBg }} sm:gap-4 sm:p-4">
          <div
            class="hidden w-8 h-8 rounded-full {{ $iconBg }} text-white items-center justify-center flex-shrink-0 sm:flex">
            <i class="fas {{ $iconSymbol }} text-sm"></i>
          </div>
          <div class="flex-1 min-w-0">
            <div class="text-[13px] font-semibold text-gray-700 mb-1 sm:text-sm">
              Bukti Persetujuan (ACC) Judul Tugas Akhir
            </div>
            <div class="text-xs text-gray-600 leading-relaxed mb-3">
              @if ($statusBukti === 'disetujui')
                Dokumen bukti ACC telah diverifikasi dan disetujui.
              @elseif ($statusBukti === 'ditolak')
                Dokumen bukti ACC ditolak. Lihat alasan penolakan di bawah.
              @else
                Dokumen bukti ACC dari koordinator program studi telah diupload mahasiswa dan perlu diverifikasi.
              @endif
            </div>

            <!-- File Preview -->
            <x-file-preview-item :path="$permintaan->bukti_acc_path" type="permintaan-pembimbing" :file-id="$permintaan->id" :uploaded-at="$permintaan->created_at"
              class="rounded-lg mb-3" />

            {{-- Form verifikasi: hanya muncul saat pending --}}
            @if ($statusBukti === 'pending')
              <form action="{{ route('kajur.verify-bukti', ['permintaan' => $permintaan->id]) }}" method="POST"
                x-data="{ status: 'disetujui' }">
                @csrf
                @method('PUT')

                <div class="flex gap-x-4">
                  <label class="flex items-center gap-x-1 cursor-pointer">
                    <input type="radio" name="status" value="disetujui" x-model="status"
                      class="text-green-600 focus:ring-0">
                    <span class="text-sm">Terima</span>
                  </label>

                  <label class="flex items-center gap-x-1 cursor-pointer">
                    <input type="radio" name="status" value="ditolak" x-model="status"
                      class="text-red-600 focus:ring-0">
                    <span class="text-sm">Tolak</span>
                  </label>
                </div>

                {{-- textarea muncul hanya jika Tolak --}}
                <div x-show="status === 'ditolak'" x-transition class="mt-4">
                  <label class="block text-sm font-medium text-gray-700 mb-1">
                    Alasan Penolakan
                  </label>
                  <textarea name="alasan" rows="3" class="w-full rounded-md border-gray-300 focus:ring-0 focus:border-blue-500"
                    placeholder="Masukkan alasan penolakan..."></textarea>
                </div>

                <div class="flex justify-end mt-4">
                  <x-primary-button>Submit</x-primary-button>
                </div>
              </form>

              {{-- Hasil: ditolak → tampilkan alasan --}}
            @elseif ($statusBukti === 'ditolak')
              <div class="p-3 bg-red-50 border border-red-200 rounded-lg">
                <div class="text-xs font-semibold text-red-700 mb-1">
                  <i class="fas fa-comment-alt mr-1"></i> Alasan Penolakan:
                </div>
                <p class="text-sm text-red-600">{{ $permintaan->catatan ?? '-' }}</p>
              </div>

              {{-- Hasil: disetujui → tampilkan konfirmasi --}}
            @else
              <div class="p-3 bg-green-50 border border-green-200 rounded-lg flex items-center gap-2">
                <i class="fas fa-check-circle text-green-600"></i>
                <span class="text-sm text-green-700 font-medium">Dokumen telah diverifikasi dan disetujui.</span>
              </div>
            @endif

          </div>
        </div>
      </div>
    </div>
  </div>

  @if ($permintaan->status == 'pending' && $permintaan->status_verifikasi_bukti === 'pending')
    {{-- Petunjuk: rekomendasi akan muncul setelah verifikasi --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6 border border-gray-200">
      <div class="px-4 py-3.5 border-b border-gray-200 flex items-center justify-between sm:px-6 sm:py-5">
        <div class="flex items-center gap-3">
          <i class="fas fa-users text-gray-300 text-xl"></i>
          <h3 class="text-base font-semibold text-gray-400 sm:text-lg">Rekomendasi Dosen Pembimbing</h3>
        </div>
        <span
          class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-400">
          <i class="fas fa-lock text-[10px]"></i> Menunggu Verifikasi
        </span>
      </div>
      <div class="p-4 sm:p-6">
        {{-- Skeleton cards --}}
        <div class="space-y-4 animate-pulse">
          @for ($i = 0; $i < 2; $i++)
            <div class="border border-gray-200 rounded-xl p-4">
              <div class="flex items-center gap-3 mb-3">
                <div class="w-7 h-7 bg-gray-200 rounded-full sm:w-9 sm:h-9"></div>
                <div class="h-3 bg-gray-200 rounded w-40"></div>
              </div>
              <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                <div class="w-9 h-9 bg-gray-200 rounded-full sm:w-11 sm:h-11"></div>
                <div class="flex-1 space-y-2">
                  <div class="h-3.5 bg-gray-200 rounded w-48"></div>
                  <div class="h-2.5 bg-gray-200 rounded w-64"></div>
                </div>
              </div>
            </div>
          @endfor
        </div>
        <p class="text-center text-xs text-gray-400 mt-4">
          <i class="fas fa-info-circle mr-1"></i>
          Hasil rekomendasi akan muncul setelah verifikasi bukti ACC disetujui.
        </p>
      </div>
    </div>
  @endif

  @if ($permintaan->status == 'pending' && $permintaan->status_verifikasi_bukti == 'disetujui')
    <!-- Form Penetapan Pembimbing -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
      <div
        class="px-4 py-3.5 border-b border-gray-200 flex items-center justify-between flex-wrap gap-3 sm:px-6 sm:py-5">
        <div class="flex items-center gap-3">
          <i class="fas fa-users text-blue-500 text-xl"></i>
          <h3 class="text-base font-semibold text-gray-900 sm:text-lg">Rekomendasi Dosen Pembimbing</h3>
        </div>
        <span
          class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-purple-600 to-indigo-600 text-white">
          <i class="fas fa-magic"></i> Auto-Recommended
        </span>
      </div>
      <div class="p-3 sm:p-6">
        {{-- Alpine state untuk pengelolaan dosen terpilih + modal pengganti --}}
        <form x-data="pembimbingHandler(
            @js($rankedDosens->values()),
            @js($unrankedDosens->values()),
            @js($rankedDosens->take(2)->values())
        )"
          action="{{ route('kajur.tetapkanPembimbing', ['permintaan' => $permintaan->id]) }}" method="POST">
          @csrf
          <!-- Dosen Pembimbing -->
          <div class="mb-5">
            <label class="block text-sm font-semibold text-gray-700 mb-2">
              Dosen Pembimbing <span class="text-red-600">*</span>
              <span class="hidden text-xs text-gray-500 font-normal ml-1 sm:inline">(Direkomendasikan berdasarkan
                analisis sistem)</span>
            </label>

            <div class="flex flex-col gap-4 sm:gap-6" id="pembimbingContainer">
              <template x-for="dosen in selected" :key="dosen.id">
                <input type="hidden" name="dosen_ids[]" :value="dosen.id">
              </template>

              {{-- Render card dosen terpilih (otomatis update setelah proses ganti) --}}
              <template x-for="(dosen, index) in selected" :key="dosen.id">
                <div
                  class="bg-gradient-to-br from-green-50 to-emerald-50 border border-green-300 rounded-xl p-3 transition-all sm:p-5">
                  <div class="flex items-center justify-between mb-3 gap-2 sm:mb-4 sm:gap-3">
                    <div class="flex min-w-0 items-center gap-2 sm:gap-3">
                      <span
                        class="w-7 h-7 bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-full flex items-center justify-center text-sm font-bold sm:w-9 sm:h-9 sm:text-base"
                        x-text="index + 1"></span>
                      <div class="min-w-0 truncate text-xs font-semibold text-gray-700 sm:text-sm">
                        <template x-if="dosen.has_detail">
                          <span>Pembimbing <span x-text="index + 1"></span> (Ranking
                            #<span x-text="dosen.rank"></span>)</span>
                        </template>
                        <template x-if="!dosen.has_detail">
                          <span>Pembimbing <span x-text="index + 1"></span></span>
                        </template>
                      </div>
                    </div>
                    <template x-if="dosen.has_detail">
                      <div class="flex gap-2">
                        <div
                          class="flex flex-col items-center px-2 py-1.5 rounded-lg bg-gradient-to-br from-green-200 to-green-300 min-w-[70px] sm:px-3 sm:py-2 sm:min-w-[90px]">
                          <span
                            class="text-[9px] font-semibold uppercase tracking-wide text-green-900 sm:text-[10px]">Skor</span>
                          <span class="text-base font-bold text-green-900 sm:text-lg"
                            x-text="formatScore(dosen.total_score)"></span>
                        </div>
                      </div>
                    </template>
                  </div>

                  <div
                    class="flex flex-col gap-3 p-3 bg-gradient-to-r from-white to-green-50 border border-green-300 rounded-lg mb-3 sm:flex-row sm:items-center sm:justify-between sm:gap-0 sm:p-4">
                    <div class="flex items-center gap-3">
                      <div
                        class="w-9 h-9 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-sm sm:w-11 sm:h-11 sm:text-base">
                        <span x-text="dosen.initials"></span>
                      </div>
                      <div>
                        <h4 class="text-sm font-semibold text-gray-900 mb-0.5 sm:text-[15px]"
                          x-text="dosen.nama_lengkap"></h4>
                        <div class="flex flex-wrap gap-x-3 gap-y-0.5 text-[11px] text-gray-600 sm:text-xs">
                          <span><i class="fas fa-id-badge"></i> <span x-text="dosen.nidn"></span></span>
                          <span><i class="fas fa-award"></i> <span x-text="dosen.jabatan_fungsional"></span></span>
                          <span><i class="fas fa-users"></i> <span x-text="dosen.total_bimbingan_aktif"></span>
                            Bimbingan Aktif</span>

                        </div>
                      </div>
                    </div>
                    <button type="button"
                      class="flex w-full items-center justify-center gap-1.5 px-4 py-2 bg-yellow-100 text-yellow-900 border border-yellow-300 rounded-lg text-xs font-semibold hover:bg-yellow-200 hover:border-yellow-400 transition-all sm:w-auto"
                      @click="openModal(index)">
                      <i class="fas fa-exchange-alt text-xs"></i> Ganti
                    </button>
                  </div>

                  <!-- Detail perhitungan hanya untuk dosen yang punya hasil MAUT -->
                  <template x-if="dosen.has_detail">
                    <div x-cloak class="bg-white border border-gray-200 rounded-lg overflow-hidden"
                      x-data="{ open: false }">
                      <div
                        class="flex items-center gap-2 px-3 py-2.5 cursor-pointer hover:bg-gray-50 transition-colors sm:px-4 sm:py-3"
                        @click="open = !open">
                        <i class="fas fa-calculator text-purple-600"></i>
                        <span class="text-xs font-semibold text-gray-700">Lihat Detail Perhitungan</span>
                        <i class="fas fa-chevron-down text-xs text-gray-500 ml-auto transition-transform duration-200"
                          :class="{ 'rotate-180': open }"></i>
                      </div>
                      <div x-show="open" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 -translate-y-1"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 -translate-y-1"
                        class="border-t border-gray-200 p-3 bg-gray-50 sm:p-4">
                        <div>
                          {{-- CBF Section --}}
                          <div class="mb-4 pb-4 border-b border-dashed border-gray-300">
                            <div class="text-xs font-semibold text-indigo-600 mb-2 flex items-center gap-2">
                              <i class="fas fa-project-diagram text-[11px]"></i> Content-Based Filtering (Cosine
                              Similarity)
                            </div>
                            <div
                              class="flex justify-between items-center px-3 py-2 bg-blue-100 rounded-md text-xs font-semibold">
                              <span class="text-blue-900">Nilai Similarity (CBF)</span>
                              <span class="text-blue-900 text-sm" x-text="formatScore(dosen.similarity_score)"></span>
                            </div>
                          </div>

                          {{-- MAUT Section --}}
                          <div>
                            <div class="text-xs font-semibold text-indigo-600 mb-2 flex items-center gap-2">
                              <i class="fas fa-balance-scale text-[11px]"></i> Multi-Attribute Utility Theory (MAUT)
                            </div>
                            <div class="overflow-x-auto">
                              <table class="w-full text-[11px] mb-2 bg-white border-collapse sm:text-xs">
                                <thead>
                                  <tr>
                                    <th
                                      class="bg-gray-100 px-2 py-2 text-left font-semibold text-gray-700 border border-gray-200">
                                      Atribut</th>
                                    <th
                                      class="bg-gray-100 px-2 py-2 text-center font-semibold text-gray-700 border border-gray-200">
                                      Nilai Ternormalisasi</th>
                                    <th
                                      class="bg-gray-100 px-2 py-2 text-center font-semibold text-gray-700 border border-gray-200">
                                      Bobot</th>
                                    <th
                                      class="bg-gray-100 px-2 py-2 text-right font-semibold text-gray-700 border border-gray-200">
                                      Utility</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <template x-for="criterion in dosen.criteria_details"
                                    :key="criterion.key + '-' + criterion.label">
                                    <tr>
                                      <td class="px-2 py-2 font-medium border border-gray-200" x-text="criterion.label">
                                      </td>
                                      <td class="px-2 py-2 text-center border border-gray-200"
                                        x-text="formatScore(criterion.normalized_value)"></td>
                                      <td class="px-2 py-2 text-center border border-gray-200"
                                        x-text="criterion.weight">
                                      </td>
                                      <td class="px-2 py-2 text-right border border-gray-200"
                                        x-text="formatScore(criterion.utility_score)"></td>
                                    </tr>
                                  </template>
                                </tbody>
                              </table>
                            </div>
                            <div
                              class="flex justify-between items-center px-3 py-2 bg-gradient-to-br from-green-200 to-green-300 rounded-md text-xs font-semibold">
                              <span class="text-green-900">Total Skor MAUT</span>
                              <span class="text-green-900 text-base" x-text="formatScore(dosen.total_score)"></span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </template>
                </div>
              </template>
            </div>
          </div>

          <!-- Button Group -->
          <div
            class="flex flex-col-reverse gap-3 pt-4 border-t border-gray-200 sm:flex-row sm:gap-4 sm:justify-end sm:pt-6">
            <a href="{{ route('kajur.permintaan-pembimbing') }}"
              class="flex items-center justify-center gap-2 px-4 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 hover:border-gray-400 transition-all sm:px-6 sm:py-3 sm:text-[15px]">
              <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <button type="button" @click="showModal = true"
              class="flex items-center justify-center gap-2 px-4 py-2.5 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg text-sm font-medium hover:shadow-lg hover:-translate-y-0.5 transition-all sm:px-6 sm:py-3 sm:text-[15px]">
              <i class="fas fa-check"></i> Tetapkan Pembimbing
            </button>
          </div>

          <x-modal-confirm model="showModal" title="Konfirmasi Penetapan Pembimbing"
            confirmText="Ya, Tetapkan Pembimbing" theme="blue">
            <p class="text-sm text-gray-500">
              Apakah Anda yakin dosen pembimbing yang dipilih sudah sesuai? Penetapan ini akan menyimpan pembimbing 1
              dan pembimbing 2 untuk mahasiswa terkait.
            </p>
          </x-modal-confirm>

          <!-- Modal ganti dosen: pilih kandidat dulu, lalu konfirmasi di footer -->
          <div x-show="activeIndex !== null" x-cloak
            class="flex fixed inset-0 bg-black/50 items-center justify-center z-[1000] p-4">
            <div @click.outside="closeModal()"
              class="bg-white rounded-2xl w-full max-w-xl max-h-[90vh] overflow-hidden shadow-2xl"
              style="animation: modalSlideIn 0.3s ease-out">

              <!-- Modal Header -->
              <div class="px-4 py-3.5 border-b border-gray-200 flex items-center justify-between sm:px-6 sm:py-5">
                <h3 class="text-base font-bold text-gray-900 flex items-center gap-2 sm:text-lg">
                  <i class="fas fa-exchange-alt"></i> Ganti Dosen Pembimbing
                  <span class="text-sm font-semibold text-blue-700"
                    x-text="activeIndex !== null ? '(Pembimbing ' + (activeIndex + 1) + ')' : ''"></span>
                </h3>
                <button type="button"
                  class="bg-transparent border-none text-xl text-gray-500 cursor-pointer p-1 hover:text-gray-900 transition-colors"
                  @click="closeModal()">
                  <i class="fas fa-times"></i>
                </button>
              </div>

              <!-- Modal Body -->
              <div class="px-4 py-4 max-h-[60vh] overflow-y-auto sm:px-6 sm:py-6">
                <div class="mb-4">
                  <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="text" name="dosenSearch" x-model.trim="searchQuery"
                      placeholder="Cari nama, NIDN, atau bidang..."
                      class="w-full rounded-lg border border-gray-300 pl-10 pr-4 py-2.5 text-sm focus:border-blue-500 focus:ring-0">
                  </div>
                </div>

                <div class="flex flex-col gap-2" id="dosenList">
                  {{-- Ranked ditampilkan di atas, unranked di bawah --}}
                  <div class="px-1 pb-1 text-[11px] font-semibold uppercase tracking-wide text-gray-500">Rekomendasi
                  </div>
                  <template x-for="dosen in filteredAvailableRanked" :key="'ranked-' + dosen.id">
                    <button type="button" @click="selectCandidate(dosen)"
                      class="w-full text-left relative flex items-center gap-3 p-3 bg-white border rounded-xl cursor-pointer transition-all hover:shadow-md hover:border-blue-300 hover:-translate-y-0.5 sm:gap-4 sm:p-4"
                      :class="isCandidateSelected(dosen.id) ? 'border-blue-400 ring-2 ring-blue-100' : 'border-gray-200'">
                      <div
                        class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center text-white font-semibold text-xs flex-shrink-0 sm:w-10 sm:h-10 sm:text-sm"
                        x-text="dosen.initials"></div>

                      <div class="flex-1 min-w-0">
                        <h5 class="text-sm font-semibold text-gray-900 truncate" x-text="dosen.nama_lengkap"></h5>
                        <p class="text-[0.7rem] text-gray-500 truncate">
                          NIDN: <span x-text="dosen.nidn || '-' "></span>
                        </p>
                        <p class="text-[0.7rem] text-gray-500 truncate">
                          <span x-text="dosen.keahlian"></span>
                          <span>·</span>
                          <span x-text="dosen.total_bimbingan_aktif"></span> <span>bimbingan aktif</span>
                        </p>
                      </div>

                      <div
                        class="flex-shrink-0 w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center sm:w-7 sm:h-7">
                        <span class="text-[0.6rem] font-bold text-blue-600 sm:text-[0.65rem]"
                          x-text="'#' + dosen.rank"></span>
                      </div>

                      <div class="flex-shrink-0 text-right">
                        <p class="text-[0.55rem] text-gray-400 uppercase tracking-wide sm:text-[0.6rem]">Skor</p>
                        <p class="text-xs font-bold text-emerald-500 sm:text-sm"
                          x-text="formatScore(dosen.total_score)"></p>
                      </div>
                    </button>
                  </template>

                  <div class="mt-3 px-1 pb-1 text-[11px] font-semibold uppercase tracking-wide text-gray-500">Lainnya
                  </div>
                  <template x-for="dosen in filteredAvailableUnranked" :key="'unranked-' + dosen.id">
                    <button type="button" @click="selectCandidate(dosen)"
                      class="w-full text-left flex items-center justify-between p-3 border rounded-lg cursor-pointer transition-all hover:bg-blue-50 hover:border-blue-300 sm:p-4"
                      :class="isCandidateSelected(dosen.id) ? 'bg-blue-50 border-blue-300 ring-2 ring-blue-100' :
                          'bg-gray-50 border-gray-200'">
                      <div class="flex items-center gap-3">
                        <div
                          class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center text-white font-semibold text-xs flex-shrink-0 sm:w-10 sm:h-10 sm:text-sm"
                          x-text="dosen.initials"></div>
                        <div>
                          <h5 class="text-[0.9rem] font-semibold text-gray-900 mb-0.5" x-text="dosen.nama_lengkap"></h5>
                          <p class="text-[0.7rem] text-gray-500">
                            NIDN: <span x-text="dosen.nidn || '-' "></span>
                          </p>
                          <p class="text-[0.7rem] text-gray-500">
                            <span x-text="dosen.keahlian"></span>
                            <span>·</span>
                            <span x-text="dosen.total_bimbingan_aktif"></span> <span>bimbingan aktif</span>
                          </p>
                        </div>
                      </div>
                    </button>
                  </template>

                  <template x-if="filteredAvailableRanked.length === 0 && filteredAvailableUnranked.length === 0">
                    <div class="rounded-lg border border-gray-200 bg-gray-50 px-4 py-3 text-xs text-gray-500">
                      Dosen tidak ditemukan. Coba kata kunci lain.
                    </div>
                  </template>
                </div>
              </div>

              <!-- Modal Footer -->
              <div class="px-4 py-3 border-t border-gray-200 flex justify-end gap-3 sm:px-6 sm:py-4">
                <button type="button"
                  class="px-5 py-2.5 bg-white border border-gray-300 rounded-lg text-gray-700 text-sm font-medium cursor-pointer hover:bg-gray-50 hover:border-gray-400 transition-all"
                  @click="closeModal()">
                  Batal
                </button>
                <button type="button"
                  class="px-5 py-2.5 rounded-lg text-sm font-medium flex items-center gap-2 transition-all"
                  :class="candidateDosen ?
                      'bg-gradient-to-br from-blue-500 to-blue-700 text-white hover:-translate-y-px hover:shadow-lg hover:shadow-blue-300/50' :
                      'bg-gray-200 text-gray-500 cursor-not-allowed'"
                  :disabled="!candidateDosen" @click="confirmSelectedDosen()">
                  <i class="fas fa-check"></i> Pilih Dosen
                </button>
              </div>

            </div>
          </div>
        </form>
      </div>
    </div>
  @endif


  {{-- Success Modal --}}
  @if (session('show_success_modal'))
    <x-result-modal status="success" title="Pembimbing Berhasil Ditetapkan!"
      desc="Dosen pembimbing telah ditetapkan dan mahasiswa akan mendapatkan notifikasi." :href="route('kajur.permintaan-pembimbing')" />
  @endif

  @if (session('show_result_modal'))
    <x-result-modal :status="session('show_result_modal')" title="Bukti ACC Ditolak!"
      desc="Penolakan bukti ACC berhasil disimpan dan mahasiswa akan mendapatkan notifikasi." :href="route('kajur.permintaan-pembimbing')" />
  @endif

@endsection

@push('scripts')
  <script>
    function pembimbingHandler(rankedDosens, unrankedDosens, initialSelected) {
      return {
        // Data master dari backend
        rankedDosens,
        unrankedDosens,

        // State utama UI
        selected: [...initialSelected],
        showModal: false,

        activeIndex: null,
        candidateDosen: null,
        searchQuery: '',

        openModal(index) {
          this.activeIndex = index;
          this.candidateDosen = null;
          this.searchQuery = '';
        },

        closeModal() {
          this.activeIndex = null;
          this.candidateDosen = null;
          this.searchQuery = '';
        },

        selectCandidate(dosen) {
          this.candidateDosen = dosen;
        },

        isCandidateSelected(dosenId) {
          return this.candidateDosen?.id === dosenId;
        },

        pilihDosen(dosen) {
          this.selected[this.activeIndex] = dosen;
          this.closeModal();
        },

        // Apply kandidat yang dipilih user di modal
        confirmSelectedDosen() {
          if (!this.candidateDosen || this.activeIndex === null) {
            return;
          }

          this.pilihDosen(this.candidateDosen);
        },

        formatScore(value) {
          return Number(value ?? 0).toFixed(2);
        },

        // Search sederhana berbasis nama, NIDN, dan bidang keahlian
        matchesSearch(dosen) {
          const keyword = this.searchQuery.toLowerCase();
          if (!keyword) {
            return true;
          }

          const haystack = [
              dosen.nama_lengkap,
              dosen.nidn,
              dosen.keahlian,
            ]
            .filter(Boolean)
            .join(' ')
            .toLowerCase();

          return haystack.includes(keyword);
        },

        get availableRanked() {
          return this.rankedDosens.filter(d =>
            !this.selected.some(s => s.id === d.id)
          );
        },

        get filteredAvailableRanked() {
          return this.availableRanked.filter(d => this.matchesSearch(d));
        },

        get availableUnranked() {
          return this.unrankedDosens.filter(d =>
            !this.selected.some(s => s.id === d.id)
          );
        },

        get filteredAvailableUnranked() {
          return this.availableUnranked.filter(d => this.matchesSearch(d));
        }
      }
    }
  </script>

  <script>
    // Simpan posisi scroll saat form disubmit, restore setelah reload
    document.querySelectorAll('form').forEach(form => {
      form.addEventListener('submit', () => {
        sessionStorage.setItem('scrollY', window.scrollY);
      });
    });

    const savedScroll = sessionStorage.getItem('scrollY');
    if (savedScroll) {
      window.scrollTo(0, parseInt(savedScroll));
      sessionStorage.removeItem('scrollY');
    }
  </script>
@endpush
