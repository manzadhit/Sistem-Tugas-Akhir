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
            'program_studi' => fake()->randomElement(['S1', 'D3']),
            'keahlian' => fake()->randomElement(['Rekayasa Perangkat Lunak', 'Artificial Intelligence', 'Jaringan']),
            'jabatan_fungsional' => fake()->randomElement(['Tenaga Pengajar', 'Asisten Ahli', 'Lektor', 'Lektor Kepala', 'Guru Besar']),
            'status' => fake()->randomElement(['aktif', 'cuti', 'nonaktif', 'pensiun']),
            'kuota_pembimbing' => fake()->numberBetween(5, 12),
            'kuota_penguji' => fake()->numberBetween(5, 15),
            'foto' => null,
            'no_telp' => fake()->optional()->phoneNumber()
        ];
    }
}
