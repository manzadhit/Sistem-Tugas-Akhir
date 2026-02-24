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
        Schema::create('undangan_ujian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ujian_id')->constrained('ujian')->cascadeOnDelete();
            $table->string('nomor_surat');
            $table->string('hal');
            $table->date('tanggal_surat');
            $table->foreignId('ketua_sidang_id')->nullable()->constrained('profile_dosen')->nullOnDelete();
            $table->foreignId('sekretaris_sidang_id')->nullable()->constrained('profile_dosen')->nullOnDelete();
            $table->string('file_path');
            $table->enum('status', ['draft', 'terkirim'])->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('undangan_ujian');
    }
};
