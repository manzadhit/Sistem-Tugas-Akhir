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
        Schema::create('publikasi_dosen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dosen_id')->constrained('profile_dosen')->cascadeOnDelete();

            $table->string('judul');
            $table->text('abstrak')->nullable();
            $table->enum('jenis_publikasi', ['jurnal', 'haki', 'buku']);
            $table->year('tahun');
            $table->string('penerbit')->nullable();
            $table->text('url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('publikasi_dosen');
    }
};
