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
        Schema::table('kelas_perkuliahan', function (Blueprint $table) {
            // Tambahkan program_studi_id (untuk relasi langsung ke program studi)
            $table->foreignId('program_studi_id')->nullable()->constrained('program_studi')->onDelete('cascade');
            
            // Tambahkan tahun_akademik (misal: 2026/2027)
            $table->string('tahun_akademik')->nullable();
            
            // Tambahkan kuota mahasiswa
            $table->integer('kuota_mahasiswa')->default(30);
            
            // Tambahkan status_kelas (lebih deskriptif dari is_active)
            $table->enum('status_kelas', ['aktif', 'nonaktif', 'selesai', 'draft'])->default('draft');
            
            // Tambahkan catatan untuk team teaching (field JSON untuk menyimpan multiple dosen)
            $table->json('dosen_pengampu')->nullable()->comment('Array dosen ID untuk team teaching');
            
            // Index untuk pencarian dan optimasi
            $table->index(['program_studi_id', 'semester_id']);
            $table->index(['hari', 'jam_mulai']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kelas_perkuliahan', function (Blueprint $table) {
            $table->dropForeign(['program_studi_id']);
            $table->dropColumn(['program_studi_id', 'tahun_akademik', 'kuota_mahasiswa', 'status_kelas', 'dosen_pengampu']);
            $table->dropIndex(['program_studi_id', 'semester_id']);
            $table->dropIndex(['hari', 'jam_mulai']);
        });
    }
};
