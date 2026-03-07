<?php

namespace App\Notifications;

use App\Models\PermintaanPembimbing;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewPembimbingRequest extends Notification
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
        $mahasiswa = $this->permintaanPembimbing->mahasiswa;

        return [
            'title' => 'Pengajuan Pembimbing Baru',
            'message' => "{$mahasiswa->nama_lengkap} mengajukan permintaan dosen pembimbing. Silakan tinjau pengajuan tersebut.",
            'action_url' => route('kajur.penetapan-pembimbing', ['permintaan' => $this->permintaanPembimbing->id]),
            'permintaan_pembimbing_id' => $this->permintaanPembimbing->id,
            'icon' => 'fas fa-user-plus',
            'icon_bg' => 'bg-blue-100',
            'icon_color' => 'text-blue-500',
        ];
    }
}
