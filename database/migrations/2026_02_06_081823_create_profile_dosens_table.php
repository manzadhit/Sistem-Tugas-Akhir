<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('profile_dosen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('nidn')->unique();
            $table->string('nama_lengkap');
            $table->string('jurusan');
            $table->string('program_studi');
            $table->string('keahlian');
            $table->string('jabatan_fungsional');
            $table->enum('status', ['aktif', 'cuti', 'nonaktif', 'pensiun'])->default('aktif');
            $table->unsignedInteger('total_mahasiswa_dibimbing')->default(0);
            $table->unsignedInteger('total_mahasiswa_diuji')->default(0);
            $table->string('foto')->nullable();
            $table->string('no_telp')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profile_dosen');
    }
};
