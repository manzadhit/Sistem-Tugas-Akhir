<?php

namespace Database\Seeders;

use App\Models\DosenPembimbing;
use App\Models\User;
use App\Models\ProfileDosen;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProfileMahasiswa;
use App\Models\Submission;
use App\Models\SubmissionFile;
use App\Models\TugasAkhir;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $dosens = ProfileDosen::factory()->count(6)->create();
        $mahasiswas = ProfileMahasiswa::factory()->count(10)->create();

        foreach ($mahasiswas as $mahasiswa) {
            $pair = $dosens->random(2)->values();

            $dospem1 = DosenPembimbing::factory()->pembimbing1()->create([
                'mahasiswa_id' => $mahasiswa->id,
                'dosen_id' => $pair[0]->id,
            ]);

            $dospem2 = DosenPembimbing::factory()->pembimbing2()->create([
                'mahasiswa_id' => $mahasiswa->id,
                'dosen_id' => $pair[1]->id,
            ]);

            $tugasAkhir = TugasAkhir::factory()->create([
                'mahasiswa_id' => $mahasiswa->id,
            ]);

            $submission1 = Submission::factory()->create([
                'tugas_akhir_id' => $tugasAkhir->id,
                'dosen_pembimbing_id' => $dospem1->id,
            ]);

            $submission2 = Submission::factory()->create([
                'tugas_akhir_id' => $tugasAkhir->id,
                'dosen_pembimbing_id' => $dospem2->id,
            ]);

            SubmissionFile::create([
                'submission_id' => $submission1->id,
                'uploaded_by' => 'mahasiswa',
                'file_path' => 'submission-file/Nyoman_CV_2025.pdf'
            ]);

            SubmissionFile::create([
                'submission_id' => $submission2->id,
                'uploaded_by' => 'mahasiswa',
                'file_path' => 'submission-file/Nyoman_CV_2025.pdf'
            ]);
        }
    }
}
