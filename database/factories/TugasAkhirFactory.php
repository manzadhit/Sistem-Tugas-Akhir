<?php

namespace Database\Factories;

use App\Models\ProfileMahasiswa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TugasAkhir>
 */
class TugasAkhirFactory extends Factory
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
            'judul' => fake()->sentence(6),
            'abstrak' => null,
            'kata_kunci' => implode(', ', fake()->words(5)),
            'tahapan' => 'proposal',
            'file_path' => null,
            'status' => 'draft'
        ];
    }
}
