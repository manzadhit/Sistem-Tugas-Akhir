<?php

namespace Database\Seeders;

use App\Models\MataKuliah;
use Illuminate\Database\Seeder;

class MataKuliahSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $mataKuliahs = [
            ['kode' => 'IF201', 'nama' => 'Struktur Data'],
            ['kode' => 'IF301', 'nama' => 'Basis Data'],
            ['kode' => 'IF302', 'nama' => 'Rekayasa Perangkat Lunak'],
            ['kode' => 'IF401', 'nama' => 'Machine Learning'],
            ['kode' => 'IF402', 'nama' => 'Data Mining'],
        ];

        foreach ($mataKuliahs as $mataKuliah) {
            MataKuliah::updateOrCreate(
                ['kode' => $mataKuliah['kode']],
                ['nama' => $mataKuliah['nama']]
            );
        }
    }
}
