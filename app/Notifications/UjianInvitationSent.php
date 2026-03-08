<?php

namespace App\Notifications;

use App\Models\Ujian;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class UjianInvitationSent extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected Ujian $ujian
    ) {
        $this->afterCommit();
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $isMahasiswa = method_exists($notifiable, 'getAttribute') && $notifiable->getAttribute('role') === 'mahasiswa';

        return [
            'title' => 'Undangan Ujian Telah Dikirim',
            'message' => $isMahasiswa
                ? "Undangan ujian {$this->ujian->jenis_ujian} Anda telah dikirim. Silakan cek detail jadwal dan dokumen undangan."
                : "Undangan ujian {$this->ujian->jenis_ujian} untuk mahasiswa {$this->ujian->tugasAkhir->mahasiswa->nama_lengkap} telah dikirim. Silakan cek detail jadwal dan dokumen undangan.",
            'action_url' => $isMahasiswa
                ? route('mahasiswa.ujian.undangan', ['jenis' => $this->ujian->jenis_ujian])
                : route('dosen.undangan.index'),
            'ujian_id' => $this->ujian->id,
            'icon' => 'fas fa-envelope-open-text',
            'icon_bg' => 'bg-blue-100',
            'icon_color' => 'text-blue-500',
        ];
    }
}
