<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ProfileDosen;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProfileMahasiswa;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        ProfileMahasiswa::factory(5)->create();
        ProfileDosen::factory(5)->create();
    }
}
