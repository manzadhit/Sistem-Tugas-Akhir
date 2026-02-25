{{--
  Progress Bar Partial - Ujian Mahasiswa
  ------------------------------------
  Usage: @include('mahasiswa.ujian.partials.progress-bar', ['activeStep' => 1])

  Steps:
    1 = Upload Syarat (upload-syarat)
    2 = Undangan      (undangan)
    3 = Upload Hasil  (upload-hasil-ujian)
    4 = Selesai       (selesai)

  Step state:
    - step < activeStep  → done (emerald)
    - step = activeStep  → active (blue + ring)
    - step > activeStep  → pending (gray)
  
  Special: activeStep = 4 (selesai) → semua step emerald, garis emerald.
--}}

@php
  $steps = [
      ['icon' => 'fas fa-file-upload', 'label' => 'Upload Syarat'],
      ['icon' => 'fas fa-envelope-open-text', 'label' => 'Undangan'],
      ['icon' => 'fas fa-upload', 'label' => 'Upload Hasil'],
      ['icon' => 'fas fa-check-circle', 'label' => 'Selesai'],
  ];

  $lineColor = $activeStep === 4 ? 'bg-emerald-500' : 'bg-gray-200';
@endphp

<div class="p-5 sm:p-6 mb-8 bg-white shadow-sm rounded-xl">
  <div class="relative flex justify-between">
    {{-- Garis penghubung --}}
    <div class="absolute top-5 left-[12%] right-[12%] h-[3px] {{ $lineColor }} z-0"></div>

    @foreach ($steps as $i => $step)
      @php
        $stepNumber = $i + 1;
        $isDone = $stepNumber < $activeStep;
        $isActive = $stepNumber === $activeStep;
        $isDoneActive = $isActive && $activeStep === count($steps);
      @endphp

      <div class="relative z-10 flex-1 flex flex-col items-center">
        <div
          class="
          w-10 h-10 rounded-full flex items-center justify-center text-base mb-2 shadow-sm
          {{ $isDone || $isDoneActive ? 'bg-emerald-500 text-white' : '' }}
          {{ $isActive && !$isDoneActive ? 'bg-blue-600 text-white ring-4 ring-blue-600/20' : '' }}
          {{ !$isDone && !$isActive && !$isDoneActive ? 'bg-gray-200 text-gray-400' : '' }}
        ">
          <i class="{{ $step['icon'] }}"></i>
        </div>
        <span
          class="
          text-[10px] sm:text-xs font-medium text-center
          {{ $isDone || $isDoneActive ? 'text-emerald-600 font-semibold' : '' }}
          {{ $isActive && !$isDoneActive ? 'text-blue-600 font-semibold' : '' }}
          {{ !$isDone && !$isActive && !$isDoneActive ? 'text-gray-500' : '' }}
        ">{{ $step['label'] }}</span>
      </div>
    @endforeach
  </div>
</div>
