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
        Schema::create('kalender_akademik', function (Blueprint $table) {
            $table->id();
            $table->foreignId('semester_id')->constrained('semesters')->cascadeOnDelete();
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable();
            $table->enum('jenis_kegiatan', [
                'uts',
                'uas',
                'libur_nasional',
                'libur_akademik',
                'deadline_tugas',
                'deadline_skripsi',
                'pengumuman_nilai',
                'praktikum',
                'wisuda',
                'orientasi_mahasiswa_baru',
                'pembayaran_ukt',
                'pengisian_krs',
                'pengisian_khs',
                'cuti_akademik',
                'seminar',
                'presentasi_proyek',
                'sidang',
                'workshop',
                'pengumuman_akademik',
                'lainnya'
            ]);
            $table->string('warna', 7)->default('#002B6B');
            $table->boolean('is_published')->default(true);
            $table->boolean('is_all_day')->default(true);
            $table->time('waktu_mulai')->nullable();
            $table->time('waktu_selesai')->nullable();
            $table->string('lokasi')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->index(['semester_id', 'tanggal_mulai']);
            $table->index('jenis_kegiatan');
            $table->index('is_published');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kalender_akademik');
    }
};