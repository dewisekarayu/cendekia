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
        $mahasiswa = User::where('email', 'mahasiswa@cendekia.test')->first();
        $kelasList = KelasPerkuliahan::all();

        foreach ($kelasList as $kelas) {
            KelasMahasiswa::create([
                'kelas_perkuliahan_id' => $kelas->id,
                'mahasiswa_id' => $mahasiswa->id,
                'tanggal_daftar' => now(),
            ]);
        }
    }
}