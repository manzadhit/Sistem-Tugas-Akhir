@extends('layouts.app')

@section('title', 'Edit Dosen - ' . $dosen->nama_lengkap)

@section('sidebar')
  @include('admin.sidebar')
@endsection

@section('content')

  {{-- Breadcrumb --}}
  <div class="flex items-center gap-2 text-sm text-gray-500 mb-4">
    <a href="{{ route('admin.dosen.index') }}" class="hover:text-blue-600 transition-colors">Kelola Dosen</a>
    <i class="fas fa-chevron-right text-xs text-gray-400"></i>
    <a href="{{ route('admin.dosen.show', $dosen->id) }}"
      class="hover:text-blue-600 transition-colors truncate max-w-[160px]">{{ $dosen->nama_lengkap }}</a>
    <i class="fas fa-chevron-right text-xs text-gray-400"></i>
    <span class="text-gray-800 font-medium">Edit</span>
  </div>

  {{-- Banner --}}
  <div class="bg-gradient-to-br from-blue-700 to-blue-500 rounded-2xl p-5 sm:p-8 mb-6 text-white">
    <div class="flex items-center gap-4">
      <x-avatar :src="$dosen->foto" :initials="$dosen->initials" size="xl" class="!rounded-2xl !bg-white/20 border-2 border-white/30 !text-xl !font-bold !text-white" />
      <div>
        <h1 class="text-xl sm:text-2xl font-bold mb-0.5">Edit Data Dosen</h1>
        <p class="opacity-80 text-sm">{{ $dosen->nama_lengkap }} · NIDN {{ $dosen->nidn }}</p>
      </div>
    </div>
  </div>

  {{-- Flash Messages --}}
  <x-alert type="success" />
  <x-alert type="error" />
  <x-alert type="warning" />

  {{-- Validation errors --}}
  @if ($errors->any())
    <div class="mb-5 bg-red-50 border border-red-200 rounded-xl px-5 py-4">
      <div class="flex items-center gap-2 text-red-700 font-semibold text-sm mb-2">
        <i class="fas fa-circle-exclamation"></i> Terdapat kesalahan input
      </div>
      <ul class="list-disc list-inside space-y-1">
        @foreach ($errors->all() as $e)
          <li class="text-sm text-red-600">{{ $e }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('admin.dosen.update', $dosen->id) }}">
    @csrf
    @method('PUT')

    <div class="space-y-5">

      {{-- Info role --}}
      <div class="flex items-start gap-3 bg-blue-50 border border-blue-200 rounded-xl px-5 py-4">
        <i class="fas fa-circle-info text-blue-500 mt-0.5"></i>
        <div class="text-sm text-blue-800">
          <p class="font-semibold mb-0.5">Perubahan Role</p>
          <p class="text-xs text-blue-600">Role <strong>Kajur</strong> dan <strong>Sekjur</strong> masing-masing
            hanya boleh 1 akun. Ganti role akan langsung mempengaruhi hak akses login dosen.</p>
        </div>
      </div>

      {{-- ██ DATA DOSEN --}}
      <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-5 py-3.5 border-b border-gray-100 flex items-center gap-2">
          <i class="fas fa-id-card text-blue-500 text-sm"></i>
          <h2 class="text-sm font-semibold text-gray-800">Data Dosen</h2>
        </div>
        <div class="px-5 py-5 grid grid-cols-1 sm:grid-cols-2 gap-5">

          {{-- Nama Lengkap --}}
          <div class="sm:col-span-2">
            <label class="block text-xs font-medium text-gray-600 mb-1.5">
              Nama Lengkap <span class="text-red-500">*</span>
            </label>
            <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $dosen->nama_lengkap) }}" required
              class="w-full px-4 py-2.5 border rounded-lg text-sm text-gray-800 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 transition-all @error('nama_lengkap') border-red-400 bg-red-50 @else border-gray-300 @enderror" />
            @error('nama_lengkap')
              <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
          </div>

          {{-- NIDN (readonly) --}}
          <div>
            <label class="block text-xs font-medium text-gray-600 mb-1.5">NIDN</label>
            <div class="relative">
              <input type="text" value="{{ $dosen->nidn }}" disabled
                class="w-full px-4 py-2.5 border border-gray-200 rounded-lg text-sm text-gray-400 bg-gray-50 cursor-not-allowed" />
              <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-400">
                <i class="fas fa-lock"></i>
              </span>
            </div>
            <p class="text-xs text-gray-400 mt-1">NIDN tidak dapat diubah</p>
          </div>

          {{-- Role --}}
          <div>
            <label class="block text-xs font-medium text-gray-600 mb-1.5">
              Role <span class="text-red-500">*</span>
            </label>
            <select name="role" required
              class="w-full pl-3 pr-8 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-800 bg-white focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 transition-all @error('role') border-red-400 bg-red-50 @enderror">
              <option value="dosen" {{ old('role', $dosen->user?->role) === 'dosen' ? 'selected' : '' }}>Dosen</option>
              <option value="kajur" {{ old('role', $dosen->user?->role) === 'kajur' ? 'selected' : '' }}
                {{ $kajurExists ? 'disabled' : '' }}>
                Kajur {{ $kajurExists ? '(sudah terisi)' : '' }}
              </option>
              <option value="sekjur" {{ old('role', $dosen->user?->role) === 'sekjur' ? 'selected' : '' }}
                {{ $sekjurExists ? 'disabled' : '' }}>
                Sekjur {{ $sekjurExists ? '(sudah terisi)' : '' }}
              </option>
            </select>
            @error('role')
              <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
          </div>

          {{-- Jurusan --}}
          <div>
            <label class="block text-xs font-medium text-gray-600 mb-1.5">
              Jurusan <span class="text-red-500">*</span>
            </label>
            <input type="text" name="jurusan" value="{{ old('jurusan', $dosen->jurusan) }}" required
              class="w-full px-4 py-2.5 border rounded-lg text-sm text-gray-800 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 transition-all @error('jurusan') border-red-400 bg-red-50 @else border-gray-300 @enderror" />
            @error('jurusan')
              <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
          </div>

          {{-- Jabatan Fungsional --}}
          <div>
            <label class="block text-xs font-medium text-gray-600 mb-1.5">
              Jabatan Fungsional <span class="text-red-500">*</span>
            </label>
            <select name="jabatan_fungsional" required
              class="w-full pl-3 pr-8 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-800 bg-white focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 transition-all @error('jabatan_fungsional') border-red-400 bg-red-50 @enderror">
              @foreach (['Asisten Ahli', 'Lektor', 'Lektor Kepala', 'Guru Besar', 'Tenaga Pendidik'] as $jabatan)
                <option value="{{ $jabatan }}"
                  {{ old('jabatan_fungsional', $dosen->jabatan_fungsional) === $jabatan ? 'selected' : '' }}>
                  {{ $jabatan }}
                </option>
              @endforeach
            </select>
            @error('jabatan_fungsional')
              <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
          </div>

          {{-- Status --}}
          <div>
            <label class="block text-xs font-medium text-gray-600 mb-1.5">
              Status <span class="text-red-500">*</span>
            </label>
            <select name="status" required
              class="w-full pl-3 pr-8 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-800 bg-white focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 transition-all @error('status') border-red-400 bg-red-50 @enderror">
              @foreach (['aktif' => 'Aktif', 'cuti' => 'Cuti', 'nonaktif' => 'Non-aktif', 'pensiun' => 'Pensiun'] as $val => $lbl)
                <option value="{{ $val }}" {{ old('status', $dosen->status) === $val ? 'selected' : '' }}>
                  {{ $lbl }}
                </option>
              @endforeach
            </select>
            @error('status')
              <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
          </div>

          {{-- No. Telepon --}}
          <div>
            <label class="block text-xs font-medium text-gray-600 mb-1.5">No. Telepon</label>
            <div class="relative">
              <i class="fas fa-phone absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
              <input type="text" name="no_telp" value="{{ old('no_telp', $dosen->no_telp) }}"
                placeholder="08xxxxxxxxxx"
                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-800 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 transition-all @error('no_telp') border-red-400 bg-red-50 @enderror" />
            </div>
            @error('no_telp')
              <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
          </div>

          <div>
            <label class="block text-xs font-medium text-gray-600 mb-1.5">
              SINTA Score 3Yr
            </label>
            <input type="number" name="sinta_score_3y"
              value="{{ old('sinta_score_3y', $dosen->sinta_score_3y ?? 0) }}" min="0" step="0.01"
              placeholder="0.00"
              class="w-full px-4 py-2.5 border rounded-lg text-sm text-gray-800 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 transition-all @error('sinta_score_3y') border-red-400 bg-red-50 @else border-gray-300 @enderror" />
            <p class="mt-1 text-xs text-slate-500">Skor SINTA 3 tahun terakhir.</p>
            @error('sinta_score_3y')
              <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
          </div>

          {{-- Rumpun Ilmu --}}
          <div class="sm:col-span-2">
            <label class="block text-xs font-medium text-gray-600 mb-1.5">
              Rumpun Ilmu
            </label>
            <input type="text" name="rumpun_ilmu" value="{{ old('rumpun_ilmu', $dosen->rumpun_ilmu) }}"
              placeholder="Contoh: Rekayasa Perangkat Lunak (RPL)"
              class="w-full px-4 py-2.5 border rounded-lg text-sm text-gray-800 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 transition-all @error('rumpun_ilmu') border-red-400 bg-red-50 @else border-gray-300 @enderror" />
            @error('rumpun_ilmu')
              <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
          </div>

          <div class="sm:col-span-2">
            <label class="block text-xs font-medium text-gray-600 mb-1.5">
              Mata Kuliah yang Diampu
            </label>
            <x-multi-select name="mata_kuliah_ids" :options="$mataKuliahOptions"
              :selected="old('mata_kuliah_ids', $dosen->mataKuliah->pluck('id')->map(fn ($id) => (string) $id)->all())"
              placeholder="Pilih mata kuliah yang diampu..." search-placeholder="Cari mata kuliah..."
              empty-text="Mata kuliah tidak ditemukan." />
            <p class="mt-2 text-xs text-slate-500">Bisa pilih lebih dari satu mata kuliah.</p>
            @error('mata_kuliah_ids')
              <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
            @enderror
            @error('mata_kuliah_ids.*')
              <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
            @enderror
          </div>

        </div>
      </div>

      {{-- ██ Action Buttons --}}
      <div class="flex items-center justify-between gap-3">
        <a href="{{ route('admin.dosen.index') }}"
          class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-medium border border-gray-300 text-gray-600 hover:bg-gray-50 transition-all">
          <i class="fas fa-arrow-left text-xs"></i> Batal
        </a>
        <div class="flex items-center gap-2">
          {{-- Tombol Hapus --}}
          <button type="button" x-data @click="$dispatch('open-delete-modal')"
            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-semibold border border-red-300 text-red-600 hover:bg-red-50 transition-all">
            <i class="fas fa-trash text-xs"></i> Hapus
          </button>
          <button type="submit"
            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg text-sm font-semibold bg-blue-600 text-white hover:bg-blue-700 transition-all shadow-sm">
            <i class="fas fa-save text-xs"></i> Simpan Perubahan
          </button>
        </div>
      </div>

    </div>
  </form>

  {{-- Modal Konfirmasi Hapus --}}
  <div x-data="{ open: false }" x-on:open-delete-modal.window="open = true" x-show="open" x-transition.opacity
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm" style="display: none;">

    <div x-show="open" x-transition:enter="transition ease-out duration-200"
      x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
      @click.outside="open = false" class="bg-white rounded-2xl shadow-xl p-6 w-full max-w-sm mx-4">

      <div class="flex items-center gap-3 mb-4">
        <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center shrink-0">
          <i class="fas fa-triangle-exclamation text-red-600"></i>
        </div>
        <div>
          <h3 class="text-sm font-bold text-gray-900">Hapus Dosen</h3>
          <p class="text-xs text-gray-500">Tindakan ini tidak dapat dibatalkan</p>
        </div>
      </div>

      <p class="text-sm text-gray-600 mb-5">
        Yakin ingin menghapus akun dosen
        <span class="font-semibold text-gray-900">{{ $dosen->nama_lengkap }}</span>
        (NIDN: <span class="font-semibold">{{ $dosen->nidn }}</span>)?
        Seluruh data terkait juga akan ikut terhapus.
      </p>

      <div class="flex gap-3">
        <button @click="open = false" type="button"
          class="flex-1 px-4 py-2.5 rounded-lg text-sm font-medium border border-gray-300 text-gray-600 hover:bg-gray-50 transition-all">
          Batal
        </button>
        <form method="POST" action="{{ route('admin.dosen.destroy', $dosen->id) }}" class="flex-1">
          @csrf
          @method('DELETE')
          <button type="submit"
            class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg text-sm font-semibold bg-red-600 text-white hover:bg-red-700 transition-all">
            <i class="fas fa-trash text-xs"></i> Ya, Hapus
          </button>
        </form>
      </div>
    </div>
  </div>

@endsection
