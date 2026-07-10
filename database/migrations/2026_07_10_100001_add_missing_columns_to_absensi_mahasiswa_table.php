<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('absensi_mahasiswa', function (Blueprint $table) {
            if (!Schema::hasColumn('absensi_mahasiswa', 'waktu_absensi')) {
                $table->timestamp('waktu_absensi')->nullable()->after('status');
            }
            if (!Schema::hasColumn('absensi_mahasiswa', 'keterangan')) {
                $table->string('keterangan')->nullable()->after('waktu_absensi');
            }
        });

        // Satu mahasiswa hanya boleh punya satu baris kehadiran per sesi absensi
        try {
            Schema::table('absensi_mahasiswa', function (Blueprint $table) {
                $table->unique(
                    ['absensi_id', 'mahasiswa_id'],
                    'absensi_mahasiswa_unique'
                );
            });
        } catch (\Throwable $e) {
            // index sudah ada, abaikan
        }
    }

    public function down(): void
    {
        Schema::table('absensi_mahasiswa', function (Blueprint $table) {
            try {
                $table->dropUnique('absensi_mahasiswa_unique');
            } catch (\Throwable $e) {
                //
            }
            $table->dropColumn(['waktu_absensi', 'keterangan']);
        });
    }
};
