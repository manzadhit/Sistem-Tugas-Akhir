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
        Schema::create('profile_mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('nim')->unique();
            $table->string('nama_lengkap');
            $table->string('jurusan');
            $table->string('program_studi');
            $table->string('angkatan');
            $table->decimal('ipk', 3, 2);
            $table->string('no_telp')->nullable();
            $table->string('foto')->nullable();
            $table->enum('status_akademik', ['aktif', 'cuti', 'nonaktif', 'lulus', 'dropout']);     
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profile_mahasiswa');
    }
};
