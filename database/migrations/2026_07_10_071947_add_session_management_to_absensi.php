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
        Schema::table('absensi', function (Blueprint $table) {
            // Add session management columns
            $table->time('jam_mulai')->nullable()->after('tanggal');
            $table->time('jam_selesai')->nullable()->after('jam_mulai');
            $table->enum('session_status', ['draft', 'buka', 'tutup'])->default('draft')->after('jam_selesai');
            $table->text('catatan')->nullable()->after('berita_acara');
            $table->timestamp('waktu_buka')->nullable()->after('catatan');
            $table->timestamp('waktu_tutup')->nullable()->after('waktu_buka');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('absensi', function (Blueprint $table) {
            $table->dropColumn([
                'jam_mulai',
                'jam_selesai',
                'session_status',
                'catatan',
                'waktu_buka',
                'waktu_tutup'
            ]);
        });
    }
};
