<?php

namespace App\Notifications;

use App\Models\Ujian;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class UjianHasilReviewed extends Notification implements ShouldQueue
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
        $isApproved = $this->ujian->status === 'selesai';

        return [
            'title' => $isApproved ? 'Verifikasi Hasil Ujian Disetujui' : 'Verifikasi Hasil Ujian Ditolak',
            'message' => $isApproved
                ? "Berkas hasil ujian {$this->ujian->jenis_ujian} Anda telah diverifikasi dan disetujui oleh admin. Ujian Anda dinyatakan selesai."
                : "Beberapa berkas hasil ujian {$this->ujian->jenis_ujian} Anda ditolak. Silakan periksa catatan admin dan upload ulang dokumen yang diminta.",
            'action_url' => $isApproved
                ? route('mahasiswa.ujian.selesai', ['jenis' => $this->ujian->jenis_ujian])
                : route('mahasiswa.ujian.hasil-ujian', ['jenis' => $this->ujian->jenis_ujian]),
            'ujian_id' => $this->ujian->id,
            'icon' => $isApproved ? 'fas fa-file-circle-check' : 'fas fa-file-circle-xmark',
            'icon_bg' => $isApproved ? 'bg-green-100' : 'bg-red-100',
            'icon_color' => $isApproved ? 'text-green-500' : 'text-red-500',
        ];
    }
}
