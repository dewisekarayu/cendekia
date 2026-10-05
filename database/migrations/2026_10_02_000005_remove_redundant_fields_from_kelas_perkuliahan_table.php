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
            // Kita drop kolom redundan
            if (Schema::hasColumn('kelas_perkuliahan', 'tahun_akademik')) {
                $table->dropColumn('tahun_akademik');
            }
            if (Schema::hasColumn('kelas_perkuliahan', 'dosen_pengampu')) {
                $table->dropColumn('dosen_pengampu');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kelas_perkuliahan', function (Blueprint $table) {
            $table->string('tahun_akademik')->nullable();
            $table->json('dosen_pengampu')->nullable()->comment('Array dosen ID untuk team teaching');
        });
    }
};
