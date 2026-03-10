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
        Schema::create('permintaan_pembimbing_mata_kuliah', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permintaan_pembimbing_id')
                ->constrained('permintaan_pembimbing')
                ->cascadeOnDelete();
            $table->foreignId('mata_kuliah_id')
                ->constrained('mata_kuliah')
                ->cascadeOnDelete();
            $table->timestamps();

            $table->unique(
                ['permintaan_pembimbing_id', 'mata_kuliah_id'],
                'pp_mk_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permintaan_pembimbing_mata_kuliah');
    }
};
