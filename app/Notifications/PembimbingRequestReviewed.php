<?php

namespace App\Notifications;

use App\Models\PermintaanPembimbing;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PembimbingRequestReviewed extends Notification
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
        $isApproved = $this->permintaanPembimbing->status_verifikasi_bukti === 'disetujui';

        return [
            'title' => $isApproved ? 'Bukti ACC Disetujui' : 'Bukti ACC Ditolak',
            'message' => $isApproved
                ? 'Bukti ACC judul tugas akhir Anda telah diverifikasi dan disetujui oleh kajur.'
                : 'Bukti ACC judul tugas akhir Anda ditolak oleh kajur. Silakan periksa catatan yang diberikan.',
            'action_url' => route('mahasiswa.permintaan-pembimbing.create'),
            'permintaan_pembimbing_id' => $this->permintaanPembimbing->id,
            'icon' => $isApproved ? 'fas fa-file-circle-check' : 'fas fa-file-circle-xmark',
            'icon_bg' => $isApproved ? 'bg-green-100' : 'bg-red-100',
            'icon_color' => $isApproved ? 'text-green-500' : 'text-red-500',
        ];
    }
}
