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
        Schema::create('tugas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_perkuliahan_id')->constrained('kelas_perkuliahan')->onDelete('cascade');
            $table->string('judul');
            $table->text('instruksi')->nullable();
            $table->string('file_lampiran')->nullable(); // file pendukung dari dosen
            $table->dateTime('deadline');
            $table->integer('bobot_nilai')->default(100); // persentase bobot ke nilai akhir
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tugas');
    }
};
