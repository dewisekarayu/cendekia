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
        // Add dosen_pa_id to users (mahasiswa)
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('dosen_pa_id')->nullable()->constrained('users')->onDelete('set null');
        });

        Schema::create('krs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('semester_id')->constrained('semesters')->onDelete('cascade');
            $table->enum('status', ['draft', 'diajukan', 'disetujui', 'ditolak'])->default('draft');
            $table->text('catatan_dosen')->nullable();
            $table->timestamps();
        });

        Schema::create('krs_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('krs_id')->constrained('krs')->onDelete('cascade');
            $table->foreignId('kelas_perkuliahan_id')->constrained('kelas_perkuliahan')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('krs_items');
        Schema::dropIfExists('krs');
        
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['dosen_pa_id']);
            $table->dropColumn('dosen_pa_id');
        });
    }
};
