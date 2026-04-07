<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;

class PermintaanPembimbingPolicy
{
    public function view($user, $permintaan)
    {
        $allowed = $user->role === 'kajur'
            || $this->isMahasiswaOwner($user, $permintaan->mahasiswa_id);

        return $allowed
            ? Response::allow()
            : Response::deny('Anda tidak berwenang mengakses bukti ACC pembimbing ini.');
    }

    private function isMahasiswaOwner($user, $mahasiswaId)
    {
        return $user->role === 'mahasiswa'
            && (int) $user->profileMahasiswa?->id === (int) $mahasiswaId;
    }
}
