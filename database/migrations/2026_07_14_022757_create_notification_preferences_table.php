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
        Schema::create('notification_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('materi_baru')->default(true);
            $table->boolean('tugas_baru')->default(true);
            $table->boolean('pengumuman_baru')->default(true);
            $table->boolean('nilai_baru')->default(true);
            $table->boolean('absensi_dibuka')->default(true);
            $table->boolean('pengumpulan_tugas')->default(true);
            $table->boolean('pesan_baru')->default(true);
            $table->boolean('pengguna_baru')->default(true);
            $table->timestamps();
            
            $table->unique('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_preferences');
    }
};
