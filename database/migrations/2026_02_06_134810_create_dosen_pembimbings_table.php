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
        Schema::create('dosen_pembimbing', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('profile_mahasiswa')->cascadeOnDelete();
            $table->foreignId('dosen_id')->constrained('profile_dosen')->cascadeOnDelete();
            $table->enum('jenis_pembimbing', ['pembimbing_1', 'pembimbing_2']);
            $table->boolean('status_aktif')->default(true);
            $table->dateTime('tanggal_mulai');
            $table->dateTime('tanggal_selesai')->nullable();
            $table->timestamps();

            $table->unique(['mahasiswa_id', 'jenis_pembimbing']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dosen_pembimbing');
    }
};
