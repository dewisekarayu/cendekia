<?php

namespace Database\Seeders;

use App\Models\User;
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

        $admin = User::updateOrCreate(
            ['email' => 'admin@cendekia.test'],
            [
                'name' => 'Admin Cendekia',
                'nip_nim' => 'ADM0001',
                'password' => Hash::make('admin123'),
            ]
        );
        $admin->assignRole('admin');

        for ($i = 1; $i <= 40; $i++) {
            $name = $faker->name('male');
            $email = Str::slug($name, '') . $i . '@cendekia.test';
            $nip = '19' . str_pad((70 + intdiv($i, 5)), 2, '0', STR_PAD_LEFT) . str_pad($i, 6, '0', STR_PAD_LEFT);

            $dosen = User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'nip_nim' => $nip,
                    'password' => Hash::make('dosen123'),
                ]
            );
            $dosen->assignRole('dosen');
        }

        for ($i = 1; $i <= 100; $i++) {
            $name = $faker->name();
            $email = Str::slug($name, '') . $i . '@cendekia.test';
            $nim = '2024' . str_pad($i, 6, '0', STR_PAD_LEFT);

            $mahasiswa = User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'nip_nim' => $nim,
                    'password' => Hash::make('mahasiswa123'),
                ]
            );
            $mahasiswa->assignRole('mahasiswa');
        }
    }
}