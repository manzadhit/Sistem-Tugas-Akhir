<?php

namespace App\Services\Dosen;

use App\Models\DosenPenguji;

class PengujianService
{
    public function getQuery($dosenId, $periode = null, $search = null)
    {
        return DosenPenguji::with(['mahasiswa.tugasAkhir.ujian' => fn($q) => $q
            ->when($periode, fn($q) => $q->where('periode_akademik_id', $periode->id))
            ->latest()])
            ->where('dosen_id', $dosenId)
            ->when($periode, function ($query) use ($periode) {
                // Tampilkan mahasiswa yang sudah punya ujian di periode ini,
                // atau baru ditetapkan di periode ini meski ujiannya belum dibuat.
                $query->where(function ($query) use ($periode) {
                    $query->whereHas('mahasiswa.tugasAkhir.ujian', fn($query) => $query->where('periode_akademik_id', $periode->id))
                        ->orWhereBetween('dosen_penguji.created_at', [
                            $periode->mulai_at->copy()->startOfDay(),
                            ($periode->selesai_at ?? now())->copy()->endOfDay(),
                        ]);
                });
            })
            ->when(trim((string) $search) !== '', function ($q) use ($search) {
                $q->whereHas('mahasiswa', fn($q) => $q
                    ->where('nama_lengkap', 'like', "%{$search}%")
                    ->orWhere('nim', 'like', "%{$search}%")
                );
            })
            ->latest();
    }
}
