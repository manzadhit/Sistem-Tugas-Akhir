@props([
    'status' => null,
    'pembimbingLabel' => '-',
])

@php
    $config = [
        'revisi' => [
            'container_class' => 'bg-amber-50 border-amber-200 text-amber-800',
            'icon' => 'fas fa-exclamation-triangle',
            'title' => 'Revisi Diperlukan dari :pembimbing',
            'message' => 'Silakan perbaiki laporan sesuai catatan pembimbing dan upload kembali.',
        ],
        'reject' => [
            'container_class' => 'bg-red-50 border-red-200 text-red-800',
            'icon' => 'fas fa-times-circle',
            'title' => 'Submission Ditolak oleh :pembimbing',
            'message' => 'Silakan perbaiki laporan sesuai catatan pembimbing dan upload kembali.',
        ],
        'acc' => [
            'container_class' => 'bg-emerald-50 border-emerald-200 text-emerald-800',
            'icon' => 'fas fa-check-circle',
            'title' => 'Disetujui oleh :pembimbing',
            'message' => 'Proposal Anda telah disetujui oleh pembimbing.',
        ],
    ];

    $current = $config[$status] ?? null;
@endphp

@if ($current)
  <div
    {{ $attributes->class([
        'px-4 py-4 rounded-lg mb-6 flex items-start gap-3 border',
        $current['container_class'],
    ]) }}>
    <i class="{{ $current['icon'] }} text-lg mt-0.5"></i>
    <div class="flex-1">
      <div class="font-semibold mb-1">
        {{ str_replace(':pembimbing', $pembimbingLabel, $current['title']) }}
      </div>
      <div class="text-sm">
        {{ $current['message'] }}
      </div>
    </div>
  </div>
@endif
