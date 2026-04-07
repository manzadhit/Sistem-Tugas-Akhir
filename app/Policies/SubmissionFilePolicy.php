<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;

class SubmissionFilePolicy
{
    public function view($user, $file)
    {
        $file->loadMissing('submission.tugasAkhir.mahasiswa', 'submission.dosenPembimbing');

        $submission = $file->submission;
        $mahasiswaId = $submission?->tugasAkhir?->mahasiswa_id;
        $dosenId = $user->profileDosen?->id;

        $allowed = $this->isMahasiswaOwner($user, $mahasiswaId)
            || ($dosenId && (int) $submission?->dosenPembimbing?->dosen_id === (int) $dosenId);

        return $allowed
            ? Response::allow()
            : Response::deny('Anda tidak berwenang mengakses file submission ini.');
    }

    private function isMahasiswaOwner($user, $mahasiswaId)
    {
        return $user->role === 'mahasiswa'
            && (int) $user->profileMahasiswa?->id === (int) $mahasiswaId;
    }
}
