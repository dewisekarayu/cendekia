<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ProgramStudi;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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

    $faker = \Faker\Factory::create('id_ID');

    // =====================================================
    // 1. AKUN ADMIN
    // Login -> NIM/NIP: ADM0001 | Password: admin123
    // =====================================================
    $admin = User::updateOrCreate(
        ['nip_nim' => 'ADM0001'],
        [
            'name' => 'Admin Cendekia',
            'email' => 'admin.cendekia@gmail.com',
            'password' => Hash::make('admin123'),
        ]
    );

    $admin->syncRoles('admin');

    // =====================================================
    // 2. AKUN DOSEN (40 User)
    // Password: dosen123
    // =====================================================

    $dosenNames = [
        'Dr. Ahmad Subagjo, M.Kom',
        'Siti Nurhaliza, S.Ds, M.Ds',
        'Budi Darmawan, S.E, M.M',
        'Maya Lestari, M.Pd',
        'Dr. Rina Kartika, M.T',
        'Fajar Pratama, M.Kom',
        'Dewi Anggraini, M.Stat',
        'Hendra Wijaya, M.Cs',
        'Ratna Puspitasari, M.Ds',
        'Agus Santoso, M.M',
    ];

    for ($i = 1; $i <= 40; $i++) {

        $baseName = $dosenNames[($i - 1) % count($dosenNames)];
        $name = $i <= count($dosenNames)
            ? $baseName
            : $baseName . ' ' . ceil($i / count($dosenNames));

        $cleanName = str_replace(
            ['Dr.', 'S.E', 'S.Ds', 'M.Ds', 'M.Kom', 'M.M', 'M.Pd', 'M.T', 'M.Stat', 'M.Cs', ',', '.'],
            '',
            $name
        );

        $email = 'dosen.' . Str::slug($cleanName, '.') . $i . '@cendekia.test';

        $nip = '19'
            . str_pad((string)(70 + intdiv($i, 5)), 2, '0', STR_PAD_LEFT)
            . str_pad((string)$i, 6, '0', STR_PAD_LEFT);

        $dosen = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'nip_nim' => $nip,
                'email' => $email,
                'password' => Hash::make('dosen123'),
                'program_studi_id' => $programStudiIds->isNotEmpty()
                    ? $programStudiIds[($i - 1) % $programStudiIds->count()]
                    : $prodiId,
            ]
        );

        $dosen->syncRoles('dosen');
    }

    // =====================================================
    // 3. AKUN MAHASISWA (200 User)
    // Password: mahasiswa123
    // =====================================================

    for ($i = 1; $i <= 200; $i++) {

        $name = $faker->name();

        $email = 'mahasiswa.'
            . Str::slug($name, '.')
            . $i
            . '@cendekia.test';

        $nim = '2024'
            . str_pad((string)$i, 6, '0', STR_PAD_LEFT);

        $mahasiswa = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'nip_nim' => $nim,
                'email' => $email,
                'password' => Hash::make('mahasiswa123'),
                'program_studi_id' => $programStudiIds->isNotEmpty()
                    ? $programStudiIds[($i - 1) % $programStudiIds->count()]
                    : $prodiId,
            ]
        );

        $mahasiswa->syncRoles('mahasiswa');
    }
}