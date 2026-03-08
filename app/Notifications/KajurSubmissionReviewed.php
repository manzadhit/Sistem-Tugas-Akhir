<?php

namespace App\Notifications;

use App\Models\KajurSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class KajurSubmissionReviewed extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected KajurSubmission $kajurSubmission
    ) {
        $this->afterCommit();
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $statusMap = [
            'acc' => [
                'title' => 'Verifikasi Persyaratan Disetujui',
                'message' => 'Dokumen laporan tugas akhir Anda telah diverifikasi dan disetujui oleh ketua jurusan.',
                'icon' => 'fas fa-file-circle-check',
                'icon_bg' => 'bg-green-100',
                'icon_color' => 'text-green-500',
            ],
            'revisi' => [
                'title' => 'Verifikasi Persyaratan Perlu Revisi',
                'message' => 'Dokumen laporan tugas akhir Anda perlu direvisi. Silakan periksa catatan dari ketua jurusan.',
                'icon' => 'fas fa-file-pen',
                'icon_bg' => 'bg-yellow-100',
                'icon_color' => 'text-yellow-500',
            ],
            'reject' => [
                'title' => 'Verifikasi Persyaratan Ditolak',
                'message' => 'Dokumen laporan tugas akhir Anda ditolak oleh ketua jurusan. Silakan periksa catatan yang diberikan.',
                'icon' => 'fas fa-file-circle-xmark',
                'icon_bg' => 'bg-red-100',
                'icon_color' => 'text-red-500',
            ],
        ];

        return [
            ...$statusMap[$this->kajurSubmission->status],
            'kajur_submission_id' => $this->kajurSubmission->id,
            'action_url' => route('mahasiswa.bimbingan.mintaPenguji', [
                'jenis' => $this->kajurSubmission->tugasAkhir->tahapan,
            ]),
        ];
    }
}
