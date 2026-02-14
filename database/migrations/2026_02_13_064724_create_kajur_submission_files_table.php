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
        Schema::create('kajur_submission_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kajur_submission_id')->constrained('kajur_submissions')->cascadeOnDelete();
            $table->enum('uploaded_by', ['mahasiswa', 'kajur']);
            $table->string('file_path');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kajur_submission_files');
    }
};
