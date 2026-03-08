<?php

namespace App\Notifications;

use App\Models\Ujian;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class NewUjianHasilSubmission extends Notification implements ShouldQueue
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
        $mahasiswa = $this->ujian->tugasAkhir->mahasiswa;

        return [
            'title' => 'Pengajuan Hasil Ujian Baru',
            'message' => "{$mahasiswa->nama_lengkap} mengajukan verifikasi berkas hasil ujian {$this->ujian->jenis_ujian}. Silakan tinjau dokumen yang dikirim.",
            'action_url' => route('admin.ujian.hasil.detail', ['id' => $this->ujian->id]),
            'ujian_id' => $this->ujian->id,
            'icon' => 'fas fa-file-arrow-up',
            'icon_bg' => 'bg-blue-100',
            'icon_color' => 'text-blue-500',
        ];
    }
}
