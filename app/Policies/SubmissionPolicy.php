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

        if (!$currentDosenId) {
            return Response::deny('Profile dosen tidak ditemukan.');
        }

        $allowed = (int) $submission->dosenPembimbing?->dosen_id === (int) $currentDosenId;

        return $allowed ? Response::allow() : Response::deny('Anda tidak berwenang untuk mengakses submission ini.');
    }

    public function review(User $user, Submission $submission)
    {
        return $this->view($user, $submission);
    }
}
