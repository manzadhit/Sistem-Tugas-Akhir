<?php

namespace Database\Factories;

use App\Models\ProfileDosen;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PublikasiDosen>
 */
class PublikasiDosenFactory extends Factory
{
  public function definition(): array
  {
    $jenis = fake()->randomElement(['jurnal', 'haki', 'buku']);

    return [
      'dosen_id' => ProfileDosen::factory(),
      'judul' => fake()->sentence(6),
      'jenis_publikasi' => $jenis,
      'tahun' => fake()->numberBetween(2018, 2025),
      'penerbit' => $jenis !== 'haki' ? fake()->company() : null,
      'url' => fake()->optional(0.7)->url(),
    ];
  }
}
