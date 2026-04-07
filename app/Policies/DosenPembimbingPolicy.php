<?php

namespace App\Policies;

use App\Models\DosenPembimbing;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DosenPembimbingPolicy
{
    public function view(User $user, DosenPembimbing $dosenPembimbing)
    {
        $currentDosenId = $user->profileDosen?->id;

        if (!$currentDosenId) {
            return Response::deny('Profil dosen tidak ditemukan.');
        }

        $allowed = (int) $dosenPembimbing->dosen_id === (int) $currentDosenId;

        return $allowed ? Response::allow() : Response::deny('Anda tidak berwenang mengakses data bimbingan ini.');
    }
}
