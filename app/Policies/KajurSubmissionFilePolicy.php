<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;

class KajurSubmissionFilePolicy
{
    public function view($user, $file)
    {
        $file->loadMissing('kajurSubmission.tugasAkhir.mahasiswa');

        $mahasiswaId = $file->kajurSubmission?->tugasAkhir?->mahasiswa_id;

        $allowed = $user->role === 'kajur'
            || $this->isMahasiswaOwner($user, $mahasiswaId);

        return $allowed
            ? Response::allow()
            : Response::deny('Anda tidak berwenang mengakses file persetujuan ini.');
    }

    private function isMahasiswaOwner($user, $mahasiswaId)
    {
        return $user->role === 'mahasiswa'
            && (int) $user->profileMahasiswa?->id === (int) $mahasiswaId;
    }
}
