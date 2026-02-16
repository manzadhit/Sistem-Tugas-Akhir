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
        Schema::create('tugas_akhir', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('profile_mahasiswa')->cascadeOnDelete();
            $table->string('judul')->unique();
            $table->text('abstrak')->nullable();
            $table->string('kata_kunci')->nullable();
            $table->enum('tahapan', ['proposal', 'hasil', 'skripsi'])->default('proposal');
            $table->string('file_path')->nullable();
            $table->enum('status', ['draft', 'revisi', 'acc', 'reject'])->default('draft');
            $table->timestamps();

            $table->unique('mahasiswa_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tugas_akhir');
    }
};
