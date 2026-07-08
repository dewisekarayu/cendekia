<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nilai_akhir', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_perkuliahan_id')->constrained('kelas_perkuliahan')->cascadeOnDelete();
            $table->foreignId('mahasiswa_id')->constrained('users')->cascadeOnDelete();
            $table->decimal('nilai_kehadiran', 5, 2)->default(0);
            $table->decimal('nilai_tugas', 5, 2)->default(0);
            $table->decimal('nilai_quiz', 5, 2)->default(0);
            $table->decimal('nilai_project', 5, 2)->default(0);
            $table->decimal('nilai_uts', 5, 2)->default(0);
            $table->decimal('nilai_uas', 5, 2)->default(0);
            $table->decimal('nilai_akhir', 5, 2)->default(0);
            $table->string('grade', 2)->default('E');
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->unique(['kelas_perkuliahan_id', 'mahasiswa_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nilai_akhir');
    }
};
