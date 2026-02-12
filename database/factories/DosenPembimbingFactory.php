<?php

namespace Database\Factories;

use App\Models\ProfileDosen;
use App\Models\ProfileMahasiswa;
use Illuminate\Database\Eloquent\Factories\Factory;

class DosenPembimbingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'mahasiswa_id' => ProfileMahasiswa::factory(),
            'dosen_id' => ProfileDosen::factory(),
            'jenis_pembimbing' => fake()->randomElement(['pembimbing_1', 'pembimbing_2']),
            'status_aktif' => true,
            'tanggal_mulai' => now(),
            'tanggal_selesai' => null,
        ];
    }

    public function pembimbing1(): static
    {
        return $this->state(fn () => ['jenis_pembimbing' => 'pembimbing_1']);
    }

    public function pembimbing2(): static
    {
        return $this->state(fn () => ['jenis_pembimbing' => 'pembimbing_2']);
    }
}
