<?php

namespace Database\Seeders;

use App\Models\DosenPembimbing;
use App\Models\PublikasiDosen;
use App\Models\User;
use App\Models\ProfileDosen;
use Illuminate\Database\Seeder;
use App\Models\ProfileMahasiswa;
use App\Models\Submission;
use App\Models\SubmissionFile;
use App\Models\TugasAkhir;
use App\Models\DosenPenguji;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(MataKuliahSeeder::class);
        $this->call(BobotKriteriaSeeder::class);
        $this->call(PeriodeAkademikSeeder::class);

        // ─── Admin ───
        User::create([
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('1'),
            'role' => 'admin',
        ]);

        // ─── Kajur ───
        $kajurUser = User::create([
            'username' => 'kajur',
            'email' => 'kajur@example.com',
            'password' => Hash::make('1'),
            'role' => 'kajur',
        ]);
        ProfileDosen::create([
            'user_id' => $kajurUser->id,
            'nidn' => '0012345601',
            'nama_lengkap' => 'Dr. Ahmad Fauzi, M.Kom.',
            'jurusan' => 'Informatika',
            'program_studi' => 'S1',
            'keahlian' => 'Rekayasa Perangkat Lunak',
            'jabatan_fungsional' => 'Lektor Kepala',
            'foto' => null,
            'no_telp' => '081234567890',
        ]);

        // ─── Sekjur ───
        $sekjurUser = User::create([
            'username' => 'sekjur',
            'email' => 'sekjur@example.com',
            'password' => Hash::make('1'),
            'role' => 'sekjur',
        ]);
        ProfileDosen::create([
            'user_id' => $sekjurUser->id,
            'nidn' => '0098765402',
            'nama_lengkap' => 'Ir. Siti Rahayu, M.T.',
            'jurusan' => 'Informatika',
            'program_studi' => 'S1',
            'keahlian' => 'Sistem Informasi',
            'jabatan_fungsional' => 'Lektor',
            'foto' => null,
            'no_telp' => '089876543210',
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

        // ─── Publikasi per Dosen ───
        $semuaDosen = ProfileDosen::all();
        foreach ($semuaDosen as $dosen) {
            $jumlah = fake()->numberBetween(2, 5);
            PublikasiDosen::factory()
                ->count($jumlah)
                ->create(['dosen_id' => $dosen->id]);
        }

        // ─── Mahasiswa (mahasiswa1 – mahasiswa10) ───
        $mahasiswas = collect();
        for ($i = 1; $i <= 10; $i++) {
            $mahasiswas->push(
                ProfileMahasiswa::factory()->create([
                    'user_id' => User::factory()->state([
                        'username' => "mhs{$i}",
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

            // Tambahkan dosen penguji untuk mhs1 dan mhs2
            if (in_array($mahasiswa->user->username, ['mhs1', 'mhs2'])) {
                $calonPenguji = $dosens->whereNotIn('id', [$pair[0]->id, $pair[1]->id])->take(3)->values();
                if ($calonPenguji->count() >= 3) {
                    DosenPenguji::create(['mahasiswa_id' => $mahasiswa->id, 'dosen_id' => $calonPenguji[0]->id, 'jenis_penguji' => 'penguji_1']);
                    DosenPenguji::create(['mahasiswa_id' => $mahasiswa->id, 'dosen_id' => $calonPenguji[1]->id, 'jenis_penguji' => 'penguji_2']);
                    DosenPenguji::create(['mahasiswa_id' => $mahasiswa->id, 'dosen_id' => $calonPenguji[2]->id, 'jenis_penguji' => 'penguji_3']);
                }
            }
        }
    }
}
