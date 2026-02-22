<?php

namespace Database\Seeders;

use App\Models\DosenPembimbing;
use App\Models\User;
use App\Models\ProfileDosen;
use Illuminate\Database\Seeder;
use App\Models\ProfileMahasiswa;
use App\Models\Submission;
use App\Models\SubmissionFile;
use App\Models\TugasAkhir;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ─── Admin ───
        User::create([
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // ─── Kajur ───
        User::create([
            'username' => 'kajur',
            'email' => 'kajur@example.com',
            'password' => Hash::make('password'),
            'role' => 'kajur',
        ]);

        // ─── Dosen (dosen1 – dosen6) ───
        $dosens = collect();
        for ($i = 1; $i <= 6; $i++) {
            $dosens->push(
                ProfileDosen::factory()->create([
                    'user_id' => User::factory()->state([
                        'username' => "dosen{$i}",
                        'role' => 'dosen',
                    ]),
                    'nama_lengkap' => "Dosen {$i}",
                ])
            );
        }

        // ─── Mahasiswa (mahasiswa1 – mahasiswa10) ───
        $mahasiswas = collect();
        for ($i = 1; $i <= 10; $i++) {
            $mahasiswas->push(
                ProfileMahasiswa::factory()->create([
                    'user_id' => User::factory()->state([
                        'username' => "mahasiswa{$i}",
                        'role' => 'mahasiswa',
                    ]),
                    'nama_lengkap' => "Mahasiswa {$i}",
                ])
            );
        }

        // ─── Relasi Pembimbing, Tugas Akhir, Submission ───
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
                'file_path' => 'submission-file/Nyoman_CV_2025.pdf',
            ]);

            SubmissionFile::create([
                'submission_id' => $submission2->id,
                'uploaded_by' => 'mahasiswa',
                'file_path' => 'submission-file/Nyoman_CV_2025.pdf',
            ]);
        }
    }
}
