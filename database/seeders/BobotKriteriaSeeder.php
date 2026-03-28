<?php

namespace Database\Seeders;

use App\Models\BobotKriteria;
use Illuminate\Database\Seeder;

class BobotKriteriaSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        BobotKriteria::upsert([
            [
                'key' => 'similarity',
                'label' => 'Similarity CBF',
                'weight' => 0.35,
                'type' => 'benefit',
                'is_active' => true,
            ],
            [
                'key' => 'beban_bimbingan',
                'label' => 'Jumlah Mahasiswa Dibimbing',
                'weight' => 0.20,
                'type' => 'cost',
                'is_active' => true,
            ],
            [
                'key' => 'beban_pengujian',
                'label' => 'Jumlah Mahasiswa Diuji',
                'weight' => 0.20,
                'type' => 'cost',
                'is_active' => true,
            ],
            [
                'key' => 'jabatan_fungsional',
                'label' => 'Jabatan Fungsional',
                'weight' => 0.20,
                'type' => 'benefit',
                'is_active' => true,
            ],
            [
                'key' => 'jumlah_publikasi',
                'label' => 'Jumlah Publikasi',
                'weight' => 0.15,
                'type' => 'benefit',
                'is_active' => true,
            ],
            [
                'key' => 'pemerataan_ipk',
                'label' => 'Pemerataan IPK Mahasiswa',
                'weight' => 0.10,
                'type' => 'cost',
                'is_active' => true,
            ],

        ], ['key'], ['label', 'weight', 'type', 'is_active']);
    }
}
