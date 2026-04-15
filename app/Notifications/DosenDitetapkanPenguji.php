<?php

namespace App\Notifications;

use App\Models\DosenPenguji;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DosenDitetapkanPenguji extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(protected DosenPenguji $dosenPenguji)
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
            ->subject('Penetapan Dosen Penguji')
            ->greeting('Halo, ' . ($notifiable->display_name ?? 'Bapak/Ibu Dosen') . '.')
            ->line($this->getMessage())
            ->action('Lihat Daftar Pengujian', route('dosen.pengujian.index'));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Penetapan Penguji Baru',
            'message' => $this->getMessage(),
            'action_url' => route('dosen.pengujian.index'),
            'icon' => 'fas fa-gavel',
            'icon_bg' => 'bg-violet-100',
            'icon_color' => 'text-violet-600',
        ];
    }

    private function getMessage(): string
    {
        $mahasiswa = $this->dosenPenguji->mahasiswa;

        return "{$mahasiswa->nama_lengkap} ({$mahasiswa->nim}) ditetapkan ke Anda sebagai {$this->dosenPenguji->getJenisPenguji()}.";
    }
}
