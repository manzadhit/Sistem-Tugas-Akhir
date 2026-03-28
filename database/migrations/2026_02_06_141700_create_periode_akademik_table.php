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
        Schema::create('periode_akademik', function (Blueprint $table) {
            $table->id();
            $table->string('tahun_ajaran', 9);
            $table->enum('semester', ['ganjil', 'genap']);
            $table->date('mulai_at');
            $table->date('selesai_at')->nullable();
            $table->boolean('is_active')->default(false);
            $table->timestamps();

            $table->unique(['tahun_ajaran', 'semester']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('periode_akademik');
    }
};
