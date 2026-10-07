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
            $table->boolean('is_pengganti')->default(false)->after('waktu_tutup');
            $table->string('ruangan_pengganti')->nullable()->after('is_pengganti');
            $table->text('alasan_pengganti')->nullable()->after('ruangan_pengganti');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('absensi', function (Blueprint $table) {
            $table->dropColumn(['is_pengganti', 'ruangan_pengganti', 'alasan_pengganti']);
        });
    }
};
