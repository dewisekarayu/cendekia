<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Kolom-kolom ini sudah dipakai di App\Models\KelasPerkuliahan (fillable & scope)
     * tapi belum ada di migration aslinya.
     */
    public function up(): void
    {
        Schema::table('kelas_perkuliahan', function (Blueprint $table) {
            if (!Schema::hasColumn('kelas_perkuliahan', 'program_studi_id')) {
                $table->foreignId('program_studi_id')
                    ->nullable()
                    ->after('mata_kuliah_id')
                    ->constrained('program_studi')
                    ->nullOnDelete();
            }
            if (!Schema::hasColumn('kelas_perkuliahan', 'tahun_akademik')) {
                $table->string('tahun_akademik')->nullable()->after('kode_kelas');
            }
            if (!Schema::hasColumn('kelas_perkuliahan', 'kuota_mahasiswa')) {
                $table->integer('kuota_mahasiswa')->default(40)->after('ruangan');
            }
            if (!Schema::hasColumn('kelas_perkuliahan', 'status_kelas')) {
                $table->string('status_kelas')->default('aktif')->after('kuota_mahasiswa');
            }
            if (!Schema::hasColumn('kelas_perkuliahan', 'dosen_pengampu')) {
                $table->json('dosen_pengampu')->nullable()->after('dosen_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('kelas_perkuliahan', function (Blueprint $table) {
            if (Schema::hasColumn('kelas_perkuliahan', 'program_studi_id')) {
                $table->dropConstrainedForeignId('program_studi_id');
            }
            $table->dropColumn(['tahun_akademik', 'kuota_mahasiswa', 'status_kelas', 'dosen_pengampu']);
        });
    }
};
