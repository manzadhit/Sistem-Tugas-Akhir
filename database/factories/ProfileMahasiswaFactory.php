<?php

namespace Database\Factories;

use App\Models\ProfileMahasiswa;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProfileMahasiswa>
 */
class ProfileMahasiswaFactory extends Factory
{
    protected $model = ProfileMahasiswa::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->state(['role' => 'mahasiswa']),
            'nim' => fake()->unique()->numerify('#########'),
            'nama_lengkap' => fake()->name(),
            'jurusan' => fake()->randomElement(['Informatika', 'Sistem Informasi', 'Teknik Komputer']),
            'peminatan' => fake()->randomElement(['RPL', 'KCV', 'KBJ']),
            'angkatan' => fake()->numberBetween(2020, 2025),
            'ipk' => fake()->randomFloat(2, 2.00, 4.00),
            'no_telp' => fake()->optional()->phoneNumber(),
            'foto' => null,
            'status_akademik' => 'aktif'
        ];
    }
}
