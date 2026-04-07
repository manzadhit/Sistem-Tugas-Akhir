<?php

namespace App\Policies;

use App\Models\DosenPenguji;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DosenPengujiPolicy
{
    public function inputNilai(User $user, DosenPenguji $dosenPenguji)
    {
        $currentDosenId = $user->profileDosen?->id;

        if (! $currentDosenId) {
            return Response::deny('Profile dosen tidak ditemukan.');
        }

        $allowed = (int) $dosenPenguji->dosen_id === (int) $currentDosenId;

        return $allowed ? Response::allow() : Response::deny('Anda tidak berwenang menginput nilai untuk penguji ini.');
    }
}
