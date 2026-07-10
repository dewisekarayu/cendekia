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
        Schema::create('dosen_materi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('materi_id')->constrained('materi')->onDelete('cascade');
            $table->foreignId('dosen_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            // Unique constraint: satu dosen tidak bisa diassign ke materi yang sama 2x
            $table->unique(['materi_id', 'dosen_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dosen_materi');
    }
};
