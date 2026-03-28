<?php

namespace Database\Seeders;

use App\Models\PeriodeAkademik;
use Illuminate\Database\Seeder;

class PeriodeAkademikSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        PeriodeAkademik::upsert([
            [
                'tahun_ajaran' => '2025/2026',
                'semester' => 'ganjil',
                'mulai_at' => '2025-08-01',
                'selesai_at' => '2026-01-31',
                'is_active' => false,
            ],
            [
                'tahun_ajaran' => '2025/2026',
                'semester' => 'genap',
                'mulai_at' => '2026-02-01',
                'selesai_at' => null,
                'is_active' => true,
            ],
        ], ['tahun_ajaran', 'semester'], ['mulai_at', 'selesai_at', 'is_active']);
    }
}
