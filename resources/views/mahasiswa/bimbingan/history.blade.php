@extends('layouts.app')

@section('title', 'Riwayat Bimbingan')

@section('sidebar')
  @include('mahasiswa.sidebar')
@endsection

@section('content')
  <div class="relative h-40 rounded-xl overflow-hidden mb-8 bg-gradient-to-br from-blue-600 to-blue-800">
    <div class="absolute inset-0 flex flex-col items-center justify-center text-white text-center px-4">
      <i class="fas fa-history text-3xl sm:text-4xl mb-3"></i>
      <h1 class="text-xl sm:text-2xl md:text-[1.75rem] font-bold mb-1">Riwayat Bimbingan Semua Tahap</h1>
      <p class="text-xs sm:text-sm md:text-base opacity-90">
        Menampilkan riwayat bimbingan tahap proposal, hasil, dan skripsi
      </p>
    </div>
  </div>

  <x-alert type="success" />
  <x-alert type="error" />
  <x-alert type="warning" />

  @php
    $labelTahapan = [
        'proposal' => 'Proposal',
        'hasil' => 'Hasil',
        'skripsi' => 'Skripsi',
    ];

    $statusConfig = [
        'pending' => ['label' => 'Menunggu', 'class' => 'bg-amber-50 text-amber-700'],
        'acc' => ['label' => 'ACC', 'class' => 'bg-emerald-50 text-emerald-700'],
        'revision' => ['label' => 'Revisi', 'class' => 'bg-red-50 text-red-700'],
        'revisi' => ['label' => 'Revisi', 'class' => 'bg-red-50 text-red-700'],
        'reject' => ['label' => 'Ditolak', 'class' => 'bg-red-50 text-red-700'],
    ];
  @endphp

  <div class="space-y-6">
    @foreach ($tahapanOrder as $tahapan)
      @php
        $submissions = $submissionsByTahapan->get($tahapan, collect());
      @endphp

      <div class="overflow-hidden bg-white border border-slate-200 shadow-sm rounded-xl">
        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
          <h3 class="text-base font-semibold text-slate-900">
            {{ $labelTahapan[$tahapan] ?? ucfirst($tahapan) }}
          </h3>
          <p class="text-xs text-slate-500 mt-0.5">
            Urutan terbaru ke terlama
          </p>
        </div>

        <div class="divide-y divide-slate-100">
          @forelse ($submissions as $submission)
            @php
              $historyId = $tahapan . '-' . $loop->index;
              $status = $statusConfig[$submission->status] ?? [
                  'label' => ucfirst($submission->status),
                  'class' => 'bg-slate-100 text-slate-700',
              ];
              $fileDosen = $submission->submissionFiles->where('uploaded_by', 'dosen');
              $fileMahasiswa = $submission->submissionFiles->where('uploaded_by', 'mahasiswa');
            @endphp

            <div class="p-4 sm:p-5 bg-white">
              <div class="cursor-pointer" onclick="toggleHistory('{{ $historyId }}')">
                <div class="flex flex-col items-start gap-3 lg:flex-row lg:items-center lg:justify-between">
                  <div class="min-w-0">
                    <p class="text-sm font-semibold text-slate-800">
                      {{ $submission->dosenPembimbing->getJenisPembimbing() }} -
                      {{ $submission->dosenPembimbing->dosen->nama_lengkap ?? '-' }}
                    </p>

                    <p class="text-xs text-slate-400 mt-1.5">
                      {{ $submission->created_at?->translatedFormat('d M Y, H:i') }}
                      · {{ $submission->submissionFiles->count() }} file
                    </p>
                  </div>

                  <div class="flex items-center gap-2">
                    <span class="shrink-0 rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $status['class'] }}">
                      {{ $status['label'] }}
                    </span>
                    <i class="fas fa-chevron-down text-sm text-gray-400 transition-transform"
                      id="chevron-{{ $historyId }}"></i>
                  </div>
                </div>
              </div>

              <div class="hidden border-t border-gray-200 bg-gray-50 p-3 sm:p-4 mt-4 rounded-lg"
                id="files-{{ $historyId }}">
                @if ($fileDosen->isNotEmpty())
                  <div class="text-[0.8rem] font-medium text-gray-500 mb-3">Files dari Dosen:</div>
                  @foreach ($fileDosen as $file)
                    <x-file-preview-item :path="$file->file_path" :uploaded-at="$file->created_at" />
                  @endforeach
                @endif

                @if (!empty($submission->review))
                  <div class="mb-3 rounded-md border border-yellow-200 bg-yellow-50 px-3 py-2 text-sm text-yellow-900">
                    <div class="font-semibold">Catatan Pembimbing:</div>
                    <div>{{ $submission->review }}</div>
                  </div>
                @endif

                @if ($fileMahasiswa->isNotEmpty())
                  <div class="text-[0.8rem] font-medium text-gray-500 mb-3">Files Mahasiswa:</div>
                  @foreach ($fileMahasiswa as $file)
                    <x-file-preview-item :path="$file->file_path" :uploaded-at="$file->created_at" />
                  @endforeach
                @endif

                @if (!empty($submission->catatan))
                  <div class="mb-3 rounded-md border px-3 py-2 text-sm">
                    <div class="font-semibold">Catatan :</div>
                    <div>{{ $submission->catatan }}</div>
                  </div>
                @endif
              </div>
            </div>
          @empty
            <p class="text-sm text-slate-500 text-center py-6">Belum ada riwayat bimbingan tahap ini.</p>
          @endforelse
        </div>
      </div>
    @endforeach
  </div>

  <script>
    function toggleHistory(id) {
      const filesDiv = document.getElementById(`files-${id}`);
      const chevron = document.getElementById(`chevron-${id}`);

      if (!filesDiv || !chevron) {
        return;
      }

      if (filesDiv.classList.contains('hidden')) {
        filesDiv.classList.remove('hidden');
        chevron.classList.add('rotate-180');
      } else {
        filesDiv.classList.add('hidden');
        chevron.classList.remove('rotate-180');
      }
    }
  </script>
@endsection
