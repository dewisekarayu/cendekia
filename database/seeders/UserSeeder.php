<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin Cendekia',
            'email' => 'admin@cendekia.test',
            'password' => Hash::make('admin123'),
        ]);
        $admin->assignRole('admin');

        $dosen = User::create([
            'name' => 'Dr. Bagus Wijaya',
            'email' => 'dosen@cendekia.test',
            'password' => Hash::make('dosen123'),
        ]);
        $dosen->assignRole('dosen');

        $mahasiswa = User::create([
            'name' => 'Budi Santoso',
            'email' => 'mahasiswa@cendekia.test',
            'password' => Hash::make('mahasiswa123'),
        ]);
        $mahasiswa->assignRole('mahasiswa');
    }
}