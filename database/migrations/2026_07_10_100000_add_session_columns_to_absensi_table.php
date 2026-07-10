<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambahkan kolom-kolom yang dibutuhkan alur sesi presensi
     * (draft -> buka -> tutup) yang sebelumnya tidak ada di tabel `absensi`,
     * padahal sudah dipakai oleh model Absensi & AbsensiController.
     */
    public function up(): void
    {
        Schema::table('absensi', function (Blueprint $table) {
            if (!Schema::hasColumn('absensi', 'jam_mulai')) {
                $table->time('jam_mulai')->nullable()->after('tanggal');
            }
            if (!Schema::hasColumn('absensi', 'jam_selesai')) {
                $table->time('jam_selesai')->nullable()->after('jam_mulai');
            }
            if (!Schema::hasColumn('absensi', 'session_status')) {
                $table->enum('session_status', ['draft', 'buka', 'tutup'])
                    ->default('draft')
                    ->after('jam_selesai');
            }
            if (!Schema::hasColumn('absensi', 'catatan')) {
                $table->text('catatan')->nullable()->after('berita_acara');
            }
            if (!Schema::hasColumn('absensi', 'waktu_buka')) {
                $table->timestamp('waktu_buka')->nullable()->after('catatan');
            }
            if (!Schema::hasColumn('absensi', 'waktu_tutup')) {
                $table->timestamp('waktu_tutup')->nullable()->after('waktu_buka');
            }
        });

        // Pastikan tidak ada dua sesi presensi untuk pertemuan yang sama di kelas yang sama.
        // Dibungkus try/catch supaya migration tetap aman dijalankan ulang kalau index sudah ada.
        try {
            Schema::table('absensi', function (Blueprint $table) {
                $table->unique(
                    ['kelas_perkuliahan_id', 'pertemuan_ke'],
                    'absensi_kelas_pertemuan_unique'
                );
            });
        } catch (\Throwable $e) {
            // index sudah ada, abaikan
        }
    }

    public function down(): void
    {
        Schema::table('absensi', function (Blueprint $table) {
            try {
                $table->dropUnique('absensi_kelas_pertemuan_unique');
            } catch (\Throwable $e) {
                // abaikan jika tidak ada
            }
            $table->dropColumn([
                'jam_mulai',
                'jam_selesai',
                'session_status',
                'catatan',
                'waktu_buka',
                'waktu_tutup',
            ]);
        });
    }
};
