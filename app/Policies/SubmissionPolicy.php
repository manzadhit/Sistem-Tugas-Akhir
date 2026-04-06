<?php

namespace App\Policies;

use App\Models\Submission;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SubmissionPolicy
{
    public function view(User $user, Submission $submission)
    {
        $currentDosenId = $user->profileDosen?->id;

        if(!$currentDosenId) {
            return Response::deny('Profile dosen tidak ditemukan.');
        }

        $allowed =  $submission->dosenPembimbing?->dosen_id === $currentDosenId && $submission->tugasAkhir()->whereHas('mahasiswa.dosenPembimbing', function ($query) use ($currentDosenId, $submission) {
            $query->whereKey($submission->dosen_pembimbing_id)
                ->where('dosen_id', $currentDosenId);
        })->exists();

        return $allowed ? Response::allow() : Response::deny('Anda tidak berwenang untuk mengakses submission ini.');
    }

    public function review(User $user, Submission $submission)
    {
        return $this->view($user, $submission);
    }
}
