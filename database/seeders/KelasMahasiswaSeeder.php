<?php

namespace Database\Seeders;

use App\Models\KelasMahasiswa;
use App\Models\KelasPerkuliahan;
use App\Models\User;
use Illuminate\Database\Seeder;

class KelasMahasiswaSeeder extends Seeder
{
    public function run(): void
    {
        $mahasiswa = User::role('mahasiswa')->first();
        $kelasList = KelasPerkuliahan::all();

        if (! $mahasiswa) {
            return;
        }

        foreach ($kelasList as $kelas) {
            KelasMahasiswa::firstOrCreate(
                [
                    'kelas_perkuliahan_id' => $kelas->id,
                    'mahasiswa_id' => $mahasiswa->id,
                ],
                [
                    'tanggal_daftar' => now(),
                ]
            );
        }
    }
}