<?php

namespace App\Notifications;

use App\Models\KajurSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class NewPengujiRequest extends Notification implements ShouldQueue
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
        $mahasiswa = $this->kajurSubmission->tugasAkhir->mahasiswa;

        return [
            'title' => 'Pengajuan Penguji Baru',
            'message' => "{$mahasiswa->nama_lengkap} mengajukan permintaan dosen penguji. Silakan tinjau laporan yang dikirim.",
            'action_url' => route('kajur.penetapan-penguji', ['permintaan' => $this->kajurSubmission->id]),
            'kajur_submission_id' => $this->kajurSubmission->id,
            'icon' => 'fas fa-user-check',
            'icon_bg' => 'bg-amber-100',
            'icon_color' => 'text-amber-500',
        ];
    }
}
