@php
  $isSkripsi = ($jenis ?? null) === 'skripsi';

  $allSteps = [
      'syarat' => ['icon' => 'fas fa-file-upload', 'label' => 'Upload Syarat'],
      'undangan' => ['icon' => 'fas fa-envelope-open-text', 'label' => 'Undangan'],
      'penilaian' => ['icon' => 'fas fa-star', 'label' => 'Penilaian'],
      'hasil' => ['icon' => 'fas fa-upload', 'label' => 'Upload Hasil'],
      'selesai' => ['icon' => 'fas fa-check-circle', 'label' => 'Selesai'],
  ];

  // Penilaian hanya tampil untuk skripsi
  $steps = collect($allSteps)->when(!$isSkripsi, fn($c) => $c->forget('penilaian'));

  // Cari posisi step aktif (1-based), default ke 1 jika tidak ditemukan
  $index = $steps->keys()->search($activeStep ?? '');
  $resolvedStep = ($index === false ? 0 : $index) + 1;

  // Reindex untuk foreach
  $steps = $steps->values();

  $lineColor = $resolvedStep === $steps->count() ? 'bg-emerald-500' : 'bg-gray-200';
@endphp

<div class="p-5 sm:p-6 mb-8 bg-white shadow-sm rounded-xl">
  <div class="relative flex justify-between">
    {{-- Garis penghubung --}}
    <div class="absolute top-5 left-[12%] right-[12%] h-[3px] {{ $lineColor }} z-0"></div>

    @foreach ($steps as $i => $step)
      @php
        $stepNumber = $i + 1;
        $isDone = $stepNumber < $resolvedStep;
        $isActive = $stepNumber === $resolvedStep;
        $isDoneActive = $isActive && $resolvedStep === $steps->count();
      @endphp

      <div class="relative z-10 flex-1 flex flex-col items-center">
        <div
          class="
          w-10 h-10 rounded-full flex items-center justify-center text-base mb-2 shadow-sm
          {{ $isDone || $isDoneActive ? 'bg-emerald-500 text-white' : '' }}
          {{ $isActive && !$isDoneActive ? 'bg-blue-600 text-white ring-4 ring-blue-600/20' : '' }}
          {{ !$isDone && !$isActive ? 'bg-gray-200 text-gray-400' : '' }}
        ">
          <i class="{{ $step['icon'] }}"></i>
        </div>
        <span
          class="
          text-[10px] sm:text-xs font-medium text-center
          {{ $isDone || $isDoneActive ? 'text-emerald-600 font-semibold' : '' }}
          {{ $isActive && !$isDoneActive ? 'text-blue-600 font-semibold' : '' }}
          {{ !$isDone && !$isActive ? 'text-gray-500' : '' }}
        ">{{ $step['label'] }}</span>
      </div>
    @endforeach
  </div>
</div>
