<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ProgramStudi;
use Faker\Factory as FakerFactory;
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
        $faker = FakerFactory::create('id_ID');
        $programStudiIds = ProgramStudi::pluck('id')->values();

        // 1. SEEDER ACCOUNT ADMIN
        $admin = User::updateOrCreate(
            ['email' => 'admin@cendekia.test'],
            [
                'name' => 'Admin Cendekia',
                'nip_nim' => 'ADM0001',
                'password' => Hash::make('admin123'),
            ]
        );
        $admin->syncRoles('admin');

        // Daftar Nama Dosen yang Bagus untuk dipakai Variasi
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

        // 2. SEEDER ACCOUNT DOSEN (40 Data)
        for ($i = 1; $i <= 40; $i++) {
            $baseName = $dosenNames[($i - 1) % count($dosenNames)];
            // Memberi nomor jika nama berulang agar tetap unik
            $name = $i <= count($dosenNames) ? $baseName : $baseName . ' ' . ceil($i / count($dosenNames));
            
            // Generate Email berdasarkan slug nama asli dosen agar estetik
            $cleanName = str_replace(['Dr.', 'S.E', 'S.Ds', 'M.Ds', 'M.Kom', 'M.M', 'M.Pd', 'M.T', 'M.Stat', 'M.Cs', ',', '.'], '', $name);
            $email = 'dosen.' . Str::slug($cleanName, '.') . $i . '@cendekia.test';
            
            $nip = '19' . str_pad((string)(70 + intdiv($i, 5)), 2, '0', STR_PAD_LEFT) . str_pad((string)$i, 6, '0', STR_PAD_LEFT);

            $dosen = User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'nip_nim' => $nip,
                    'password' => Hash::make('dosen123'),
                    'program_studi_id' => $programStudiIds->isNotEmpty() ? $programStudiIds[($i - 1) % $programStudiIds->count()] : null,
                ]
            );
            $dosen->syncRoles('dosen');
        }

        // 3. SEEDER ACCOUNT MAHASISWA (200 Data)
        for ($i = 1; $i <= 200; $i++) {
            $name = $faker->name();
            $email = 'mahasiswa.' . Str::slug($name, '.') . $i . '@cendekia.test';
            $nim = '2024' . str_pad((string)$i, 6, '0', STR_PAD_LEFT);

            $mahasiswa = User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'nip_nim' => $nim,
                    'password' => Hash::make('mahasiswa123'),
                    'program_studi_id' => $programStudiIds->isNotEmpty() ? $programStudiIds[($i - 1) % $programStudiIds->count()] : null,
                ]
            );
            $mahasiswa->syncRoles('mahasiswa');
        }
    }
}
