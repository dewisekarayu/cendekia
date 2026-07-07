<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ProgramStudi;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil ID Program Studi yang tersedia untuk relasi data
        $programStudiIds = ProgramStudi::pluck('id')->values();
        $prodiId = $programStudiIds->first() ?? null;

        // 1. AKUN ADMIN (1 User)
        // Login -> NIM/NIP: ADM0001 | Pass: admin123
        $admin = User::updateOrCreate(
            ['nip_nim' => 'ADM0001'],
            [
                'name' => 'Admin Cendekia',
                'email' => 'admin.cendekia@gmail.com',
                'password' => Hash::make('admin123'),
            ]
        );
        $admin->syncRoles('admin');

        // 2. AKUN DOSEN (1 User)
        // Login -> NIM/NIP: 1990999999 | Pass: dosen123
        $dosen = User::updateOrCreate(
            ['nip_nim' => '1990999999'],
            [
                'name' => 'Dr. Ahmad Subagjo, M.Kom',
                'email' => 'dosen.cendekia@gmail.com', // Menggunakan gmail.com asli
                'password' => Hash::make('dosen123'),
                'program_studi_id' => $prodiId,
            ]
        );
        $dosen->syncRoles('dosen');

        // 3. AKUN MAHASISWA (1 User)
        // Login -> NIM/NIP: 2024999999 | Pass: mahasiswa123
        $mahasiswa = User::updateOrCreate(
            ['nip_nim' => '2024999999'],
            [
                'name' => 'Mahasiswa Test Akun',
                'email' => 'mahasiswa.cendekia@gmail.com', // Menggunakan gmail.com asli
                'password' => Hash::make('mahasiswa123'),
                'program_studi_id' => $prodiId,
            ]
        );
        $mahasiswa->syncRoles('mahasiswa');
    }
}