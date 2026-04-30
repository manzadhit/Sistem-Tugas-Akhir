<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProfileDosen>
 */
class ProfileDosenFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->state(['role' => 'dosen']),
            'nidn' => fake()->unique()->numerify('########'),
            'nama_lengkap' => fake()->name(),
            'jurusan' => fake()->randomElement(['Informatika', 'Sistem Informasi', 'Teknik Komputer']),
            'rumpun_ilmu' => fake()->randomElement(['Rekayasa Perangkat Lunak', 'Artificial Intelligence', 'Jaringan']),
            'jabatan_fungsional' => fake()->randomElement(['Tenaga Pendidik', 'Asisten Ahli', 'Lektor', 'Lektor Kepala', 'Guru Besar']),
            'sinta_score_3y' => fake()->randomFloat(2, 0, 500),
            'status' => fake()->randomElement(['aktif', 'cuti', 'nonaktif', 'pensiun']),
            'foto' => null,
            'no_telp' => fake()->optional()->phoneNumber()
        ];
    }
}
