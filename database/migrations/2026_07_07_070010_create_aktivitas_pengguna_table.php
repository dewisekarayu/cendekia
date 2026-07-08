<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aktivitas_pengguna', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('kelas_perkuliahan_id')->nullable()->constrained('kelas_perkuliahan')->nullOnDelete();
            $table->string('aksi', 80);
            $table->text('deskripsi')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamp('terjadi_pada');
            $table->timestamps();

            $table->index(['aksi', 'terjadi_pada']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aktivitas_pengguna');
    }
};
