<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;

class DokumenUjianPolicy
{
    public function view($user, $dokumen)
    {
        $dokumen->loadMissing('ujian.tugasAkhir.mahasiswa');

        $allowed = $user->role === 'admin'
            || $this->isMahasiswaOwner($user, $dokumen->ujian?->tugasAkhir?->mahasiswa_id);

        return $allowed
            ? Response::allow()
            : Response::deny('Anda tidak berwenang mengakses dokumen ujian ini.');
    }

    private function isMahasiswaOwner($user, $mahasiswaId)
    {
        return $user->role === 'mahasiswa'
            && (int) $user->profileMahasiswa?->id === (int) $mahasiswaId;
    }
}
