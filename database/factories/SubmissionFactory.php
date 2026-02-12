<?php

namespace Database\Factories;

use App\Models\TugasAkhir;
use App\Models\ProfileDosen;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Submission>
 */
class SubmissionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition(): array
    {
        return [
            'tugas_akhir_id' => TugasAkhir::factory(),
            'dosen_pembimbing_id' => ProfileDosen::factory(),
            'catatan' => fake()->sentence(6),
            'status' => 'pending',
            'review' => null,
        ];
    }
}
