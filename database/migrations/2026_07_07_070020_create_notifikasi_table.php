<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('kelas_perkuliahan_id')->nullable()->constrained('kelas_perkuliahan')->nullOnDelete();
            $table->string('judul', 150);
            $table->text('pesan');
            $table->enum('tipe', ['informasi', 'tugas', 'ujian', 'nilai', 'presensi'])->default('informasi');
            $table->string('url')->nullable();
            $table->timestamp('dibaca_pada')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifikasi');
    }
};
