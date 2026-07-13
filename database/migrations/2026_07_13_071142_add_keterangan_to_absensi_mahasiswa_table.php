<?php
// database/migrations/2026_07_13_000000_add_keterangan_to_absensi_mahasiswa_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('absensi_mahasiswa', function (Blueprint $table) {
            if (!Schema::hasColumn('absensi_mahasiswa', 'keterangan')) {
                $table->string('keterangan', 255)->nullable()->after('status');
            }
            if (!Schema::hasColumn('absensi_mahasiswa', 'waktu_absensi')) {
                $table->timestamp('waktu_absensi')->nullable()->after('keterangan');
            }
        });

        // Cegah 1 mahasiswa presensi 2x di sesi yang sama (safety net di level DB)
        $indexExists = collect(DB::select("SHOW INDEX FROM absensi_mahasiswa WHERE Key_name = 'absensi_mahasiswa_unique'"))->isNotEmpty();

        if (!$indexExists) {
            Schema::table('absensi_mahasiswa', function (Blueprint $table) {
                $table->unique(['absensi_id', 'mahasiswa_id'], 'absensi_mahasiswa_unique');
            });
        }
    }

    public function down(): void
    {
        Schema::table('absensi_mahasiswa', function (Blueprint $table) {
            $table->dropUnique('absensi_mahasiswa_unique');
        });
    }
};