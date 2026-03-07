<?php

namespace App\Notifications;

use App\Models\PermintaanPembimbing;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PembimbingAssigned extends Notification
{
    use Queueable;

    public function __construct(
        protected PermintaanPembimbing $permintaanPembimbing
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Pembimbing Telah Ditetapkan',
            'message' => 'Dosen pembimbing Anda telah ditetapkan. Silakan cek dashboard untuk melanjutkan proses bimbingan.',
            'action_url' => route('mahasiswa.dashboard'),
            'permintaan_pembimbing_id' => $this->permintaanPembimbing->id,
            'icon' => 'fas fa-user-check',
            'icon_bg' => 'bg-blue-100',
            'icon_color' => 'text-blue-500',
        ];
    }
}
