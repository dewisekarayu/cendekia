<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel pivot pendaftaran mahasiswa ke kelas.
     * Dipakai oleh KelasPerkuliahan::mahasiswa() dan User::kelasDiikuti().
     * Dibuat hanya jika belum ada, supaya aman kalau ternyata sudah dibuat
     * di migration lain yang tidak ikut diupload.
     */
    public function up(): void
    {
        if (Schema::hasTable('kelas_mahasiswa')) {
            return;
        }

        Schema::create('kelas_mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_perkuliahan_id')->constrained('kelas_perkuliahan')->cascadeOnDelete();
            $table->foreignId('mahasiswa_id')->constrained('users')->cascadeOnDelete();
            $table->date('tanggal_daftar')->nullable();
            $table->timestamps();

            $table->unique(['kelas_perkuliahan_id', 'mahasiswa_id'], 'kelas_mahasiswa_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kelas_mahasiswa');
    }
};
