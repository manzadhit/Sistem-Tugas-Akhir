<?php

namespace App\Services\Dosen;

use App\Models\DosenPenguji;

class PengujianService
{
    public function getQuery($dosenId, $periode = null, $search = null)
    {
        return DosenPenguji::with(['mahasiswa.tugasAkhir.ujian' => fn($q) => $q
            ->when($periode, fn($q) => $q->where('periode_akademik_id', $periode->id))
            ->oldest()])
            ->where('dosen_id', $dosenId)
            ->when($periode, function ($query) use ($periode) {
                $query->whereHas('mahasiswa.tugasAkhir.ujian', fn($q) => $q->where('periode_akademik_id', $periode->id));
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
