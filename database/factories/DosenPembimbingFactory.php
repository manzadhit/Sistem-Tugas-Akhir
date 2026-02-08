<?php

namespace Database\Factories;

use App\Models\ProfileDosen;
use App\Models\ProfileMahasiswa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DosenPembimbing>
 */
class DosenPembimbingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'mahasiswa_id' => ProfileMahasiswa::factory(),
            'dosen_id' => ProfileDosen::factory(),
            'jenis_pembimbing' => fake()->randomElement(['pembimbing_1', 'pembimbing_2']),
            'tanggal_mulai' => now()
        ];
    }
}
