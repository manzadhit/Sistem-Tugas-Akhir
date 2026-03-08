<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewSubmission extends Notification implements ShouldQueue
{
    use Queueable;

    protected $mahasiswa;
    protected $submission;
    /**
     * Create a new notification instance.
     */
    public function __construct($mahasiswa, $submission)
    {
        $this->mahasiswa = $mahasiswa;
        $this->submission = $submission;
        $this->afterCommit();
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Pengajuan Bimbingan Baru',
            'message' => "{$this->mahasiswa->nama_lengkap} mengajukan permintaan bimbingan {$this->mahasiswa->tugasAkhir->tahapan}. Harap tinjau pengajuan tersebut.",
            'action_url' => route('dosen.bimbingan.detail', ['submission' => $this->submission->id]),
            'submission_id' => $this->submission->id,
            'icon' => 'fas fa-paper-plane',
            'icon_bg' => 'bg-orange-100',
            'icon_color' => 'text-orange-500',
        ];
    }
}
