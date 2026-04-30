{{-- Tampilan saat KajurSubmission status = 'acc' (penguji sudah ditetapkan) --}}
<div class="mb-8 overflow-hidden rounded-xl bg-white shadow-sm">
  <!-- Header -->
  <div class="border-b border-slate-200 bg-slate-50 px-8 py-8 text-center">
    <div class="mx-auto mb-4 flex h-20 w-20 items-center justify-center rounded-full bg-blue-600 text-white">
      <i class="fas fa-user-check text-4xl"></i>
    </div>
    <h3 class="mb-2 text-2xl font-bold text-slate-900">Penguji Telah Ditetapkan!</h3>
    <p class="text-[1rem] text-slate-500">
      Ketua Jurusan telah menyetujui dan menetapkan dosen penguji Anda
    </p>
  </div>

  <!-- Body -->
  <div class="p-8">
    <!-- Dosen Penguji Grid -->
    <div class="mb-2">
      <label class="mb-4 flex items-center gap-2 text-lg font-semibold text-gray-800">
        <i class="fas fa-users text-blue-600"></i> Dosen Penguji
      </label>

      <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
        @foreach ($dosenPenguji as $penguji)
          @php
            $num = preg_replace('/[^0-9]/', '', $penguji->jenis_penguji);
            $dosen = $penguji->dosen;
            $words = explode(' ', $dosen->nama_lengkap);
            $initials = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
          @endphp

          <div
            class="relative overflow-hidden rounded-xl border-2 border-gray-200 bg-white p-6 transition-all duration-300 hover:-translate-y-0.5 hover:border-blue-500 hover:shadow-[0_10px_15px_-3px_rgba(37,99,235,0.2)]">
            <!-- Top accent bar -->
            <div class="absolute left-0 right-0 top-0 h-1 bg-blue-600"></div>

            <!-- Card Header -->
            <div class="mb-5 flex items-center gap-4">
              <div
                class="flex h-16 w-16 shrink-0 items-center justify-center rounded-full bg-blue-600 text-xl font-bold text-white">
                {{ $initials }}
              </div>
              <div class="flex-1">
                <span
                  class="mb-1.5 inline-block rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-800">
                  Penguji {{ $num }}
                </span>
                <div class="text-lg font-bold text-gray-900">{{ $dosen->nama_lengkap }}</div>
                <div class="text-sm text-gray-500">NIDN: {{ $dosen->nidn }}</div>
              </div>
            </div>

            <!-- Card Body -->
            <div class="border-t border-gray-100 pt-4">
              @if ($dosen->rumpun_ilmu)
                <div class="flex items-center gap-3 py-2 text-sm">
                  <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-gray-100 text-gray-500">
                    <i class="fas fa-graduation-cap"></i>
                  </div>
                  <div class="flex-1 text-gray-700">
                    <strong>Rumpun Ilmu:</strong><br />{{ $dosen->rumpun_ilmu }}
                  </div>
                </div>
              @endif

              @if ($dosen->user && $dosen->user->email)
                <div class="flex items-center gap-3 py-2 text-sm">
                  <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-gray-100 text-gray-500">
                    <i class="fas fa-envelope"></i>
                  </div>
                  <div class="flex-1 text-gray-700">{{ $dosen->user->email }}</div>
                </div>
              @endif

              @if ($dosen->no_telp)
                <div class="flex items-center gap-3 py-2 text-sm">
                  <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-gray-100 text-gray-500">
                    <i class="fas fa-phone"></i>
                  </div>
                  <div class="flex-1 text-gray-700">{{ $dosen->no_telp }}</div>
                </div>
              @endif
            </div>
          </div>
        @endforeach
      </div>
    </div>

    <!-- Action Buttons -->
    <div class="mt-6 flex flex-wrap justify-between gap-3 border-t border-gray-200 pt-6">
      <a href="{{ route('mahasiswa.bimbingan.bimbingan', ['jenis' => $jenis]) }}"
        class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-6 py-3 text-sm font-medium text-gray-700 transition-all hover:bg-gray-50">
        <i class="fas fa-arrow-left"></i>
        Kembali
      </a>
      <a href="{{ route('mahasiswa.ujian', ['jenis' => $jenis]) }}"
        class="inline-flex items-center gap-2 rounded-lg bg-emerald-500 px-6 py-3 text-sm font-semibold text-white transition-all hover:bg-emerald-600">
        <i class="fas fa-file-alt"></i>
        Ajukan Ujian
      </a>
    </div>
  </div>
</div>
