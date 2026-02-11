<?php

namespace App\Services;

use App\Models\Submission;
use App\Models\SubmissionFile;
use App\Models\TugasAkhir;
use App\Models\ProfileMahasiswa;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SubmissionService
{
    public function createSubmission(ProfileMahasiswa $mahasiswa, int $dosenPembimbingId, ?string $catatan, array $files): Submission
    {
        $tugasAkhir = TugasAkhir::where('mahasiswa_id', $mahasiswa->id)->firstOrFail();

        throw_if($this->pembimbingHasSubmission($tugasAkhir->id, $dosenPembimbingId), AuthorizationException::class, 'Submission untuk pembimbing ini masih menunggu review.');

        return DB::transaction(function () use ($tugasAkhir, $dosenPembimbingId, $catatan, $files) {
            // Create submission
            $submission = Submission::create([
                'tugas_akhir_id' => $tugasAkhir->id,
                'dosen_pembimbing_id' => $dosenPembimbingId,
                'catatan' => $catatan
            ]);

            // Upload files
            foreach ($files as $file) {
                $filename = $file->getClientOriginalName();
                $path = $file->storeAs('submission-files', $filename, 'public');

                SubmissionFile::create([
                    'submission_id' => $submission->id,
                    'uploaded_by' => 'mahasiswa',
                    'file_path' => $path,
                ]);
            }

            return $submission;
        });
    }

    public function pembimbingHasSubmission(int $tugasAkhirId, int $pembimbingId)
    {
        $hasSubmission = Submission::where('tugas_akhir_id', $tugasAkhirId)
            ->where('dosen_pembimbing_id', $pembimbingId)
            ->where('status', 'pending')
            ->exists();

        return $hasSubmission;
    }

    public function getHistorySubmission(int $tugasAkhirId)
    {
        return Submission::with(['submissionFiles', 'dosenPembimbing.dosen'])
            ->where('tugas_akhir_id', $tugasAkhirId)
            ->latest()
            ->get();
    }
}
