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
            'user_id' => User::factory()->state(['role' => fake()->randomElement(['dosen', 'kajur'])]),
            'nidn' => fake()->unique()->numerify('########'),
            'nama_lengkap' => fake()->name(),
            'jurusan' => fake()->randomElement(['Informatika', 'Sistem Informasi', 'Teknik Komputer']),
            'program_studi' => fake()->randomElement(['S1', 'D3']),
            'keahlian' => fake()->randomElement(['Rekayasa Perangkat Lunak', 'Artificial Intelligence', 'Jaringan']),
            'foto' => null,
            'no_telp' => fake()->optional()->phoneNumber()
        ];
    }
}
