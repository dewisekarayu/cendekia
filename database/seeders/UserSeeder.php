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

        $admin = User::updateOrCreate(
            ['email' => 'admin@cendekia.test'],
            [
                'name' => 'Admin Cendekia',
                'password' => Hash::make('admin123'),
            ]
        );
        $admin->syncRoles('admin');

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
            $name = $i <= count($dosenNames) ? $baseName : $baseName . ' ' . ceil($i / count($dosenNames));
            $email = 'dosen.' . Str::slug(str_replace(['Dr.', 'S.E', 'S.Ds', 'M.Ds', 'M.Kom', 'M.M', 'M.Pd', 'M.T', 'M.Stat', 'M.Cs'], '', $name), '.') . '@cendekia.test';
            $dosen = User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'password' => Hash::make('dosen123'),
                    'program_studi_id' => $programStudiIds->isNotEmpty() ? $programStudiIds[($i - 1) % $programStudiIds->count()] : null,
                ]
            );
            $dosen->syncRoles('dosen');
        }

        for ($i = 1; $i <= 200; $i++) {
            $name = $faker->unique()->name();
            $email = 'mahasiswa.' . Str::slug($name, '.') . '.' . str_pad((string) $i, 3, '0', STR_PAD_LEFT) . '@cendekia.test';
            $mahasiswa = User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'password' => Hash::make('mahasiswa123'),
                    'program_studi_id' => $programStudiIds->isNotEmpty() ? $programStudiIds[($i - 1) % $programStudiIds->count()] : null,
                ]
            );
            $mahasiswa->syncRoles('mahasiswa');
        }
    }
}
