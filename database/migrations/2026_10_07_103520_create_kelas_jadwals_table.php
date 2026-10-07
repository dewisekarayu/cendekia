<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kelas_jadwals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_perkuliahan_id')->constrained('kelas_perkuliahan')->onDelete('cascade');
            $table->string('hari');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->string('ruangan')->nullable();
            $table->timestamps();
        });

        // Pindahkan data dari tabel kelas_perkuliahan ke kelas_jadwals
        $kelasPerkuliahans = DB::table('kelas_perkuliahan')->get();
        foreach ($kelasPerkuliahans as $kelas) {
            if ($kelas->hari && $kelas->jam_mulai && $kelas->jam_selesai) {
                DB::table('kelas_jadwals')->insert([
                    'kelas_perkuliahan_id' => $kelas->id,
                    'hari'                 => $kelas->hari,
                    'jam_mulai'            => $kelas->jam_mulai,
                    'jam_selesai'          => $kelas->jam_selesai,
                    'ruangan'              => $kelas->ruangan,
                    'created_at'           => now(),
                    'updated_at'           => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelas_jadwals');
    }
};
