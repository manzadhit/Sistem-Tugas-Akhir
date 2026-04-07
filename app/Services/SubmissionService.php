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
    public function createSubmission(ProfileMahasiswa $mahasiswa, int $dosenPembimbingId, ?string $catatan, array $files, string $tahapan = 'proposal'): Submission
    {
        $tugasAkhir = TugasAkhir::where('mahasiswa_id', $mahasiswa->id)->firstOrFail();

        throw_if($this->pembimbingHasSubmissionOrAcc($tugasAkhir->id, $dosenPembimbingId, $tahapan), AuthorizationException::class, 'Submission untuk pembimbing ini masih menunggu review atau sudah ACC.');

        return DB::transaction(function () use ($tugasAkhir, $dosenPembimbingId, $catatan, $files, $tahapan, $mahasiswa) {
            $submission = Submission::create([
                'tugas_akhir_id' => $tugasAkhir->id,
                'tahapan' => $tahapan,
                'dosen_pembimbing_id' => $dosenPembimbingId,
                'catatan' => $catatan,
            ]);

            foreach ($files as $file) {
                $path = $file->storeAs('submission-files/' . $mahasiswa->nim, $this->createFileName($file), 'local');

                SubmissionFile::create([
                    'submission_id' => $submission->id,
                    'uploaded_by' => 'mahasiswa',
                    'file_path' => $path,
                ]);
            }

            return $submission;
        });
    }

    private function createFileName($file)
    {
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extention = $file->getClientOriginalExtension();
        return $originalName . '_' . time() . '.' . $extention;
    }

    public function pembimbingHasSubmissionOrAcc(int $tugasAkhirId, int $pembimbingId, string $tahapan = 'proposal')
    {
        return Submission::where('tugas_akhir_id', $tugasAkhirId)
            ->where('dosen_pembimbing_id', $pembimbingId)
            ->where('tahapan', $tahapan)
            ->whereIn('status', ['pending', 'acc'])
            ->exists();
    }

    public function getHistorySubmission(int $tugasAkhirId, string $tahapan = 'proposal')
    {
        return Submission::with(['submissionFiles', 'dosenPembimbing.dosen'])
            ->where('tugas_akhir_id', $tugasAkhirId)
            ->where('tahapan', $tahapan)
            ->latest()
            ->get();
    }

}
