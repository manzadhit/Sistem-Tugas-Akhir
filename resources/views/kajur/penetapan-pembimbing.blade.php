@extends('layouts.app')

@section('title', 'Penetapan Pembimbing')

@section('sidebar')
  @include('kajur.sidebar')
@endsection

@section('content')
  @if (session('success'))
    <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
      {{ session('success') }}
    </div>
  @endif
  <!-- Breadcrumb -->
  <nav class="flex items-center gap-2 mb-6 text-sm">
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
  <div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-2">Penetapan Dosen Pembimbing</h1>
    <p class="text-[15px] text-gray-600">Tetapkan dosen pembimbing untuk tugas akhir mahasiswa</p>
  </div>

  <!-- Data Mahasiswa Card -->
  <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
    <div class="px-6 py-5 border-b border-gray-200">
      <div class="flex items-center gap-3">
        <i class="fas fa-user-graduate text-blue-500 text-xl"></i>
        <h3 class="text-lg font-semibold text-gray-900">Data Mahasiswa</h3>
      </div>
    </div>
    <div class="p-6">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="flex flex-col gap-1">
          <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Nama Mahasiswa</span>
          <span class="text-[15px] font-semibold text-blue-600">{{ $permintaan->mahasiswa->nama_lengkap }}</span>
        </div>
        <div class="flex flex-col gap-1">
          <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">NIM</span>
          <span class="text-[15px] text-gray-900">{{ $permintaan->mahasiswa->nim }}</span>
        </div>
        <div class="flex flex-col gap-1">
          <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Jurusan</span>
          <span class="text-[15px] text-gray-900">{{ $permintaan->mahasiswa->jurusan }}</span>
        </div>
        <div class="flex flex-col gap-1">
          <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Angkatan</span>
          <span
            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 w-fit">{{ $permintaan->mahasiswa->angkatan }}</span>
        </div>
        <div class="flex flex-col gap-1 md:col-span-2">
          <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Judul Tugas Akhir</span>
          <span class="text-[15px] text-gray-900">{{ $permintaan->judul_ta }}</span>
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

  <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6 border-2 {{ $cardBorder }}"
    id="verificationCardPembimbing">
    <div class="px-6 py-5 border-b border-gray-200 flex items-center justify-between flex-wrap gap-3">
      <div class="flex items-center gap-3">
        <i class="fas fa-clipboard-check {{ $iconVerify }} text-xl"></i>
        <h3 class="text-lg font-semibold text-gray-900">Verifikasi Persyaratan</h3>
      </div>
      <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-semibold {{ $badgeClass }}">
        <i class="fas {{ $badgeIcon }}"></i> {{ $badgeText }}
      </span>
    </div>
    <div class="p-6">
      <div class="flex flex-col gap-4">
        <!-- Syarat: Bukti ACC -->
        <div class="flex items-start gap-4 p-4 border rounded-lg {{ $rowBg }}">
          <div
            class="w-8 h-8 rounded-full {{ $iconBg }} text-white flex items-center justify-center flex-shrink-0">
            <i class="fas {{ $iconSymbol }} text-sm"></i>
          </div>
          <div class="flex-1">
            <div class="text-sm font-semibold text-gray-700 mb-1">
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
            <x-file-preview-item :path="$permintaan->bukti_acc_path" :view-url="route('kajur.show-bukti', ['permintaan' => $permintaan->id])"
              :download-url="route('kajur.download-bukti', ['permintaan' => $permintaan->id])" :uploaded-at="$permintaan->created_at"
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

  @if ($permintaan->status == 'pending' && $permintaan->status_verifikasi_bukti == 'disetujui')
    <!-- Form Penetapan Pembimbing -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
      <div class="px-6 py-5 border-b border-gray-200 flex items-center justify-between flex-wrap gap-3">
        <div class="flex items-center gap-3">
          <i class="fas fa-users text-blue-500 text-xl"></i>
          <h3 class="text-lg font-semibold text-gray-900">Rekomendasi Dosen Pembimbing</h3>
        </div>
        <span
          class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-purple-600 to-indigo-600 text-white">
          <i class="fas fa-magic"></i> Auto-Recommended
        </span>
      </div>
      <div class="p-6">
        <form action="{{ route('kajur.tetapkanPembimbing', ['permintaan' => $permintaan->id]) }}" method="POST">
          @csrf
          <!-- Dosen Pembimbing -->
          <div class="mb-5">
            <label class="block text-sm font-semibold text-gray-700 mb-2">
              Dosen Pembimbing <span class="text-red-600">*</span>
              <span class="text-xs text-gray-500 font-normal ml-1">(Direkomendasikan berdasarkan analisis sistem)</span>
            </label>

            <div class="flex flex-col gap-6" id="pembimbingContainer">
              <!-- Pembimbing 1 -->
              @foreach ($dosen as $index => $d)
                <input type="hidden" name="dosen_ids[]" value={{ $d->id }}>
                <div
                  class="bg-gradient-to-br from-green-50 to-emerald-50 border border-green-300 rounded-xl p-5 transition-all"
                  id="pembimbingCard1" data-dosen-id="1">
                  <div class="flex items-center justify-between mb-4 flex-wrap gap-3">
                    <div class="flex items-center gap-3">
                      <span
                        class="w-9 h-9 bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-full flex items-center justify-center text-base font-bold">{{ $index + 1 }}</span>
                      <span class="text-sm font-semibold text-gray-700">Pembimbing {{ $index + 1 }} (Ranking
                        #{{ $index + 1 }})</span>
                    </div>
                    <div class="flex gap-2">
                      <div
                        class="flex flex-col items-center px-3 py-2 rounded-lg bg-gradient-to-br from-green-200 to-green-300 min-w-[90px]">
                        <span class="text-[10px] font-semibold uppercase tracking-wide text-green-900">Skor
                          Rekomendasi</span>
                        <span class="text-lg font-bold text-green-900">0.892</span>
                      </div>
                    </div>
                  </div>

                  <div
                    class="flex items-center justify-between p-4 bg-gradient-to-r from-white to-green-50 border border-green-300 rounded-lg mb-3">
                    <div class="flex items-center gap-3">
                      <div
                        class="w-11 h-11 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-full flex items-center justify-center text-white font-bold text-base">
                        FA</div>
                      <div>
                        <h4 class="text-[15px] font-semibold text-gray-900 mb-0.5">{{ $d->nama_lengkap }}</h4>
                        <div class="text-xs text-gray-600">
                          <span class="mr-3"><i class="fas fa-id-badge"></i> {{ $d->nidn }}</span>
                          <span><i class="fas fa-award"></i> {{ $d->jabatan_fungsional }}</span>
                        </div>
                      </div>
                    </div>
                    <button type="button"
                      class="flex items-center gap-1.5 px-4 py-2 bg-yellow-100 text-yellow-900 border border-yellow-300 rounded-lg text-xs font-semibold hover:bg-yellow-200 hover:border-yellow-400 transition-all"
                      onclick="openGantiModal(1)">
                      <i class="fas fa-exchange-alt text-xs"></i> Ganti
                    </button>
                  </div>
                  <input type="hidden" id="pembimbing1" name="pembimbing1" value="1" required />

                  <!-- Detail Perhitungan -->
                  <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                    <div class="flex items-center gap-2 px-4 py-3 cursor-pointer hover:bg-gray-50 transition-colors"
                      onclick="toggleReason('reason1')">
                      <i class="fas fa-calculator text-purple-600"></i>
                      <span class="text-xs font-semibold text-gray-700">Lihat Detail Perhitungan</span>
                      <i class="fas fa-chevron-down text-xs text-gray-500 ml-auto transition-transform"
                        id="toggle1"></i>
                    </div>
                    <div class="hidden border-t border-gray-200 p-4 bg-gray-50" id="reason1">
                      <div class="mb-4 pb-4 border-b border-dashed border-gray-300">
                        <div class="text-xs font-semibold text-indigo-600 mb-2 flex items-center gap-2">
                          <i class="fas fa-project-diagram text-[11px]"></i> Content-Based Filtering (Cosine Similarity)
                        </div>
                        <div
                          class="flex justify-between items-center px-3 py-2 bg-blue-100 rounded-md text-xs font-semibold">
                          <span class="text-blue-900">Nilai Similarity (CBF)</span>
                          <span class="text-blue-900 text-sm">0.94</span>
                        </div>
                      </div>
                      <div>
                        <div class="text-xs font-semibold text-indigo-600 mb-2 flex items-center gap-2">
                          <i class="fas fa-balance-scale text-[11px]"></i> Multi-Attribute Utility Theory (MAUT)
                        </div>
                        <div class="overflow-x-auto">
                          <table class="w-full text-xs mb-2 bg-white border-collapse">
                            <thead>
                              <tr>
                                <th
                                  class="bg-gray-100 px-2 py-2 text-left font-semibold text-gray-700 border border-gray-200">
                                  Atribut</th>
                                <th
                                  class="bg-gray-100 px-2 py-2 text-center font-semibold text-gray-700 border border-gray-200">
                                  Nilai</th>
                                <th
                                  class="bg-gray-100 px-2 py-2 text-center font-semibold text-gray-700 border border-gray-200">
                                  Bobot</th>
                                <th
                                  class="bg-gray-100 px-2 py-2 text-right font-semibold text-gray-700 border border-gray-200">
                                  Utility</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <td class="px-2 py-2 font-medium border border-gray-200">Similarity (CBF)</td>
                                <td class="px-2 py-2 text-center border border-gray-200">0.94</td>
                                <td class="px-2 py-2 text-center border border-gray-200">0.35</td>
                                <td class="px-2 py-2 text-right border border-gray-200">0.329</td>
                              </tr>
                              <tr>
                                <td class="px-2 py-2 font-medium border border-gray-200">Beban Bimbingan</td>
                                <td class="px-2 py-2 text-center border border-gray-200">0.85</td>
                                <td class="px-2 py-2 text-center border border-gray-200">0.25</td>
                                <td class="px-2 py-2 text-right border border-gray-200">0.213</td>
                              </tr>
                              <tr>
                                <td class="px-2 py-2 font-medium border border-gray-200">Jabatan Fungsional</td>
                                <td class="px-2 py-2 text-center border border-gray-200">0.90</td>
                                <td class="px-2 py-2 text-center border border-gray-200">0.20</td>
                                <td class="px-2 py-2 text-right border border-gray-200">0.180</td>
                              </tr>
                              <tr>
                                <td class="px-2 py-2 font-medium border border-gray-200">Pengalaman Membimbing</td>
                                <td class="px-2 py-2 text-center border border-gray-200">0.85</td>
                                <td class="px-2 py-2 text-center border border-gray-200">0.20</td>
                                <td class="px-2 py-2 text-right border border-gray-200">0.170</td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                        <div
                          class="flex justify-between items-center px-3 py-2 bg-gradient-to-br from-green-200 to-green-300 rounded-md text-xs font-semibold">
                          <span class="text-green-900">Total Skor MAUT</span>
                          <span class="text-green-900 text-base">0.892</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
          </div>


          <!-- Button Group -->
          <div class="flex gap-4 justify-end pt-6 border-t border-gray-200">
            <a href="{{ route('kajur.permintaan-pembimbing') }}"
              class="flex items-center gap-2 px-6 py-3 bg-white border border-gray-300 text-gray-700 rounded-lg text-[15px] font-medium hover:bg-gray-50 hover:border-gray-400 transition-all">
              <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <button type="submit"
              class="flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg text-[15px] font-medium hover:shadow-lg hover:-translate-y-0.5 transition-all">
              <i class="fas fa-check"></i> Tetapkan Pembimbing
            </button>
          </div>
        </form>
      </div>
    </div>
  @endif

@endsection
