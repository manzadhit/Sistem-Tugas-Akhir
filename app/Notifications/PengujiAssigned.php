<?php

namespace App\Notifications;

use App\Models\KajurSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class PengujiAssigned extends Notification implements ShouldQueue
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
        return [
            'title' => 'Penguji Telah Ditetapkan',
            'message' => 'Dosen penguji Anda telah ditetapkan. Silakan cek halaman pengajuan penguji untuk melanjutkan proses ujian.',
            'action_url' => route('mahasiswa.bimbingan.mintaPenguji', [
                'jenis' => $this->kajurSubmission->tugasAkhir->tahapan,
            ]),
            'kajur_submission_id' => $this->kajurSubmission->id,
            'icon' => 'fas fa-users',
            'icon_bg' => 'bg-blue-100',
            'icon_color' => 'text-blue-500',
        ];
    }
}
