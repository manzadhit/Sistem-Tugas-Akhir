<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Notifications\UjianInvitationSent;
use App\Models\ProfileMahasiswa;
use App\Models\UndanganUjian;
use Illuminate\Http\Request;

class UndanganController extends Controller
{
    public function index(Request $request)
    {
        $dosenId = $request->user()->profileDosen->id;

        $undangan = UndanganUjian::with([
            'ujian.tugasAkhir.mahasiswa.dosenPembimbing',
            'ujian.tugasAkhir.mahasiswa.dosenPenguji',
            'ujian.jadwalUjian',
        ])
            ->whereHas('ujian.tugasAkhir.mahasiswa', function ($q) use ($dosenId) {
                $q->where(function ($q) use ($dosenId) {
                    $q->whereHas('dosenPembimbing', function ($q2) use ($dosenId) {
                        $q2->where('dosen_id', $dosenId)
                            ->where('status_aktif', true);
                    })
                        ->orWhereHas('dosenPenguji', function ($q2) use ($dosenId) {
                            $q2->where('dosen_id', $dosenId);
                        });
                });
            })
            ->where('status', 'terkirim')
            ->latest()
            ->paginate(10);

        // Sisipkan properti 'peran' ke setiap item tanpa query tambahan
        $undangan->getCollection()->transform(function ($item) use ($dosenId) {
            $mhs = $item->ujian->tugasAkhir->mahasiswa;
            $item->peran = $this->resolvePeran($mhs, $dosenId);
            return $item;
        });

        $request->user()->unreadNotifications()
            ->where('type', UjianInvitationSent::class)
            ->update(['read_at' => now()]);

        return view('dosen.undangan', compact('undangan'));
    }

    private function resolvePeran(ProfileMahasiswa $mhs, int $dosenId): string
    {
        $asPembimbing = $mhs->dosenPembimbing->firstWhere('dosen_id', $dosenId);

        if ($asPembimbing) {
            return match ($asPembimbing->jenis_pembimbing) {
                'pembimbing_1' => 'Pembimbing 1',
                'pembimbing_2' => 'Pembimbing 2',
                default => 'Pembimbing',
            };
        }

        $asPenguji = $mhs->dosenPenguji->firstWhere('dosen_id', $dosenId);

        if ($asPenguji) {
            return match ($asPenguji->jenis_penguji) {
                'penguji_1' => 'Penguji 1',
                'penguji_2' => 'Penguji 2',
                'penguji_3' => 'Penguji 3',
                default => 'Penguji',
            };
        }

        return '-';
    }
}
