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
        Schema::table('absensi_mahasiswa', function (Blueprint $table) {
            // Add timestamp when student checked in
            $table->timestamp('waktu_absensi')->nullable()->after('status');
            // Add notes/keterangan
            $table->text('keterangan')->nullable()->after('waktu_absensi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('absensi_mahasiswa', function (Blueprint $table) {
            $table->dropColumn(['waktu_absensi', 'keterangan']);
        });
    }
};
