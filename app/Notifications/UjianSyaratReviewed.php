<?php

namespace App\Notifications;

use App\Models\Ujian;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class UjianSyaratReviewed extends Notification implements ShouldQueue
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
        $isApproved = $this->ujian->status === 'menunggu_undangan';

        return [
            'title' => $isApproved ? 'Verifikasi Syarat Ujian Disetujui' : 'Verifikasi Syarat Ujian Ditolak',
            'message' => $isApproved
                ? "Berkas syarat ujian {$this->ujian->jenis_ujian} Anda telah diverifikasi dan disetujui oleh admin."
                : "Beberapa berkas syarat ujian {$this->ujian->jenis_ujian} Anda ditolak. Silakan periksa catatan admin dan upload ulang dokumen yang diminta.",
            'action_url' => $isApproved
                ? route('mahasiswa.ujian.undangan', ['jenis' => $this->ujian->jenis_ujian])
                : route('mahasiswa.ujian.pengajuan', ['jenis' => $this->ujian->jenis_ujian]),
            'ujian_id' => $this->ujian->id,
            'icon' => $isApproved ? 'fas fa-file-circle-check' : 'fas fa-file-circle-xmark',
            'icon_bg' => $isApproved ? 'bg-green-100' : 'bg-red-100',
            'icon_color' => $isApproved ? 'text-green-500' : 'text-red-500',
        ];
    }
}
