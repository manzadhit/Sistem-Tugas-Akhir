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
                'context' => 'pembimbing',
                'key' => 'similarity',
                'label' => 'Similarity CBF',
                'weight' => 0.35,
                'type' => 'benefit',
                'is_active' => true,
            ],
            [
                'context' => 'pembimbing',
                'key' => 'beban_bimbingan',
                'label' => 'Jumlah Mahasiswa Dibimbing',
                'weight' => 0.20,
                'type' => 'cost',
                'is_active' => true,
            ],
            [
                'context' => 'pembimbing',
                'key' => 'jabatan_fungsional',
                'label' => 'Jabatan Fungsional',
                'weight' => 0.20,
                'type' => 'benefit',
                'is_active' => true,
            ],
            [
                'context' => 'pembimbing',
                'key' => 'jumlah_publikasi',
                'label' => 'Jumlah Publikasi',
                'weight' => 0.15,
                'type' => 'benefit',
                'is_active' => true,
            ],
            [
                'context' => 'pembimbing',
                'key' => 'pemerataan_ipk',
                'label' => 'Pemerataan IPK Mahasiswa',
                'weight' => 0.10,
                'type' => 'cost',
                'is_active' => true,
            ],
            [
                'context' => 'penguji',
                'key' => 'similarity',
                'label' => 'Similarity CBF',
                'weight' => 0.40,
                'type' => 'benefit',
                'is_active' => true,
            ],
            [
                'context' => 'penguji',
                'key' => 'beban_pengujian',
                'label' => 'Jumlah Mahasiswa Diuji',
                'weight' => 0.25,
                'type' => 'cost',
                'is_active' => true,
            ],
            [
                'context' => 'penguji',
                'key' => 'jabatan_fungsional',
                'label' => 'Jabatan Fungsional',
                'weight' => 0.25,
                'type' => 'benefit',
                'is_active' => true,
            ],
            [
                'context' => 'penguji',
                'key' => 'jumlah_publikasi',
                'label' => 'Jumlah Publikasi',
                'weight' => 0.10,
                'type' => 'benefit',
                'is_active' => true,
            ],
        ], ['context', 'key'], ['label', 'weight', 'type', 'is_active']);
    }
}
