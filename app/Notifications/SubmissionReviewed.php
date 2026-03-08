<?php

namespace App\Notifications;

use App\Models\Submission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class SubmissionReviewed extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected Submission $submission,
        protected string $status
    ) {
        $this->afterCommit();
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $jenis = $this->submission->tugasAkhir->tahapan;

        $map = [
            'acc' => [
                'title'      => 'Bimbingan Disetujui',
                'message'    => "Submission bimbingan {$jenis} Anda telah disetujui oleh dosen pembimbing.",
                'icon'       => 'fas fa-circle-check',
                'icon_bg'    => 'bg-green-100',
                'icon_color' => 'text-green-500',
            ],
            'revisi' => [
                'title'      => 'Bimbingan Perlu Revisi',
                'message'    => "Submission bimbingan {$jenis} Anda memerlukan revisi. Silakan periksa catatan dari dosen.",
                'icon'       => 'fas fa-file-pen',
                'icon_bg'    => 'bg-yellow-100',
                'icon_color' => 'text-yellow-500',
            ],
            'reject' => [
                'title'      => 'Bimbingan Ditolak',
                'message'    => "Submission bimbingan {$jenis} Anda ditolak oleh dosen pembimbing.",
                'icon'       => 'fas fa-circle-xmark',
                'icon_bg'    => 'bg-red-100',
                'icon_color' => 'text-red-500',
            ],
        ];

        return [
            ...$map[$this->status],
            'submission_id' => $this->submission->id,
            'action_url'    => route('mahasiswa.bimbingan.bimbingan', ['jenis' => $jenis]),
        ];
    }
}
