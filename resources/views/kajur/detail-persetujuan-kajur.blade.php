@extends('layouts.app')

@section('title', 'Detail Persetujuan Kajur')

@section('sidebar')
  @include('kajur.sidebar')
@endsection

@section('content')
  @php $mahasiswa = $persetujuan->tugasAkhir->mahasiswa; @endphp

  {{-- Breadcrumb --}}
  <nav class="flex items-center gap-2 mb-6 text-sm">
    <a href="{{ route('kajur.persetujuan-kajur.index') }}" class="text-gray-500 hover:text-blue-600 transition-colors">
      <i class="fas fa-clipboard-check"></i> Persetujuan Kajur
    </a>
    <span class="text-gray-400">/</span>
    <span class="text-gray-900 font-medium">Detail</span>
  </nav>

  {{-- Flash Messages --}}
  <x-alert type="success" />
  <x-alert type="error" />

  {{-- Data Mahasiswa --}}
  <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
    <div class="px-6 py-5 border-b border-gray-200 flex items-center gap-3">
      <i class="fas fa-user-graduate text-blue-500 text-xl"></i>
      <h3 class="text-lg font-semibold text-gray-900">Data Mahasiswa</h3>
    </div>
    <div class="p-6">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="flex flex-col gap-1">
          <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Nama Mahasiswa</span>
          <span class="text-base font-semibold text-blue-600">{{ $mahasiswa->nama_lengkap }}</span>
        </div>
        <div class="flex flex-col gap-1">
          <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">NIM</span>
          <span class="text-base text-gray-900">{{ $mahasiswa->nim }}</span>
        </div>
        <div class="flex flex-col gap-1">
          <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Program Studi</span>
        </div>
        <div class="flex flex-col gap-1">
          <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Tahapan</span>
          <span
            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold w-fit
            {{ $persetujuan->tahapan === 'hasil' ? 'bg-purple-100 text-purple-800' : 'bg-indigo-100 text-indigo-800' }}">
            {{ ucfirst($persetujuan->tahapan) }}
          </span>
        </div>
        <div class="flex flex-col gap-1 md:col-span-2">
          <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Judul Tugas Akhir</span>
          <span class="text-base text-gray-900">{{ $persetujuan->tugasAkhir->judul }}</span>
        </div>
        <div class="flex flex-col gap-1 md:col-span-2">
          <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Dosen Pembimbing</span>
          <div class="flex flex-col gap-2 mt-1">
            @foreach ($mahasiswa->dosenPembimbing as $pembimbing)
              <div class="flex items-center gap-3 px-4 py-3 bg-gray-50 rounded-lg">
                <span
                  class="w-6 h-6 bg-blue-500 text-white rounded-full flex items-center justify-center text-xs font-semibold">{{ $loop->iteration }}</span>
                <span class="text-sm text-gray-700">{{ $pembimbing->dosen->nama_lengkap }}</span>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Verifikasi --}}
  <div x-data="{ status: @js($persetujuan->status), showAccModal: false }" class="bg-white rounded-xl shadow-sm overflow-hidden mb-6 border-2"
    :class="status === 'acc' ? 'border-emerald-400' : status === 'reject' ? 'border-red-400' : 'border-amber-400'">
    <div class="px-6 py-5 border-b border-gray-200 flex items-center justify-between flex-wrap gap-3">
      <div class="flex items-center gap-3">
        <i class="fas fa-clipboard-check text-emerald-500 text-xl"></i>
        <h3 class="text-lg font-semibold text-gray-900">Verifikasi Laporan</h3>
      </div>
      <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold"
        :class="{
            'bg-emerald-100 text-emerald-800': status === 'acc',
            'bg-amber-100 text-amber-800': status === 'revisi' || status === 'pending',
            'bg-red-100 text-red-800': status === 'reject'
        }">
        <template x-if="status === 'acc'"><span><i class="fas fa-check-circle"></i> Disetujui</span></template>
        <template x-if="status === 'revisi'"><span><i class="fas fa-pen"></i> Revisi</span></template>
        <template x-if="status === 'reject'"><span><i class="fas fa-times-circle"></i> Ditolak</span></template>
        <template x-if="status === 'pending'"><span><i class="fas fa-exclamation-circle"></i> Perlu
            Verifikasi</span></template>
      </span>
    </div>
    <div class="p-6">
      <div class="flex items-start gap-4 p-4 rounded-lg border"
        :class="status === 'acc' ? 'bg-emerald-50 border-emerald-300' : status === 'reject' ?
            'bg-red-50 border-red-300' : 'bg-amber-50 border-amber-300'">
        <div class="w-8 h-8 rounded-full text-white flex items-center justify-center flex-shrink-0"
          :class="status === 'acc' ? 'bg-emerald-500' : status === 'reject' ? 'bg-red-500' : 'bg-amber-500'">
          <i class="text-sm"
            :class="status === 'acc' ? 'fas fa-check' : status === 'reject' ? 'fas fa-times' : 'fas fa-exclamation'"></i>
        </div>
        <div class="flex-1">
          <div class="text-sm font-semibold text-gray-700 mb-1">File Laporan Tugas Akhir</div>
          <div class="text-xs text-gray-500 leading-relaxed mb-3">
            Dokumen laporan yang diupload mahasiswa dan perlu diverifikasi.
          </div>

          @if ($persetujuan->catatan)
            <div class="mb-3 rounded-lg border bg-white px-3 py-2">
              <div class="mb-1 text-xs font-semibold"><i class="fas fa-comment-dots mr-1"></i> Catatan Mahasiswa</div>
              <p class="text-sm leading-relaxed">{{ $persetujuan->catatan }}</p>
            </div>
          @endif

          @foreach ($persetujuan->kajurSubmissionFiles->where('uploaded_by', 'mahasiswa') as $file)
            <x-file-preview-item :path="$file->file_path" type="kajur-submission-file" :file-id="$file->id" :uploaded-at="$file->created_at" class="rounded-lg mb-3" />
          @endforeach

          {{-- Form Verifikasi (hanya tampil saat pending) --}}
          @if ($persetujuan->status === 'pending')
            <form action="{{ route('kajur.persetujuan-kajur.verify', $persetujuan->id) }}" method="POST"
              enctype="multipart/form-data" class="mt-4">
              @csrf
              @method('put')
              <input type="hidden" name="status" :value="status">

              {{-- Tombol Aksi --}}
              <div x-show="status === 'pending'" class="flex flex-wrap gap-2">
                <x-action-button @click="showAccModal = true" class="bg-emerald-500 hover:bg-emerald-600">
                  <i class="fas fa-check-circle mr-1"></i> ACC
                </x-action-button>
                <x-action-button @click="status = 'revisi'" class="bg-amber-500 hover:bg-amber-600">
                  <i class="fas fa-pen mr-1"></i> Revisi
                </x-action-button>
                <x-action-button @click="status = 'reject'" class="bg-red-500 hover:bg-red-600">
                  <i class="fas fa-times-circle"></i> Tolak
                </x-action-button>
              </div>

              {{-- Modal Konfirmasi ACC --}}
              <div x-show="showAccModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center">
                <div class="fixed inset-0 bg-black/50" @click="showAccModal = false"></div>
                <div class="relative bg-white rounded-xl shadow-xl w-full max-w-md mx-4 p-6" x-transition>
                  <div class="flex flex-col items-center text-center">
                    <div class="w-14 h-14 rounded-full bg-emerald-100 flex items-center justify-center mb-4">
                      <i class="fas fa-check-circle text-emerald-500 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Setujui Laporan?</h3>
                    <p class="text-sm text-gray-500 mb-6">Laporan mahasiswa akan ditandai sebagai disetujui oleh Ketua
                      Jurusan.</p>
                    <div class="flex gap-3 w-full">
                      <button type="button" @click="showAccModal = false"
                        class="flex-1 px-4 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition-all cursor-pointer">
                        Batal
                      </button>
                      <button type="submit" @click="status = 'acc'; showAccModal = false"
                        class="flex-1 px-4 py-2.5 bg-emerald-500 text-white rounded-lg text-sm font-semibold hover:bg-emerald-600 transition-all flex items-center justify-center gap-1.5 cursor-pointer">
                        <i class="fas fa-check"></i> Ya, Setujui
                      </button>
                    </div>
                  </div>
                </div>
              </div>

              {{-- Form Review (Revisi/Tolak) --}}
              <div x-show="status === 'revisi' || status === 'reject'" x-cloak class="p-4 rounded-lg border mt-3"
                :class="status === 'revisi' ? 'bg-amber-50 border-amber-300' : 'bg-red-50 border-red-300'">
                <div class="flex items-center gap-2 text-sm font-semibold mb-3"
                  :class="status === 'revisi' ? 'text-amber-700' : 'text-red-700'">
                  <i :class="status === 'revisi' ? 'fas fa-pen' : 'fas fa-times-circle'"></i>
                  <span x-text="status === 'revisi' ? 'Catatan Revisi' : 'Alasan Penolakan'"></span>
                </div>
                <textarea name="review"
                  class="w-full px-3 py-3 border border-gray-300 rounded-lg text-[0.95rem] resize-y min-h-[80px] transition-colors focus:outline-none focus:border-blue-600 focus:ring-4 focus:ring-blue-100 mb-4"
                  placeholder="Tambahkan catatan review..." :required="status === 'revisi' || status === 'reject'"></textarea>

                {{-- File Upload --}}
                <x-file-upload name="files[]" accept=".pdf,.doc,.docx" :multiple="true"
                  label="Upload File Pendukung (Opsional)" :max-mb="10" class="mb-4" />

                <div class="flex gap-2 justify-end">
                  <button type="button" @click="status = 'pending'"
                    class="px-3 py-1.5 bg-white border border-gray-300 text-gray-600 rounded-lg text-xs font-medium hover:bg-gray-50 transition-all cursor-pointer">
                    Batal
                  </button>
                  <button type="submit"
                    class="px-4 py-1.5 text-white rounded-lg text-xs font-semibold transition-all flex items-center gap-1.5 cursor-pointer"
                    :class="status === 'revisi' ? 'bg-amber-500 hover:bg-amber-600' : 'bg-red-500 hover:bg-red-600'">
                    <i class="fas fa-paper-plane"></i>
                    <span x-text="status === 'revisi' ? 'Kirim Revisi' : 'Tolak Laporan'"></span>
                  </button>
                </div>
              </div>
            </form>
          @endif

          {{-- Read-only saat sudah diverifikasi --}}
          @if ($persetujuan->status === 'acc')
            <div class="mt-4 p-4 bg-emerald-50 border border-emerald-300 rounded-lg">
              <div class="flex items-center gap-2 text-emerald-700 text-sm font-semibold mb-1">
                <i class="fas fa-check-circle"></i> Laporan Disetujui
              </div>
              <p class="text-xs text-emerald-600">Laporan mahasiswa telah diverifikasi dan disetujui oleh Ketua Jurusan.
              </p>
            </div>
          @elseif ($persetujuan->status === 'revisi')
            <div class="mt-4 p-4 bg-amber-50 border border-amber-300 rounded-lg">
              <div class="flex items-center gap-2 text-amber-700 text-sm font-semibold mb-2">
                <i class="fas fa-pen"></i> Catatan Revisi
              </div>
              @if ($persetujuan->review)
                <p class="text-sm text-gray-700 leading-relaxed">{{ $persetujuan->review }}</p>
              @endif
              @foreach ($persetujuan->kajurSubmissionFiles->where('uploaded_by', 'kajur') as $reviewFile)
                <x-file-preview-item :path="$reviewFile->file_path" type="kajur-submission-file" :file-id="$reviewFile->id" :uploaded-at="$reviewFile->created_at" class="rounded-lg mt-3" />
              @endforeach
            </div>
          @elseif ($persetujuan->status === 'reject')
            <div class="mt-4 p-4 bg-red-50 border border-red-300 rounded-lg">
              <div class="flex items-center gap-2 text-red-700 text-sm font-semibold mb-2">
                <i class="fas fa-times-circle"></i> Alasan Penolakan
              </div>
              @if ($persetujuan->review)
                <p class="text-sm text-gray-700 leading-relaxed">{{ $persetujuan->review }}</p>
              @endif
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>

  {{-- Tombol Kembali --}}
  <div class="flex justify-start mt-2">
    <a href="{{ route('kajur.persetujuan-kajur.index') }}"
      class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition-all">
      <i class="fas fa-arrow-left"></i> Kembali ke Daftar
    </a>
  </div>
  @if (session('show_result_modal'))
    <x-result-modal :status="session('show_result_modal')" :href="route('kajur.persetujuan-kajur.index')" />
  @endif
@endsection
