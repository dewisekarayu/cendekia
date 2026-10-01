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
            $table->integer('bobot_tugas')->default(30)->after('is_active');
            $table->integer('bobot_uts')->default(30)->after('bobot_tugas');
            $table->integer('bobot_uas')->default(40)->after('bobot_uts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kelas_perkuliahan', function (Blueprint $table) {
            $table->dropColumn(['bobot_tugas', 'bobot_uts', 'bobot_uas']);
        });
    }
};
