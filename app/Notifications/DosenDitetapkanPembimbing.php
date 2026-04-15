<?php

namespace App\Notifications;

use App\Models\DosenPembimbing;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DosenDitetapkanPembimbing extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(protected DosenPembimbing $dosenPembimbing)
    {
        $this->afterCommit();
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Penetapan Dosen Pembimbing')
            ->greeting('Halo, ' . ($notifiable->display_name ?? 'Bapak/Ibu Dosen') . '.')
            ->line($this->getMessage())
            ->action('Lihat Daftar Mahasiswa Bimbingan', route('dosen.bimbingan.mahasiswa'));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Penetapan Pembimbing Baru',
            'message' => $this->getMessage(),
            'action_url' => route('dosen.bimbingan.mahasiswa'),
            'icon' => 'fas fa-user-check',
            'icon_bg' => 'bg-blue-100',
            'icon_color' => 'text-blue-500',
        ];
    }

    private function getMessage(): string
    {
        $mahasiswa = $this->dosenPembimbing->mahasiswa;

        return "{$mahasiswa->nama_lengkap} ({$mahasiswa->nim}) ditetapkan ke Anda sebagai {$this->dosenPembimbing->getJenisPembimbing()}.";
    }
}
