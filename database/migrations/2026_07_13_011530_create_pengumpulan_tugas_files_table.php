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
        Schema::create('pengumpulan_tugas_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengumpulan_tugas_id')
                  ->constrained('pengumpulan_tugas')
                  ->cascadeOnDelete();
            $table->string('file_path');
            $table->string('nama_asli')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengumpulan_tugas_files');
    }
};
