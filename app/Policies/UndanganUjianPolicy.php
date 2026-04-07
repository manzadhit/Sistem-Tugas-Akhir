<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;

class UndanganUjianPolicy
{
    public function view($user, $undangan)
    {
        if ($user->role === 'admin') {
            return Response::allow();
        }

        if ($undangan->status !== 'terkirim') {
            return Response::deny('Undangan ujian belum dikirim.');
        }

        $undangan->loadMissing('ujian.tugasAkhir.mahasiswa');

        $mahasiswa = $undangan->ujian?->tugasAkhir?->mahasiswa;
        $dosenId = $user->profileDosen?->id;

        $allowed = $this->isMahasiswaOwner($user, $mahasiswa?->id);

        if ($dosenId) {
            $allowed = $allowed
                || (int) $undangan->ketua_sidang_id === (int) $dosenId
                || (int) $undangan->sekretaris_sidang_id === (int) $dosenId
                || $mahasiswa?->dosenPembimbing()->where('dosen_id', $dosenId)->exists()
                || $mahasiswa?->dosenPenguji()->where('dosen_id', $dosenId)->exists();
        }

        return $allowed
            ? Response::allow()
            : Response::deny('Anda tidak berwenang mengakses undangan ujian ini.');
    }

    private function isMahasiswaOwner($user, $mahasiswaId)
    {
        return $user->role === 'mahasiswa'
            && (int) $user->profileMahasiswa?->id === (int) $mahasiswaId;
    }
}
