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

        $allowed = $dosenPenguji->dosen_id === $currentDosenId
            && $dosenPenguji->mahasiswa()
            ->whereHas('dosenPenguji', function ($query) use ($currentDosenId, $dosenPenguji) {
                $query->whereKey($dosenPenguji->id)
                    ->where('dosen_id', $currentDosenId);
            })
            ->exists();

        return $allowed ? Response::allow() : Response::deny('Anda tidak berwenang menginput nilai untuk penguji ini.');
    }
}
