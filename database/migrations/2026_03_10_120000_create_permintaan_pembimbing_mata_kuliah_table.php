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
            $table->foreignId('pp_id')
                ->constrained('permintaan_pembimbing', 'id', 'pp_mk_pp_id_fk')
                ->cascadeOnDelete();
            $table->foreignId('mk_id')
                ->constrained('mata_kuliah', 'id', 'pp_mk_mk_id_fk')
                ->cascadeOnDelete();
            $table->timestamps();

            $table->unique(
                ['pp_id', 'mk_id'],
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
