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
        Schema::table('permintaan_pembimbing', function (Blueprint $table) {
            $table->boolean('penetapan_dilihat')->default(false)->after('diproses_pada');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permintaan_pembimbing', function (Blueprint $table) {
            $table->dropColumn('penetapan_dilihat');
        });
    }
};
