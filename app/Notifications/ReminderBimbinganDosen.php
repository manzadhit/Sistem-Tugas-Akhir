<?php

namespace App\Notifications;

use App\Models\Submission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReminderBimbinganDosen extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(protected Submission $submission)
    {
        $this->afterCommit();
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mahasiswa = $this->submission->tugasAkhir?->mahasiswa?->nama_lengkap ?? 'Mahasiswa';
        $tahapan = $this->submission->tahapan;
        $actionUrl = route('dosen.bimbingan.detail', ['submission' => $this->submission->id]);

        return (new MailMessage)
            ->subject('Reminder: Pengajuan Bimbingan Belum Direview')
            ->greeting("Halo {$notifiable->display_name},")
            ->line("Pengajuan bimbingan {$tahapan} dari {$mahasiswa} belum direview selama lebih dari 3 hari.")
            ->line('Silakan tinjau pengajuan tersebut agar proses bimbingan mahasiswa tidak terhambat.')
            ->action('Lihat Detail Bimbingan', $actionUrl);
    }

    public function toArray(object $notifiable): array
    {
        $mahasiswa = $this->submission->tugasAkhir?->mahasiswa?->nama_lengkap ?? 'Mahasiswa';

        return [
            'title' => 'Reminder Review Bimbingan',
            'message' => "Pengajuan bimbingan {$this->submission->tahapan} dari {$mahasiswa} belum direview lebih dari 3 hari.",
            'action_url' => route('dosen.bimbingan.detail', ['submission' => $this->submission->id]),
            'submission_id' => $this->submission->id,
            'icon' => 'fas fa-clock',
            'icon_bg' => 'bg-amber-100',
            'icon_color' => 'text-amber-600',
        ];
    }
}
