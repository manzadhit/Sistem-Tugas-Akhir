<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dosen_penguji', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('profile_mahasiswa')->cascadeOnDelete();
            $table->foreignId('dosen_id')->constrained('profile_dosen')->cascadeOnDelete();
            $table->foreignId('periode_akademik_id')->nullable()->constrained('periode_akademik')->nullOnDelete();
            $table->enum('jenis_penguji', ['penguji_1', 'penguji_2', 'penguji_3']);
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();

            $table->unique(['mahasiswa_id', 'jenis_penguji']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dosen_penguji');
    }
};
