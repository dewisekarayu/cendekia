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
        $programStudi = collect([
            ['kode_prodi' => 'TI', 'nama_prodi' => 'Teknik Informatika', 'jenjang' => 'S1'],
            ['kode_prodi' => 'SI', 'nama_prodi' => 'Sistem Informasi', 'jenjang' => 'S1'],
            ['kode_prodi' => 'RPL', 'nama_prodi' => 'Rekayasa Perangkat Lunak', 'jenjang' => 'S1'],
            ['kode_prodi' => 'DS', 'nama_prodi' => 'Sains Data', 'jenjang' => 'S1'],
        ])->mapWithKeys(function (array $item) {
            $prodi = ProgramStudi::updateOrCreate(['kode_prodi' => $item['kode_prodi']], $item);

            return [$item['kode_prodi'] => $prodi->id];
        });

        // =====================================================
        // 1. AKUN ADMIN
        // Login -> NIM/NIP: ADM0001 | Password: admin123
        // =====================================================
        $adminPassword = Hash::make('admin123');
        $dosenPassword = Hash::make('dosen123');
        $mahasiswaPassword = Hash::make('mahasiswa123');

        $admin = User::updateOrCreate(
            ['nip_nim' => 'ADM0001'],
            [
                'name' => 'Admin Cendekia',
                'email' => 'admin@cendekia.ac.id',
                'email_verified_at' => now(),
                'password' => $adminPassword,
            ]
        );
        $admin->syncRoles('admin');

        $namaDosen = [
            'Ahmad Subagjo', 'Nadia Kurniasari', 'Rizal Pratama', 'Maya Lestari',
            'Fajar Nugroho', 'Sinta Paramita', 'Bima Arya', 'Ratih Wulandari',
            'Hendra Wijaya', 'Dewi Maharani', 'Arif Setiawan', 'Laras Puspita',
            'Raka Firmansyah', 'Intan Permatasari', 'Yusuf Maulana', 'Dian Safitri',
            'Agus Hermawan', 'Kartika Sari', 'Farhan Hidayat', 'Mei Anggraini',
            'Teguh Santoso', 'Putri Amelia', 'Bagas Prakoso', 'Citra Andini',
            'Ilham Ramadhan', 'Vina Oktaviani', 'Galih Saputra', 'Anisa Rahma',
            'Reza Mahendra', 'Fitri Handayani', 'Aditya Wibowo', 'Niken Pertiwi',
            'Yoga Pranata', 'Rini Marlina', 'Dimas Pamungkas', 'Aulia Zahra',
            'Eko Purnomo', 'Sarah Khairunnisa', 'Joko Susilo', 'Monica Febriani',
        ];

        $kodeProdi = $programStudi->keys()->values();

        foreach ($namaDosen as $index => $nama) {
            $gelarDepan = $index % 5 === 0 ? 'Prof. Dr. ' : ($index % 2 === 0 ? 'Dr. ' : '');
            $gelarBelakang = $index % 3 === 0 ? ', M.Kom' : ', S.Kom., M.T';
            $nip = '1979' . str_pad((string) ($index + 1), 6, '0', STR_PAD_LEFT);
            $slug = Str::slug($nama, '.');
            $prodiId = $programStudi[$kodeProdi[$index % $kodeProdi->count()]];

            $dosen = User::updateOrCreate(
                ['nip_nim' => $nip],
                [
                    'name' => $gelarDepan . $nama . $gelarBelakang,
                    'email' => $slug . '@dosen.cendekia.ac.id',
                    'email_verified_at' => now(),
                    'password' => $dosenPassword,
                    'program_studi_id' => $prodiId,
                ]
            );
            $dosen->syncRoles('dosen');
        }

        $namaDepan = ['Alya', 'Bagus', 'Cahya', 'Daffa', 'Elsa', 'Farrel', 'Gita', 'Hasan', 'Indah', 'Jihan', 'Kevin', 'Laila', 'Miko', 'Nabila', 'Oscar', 'Priska', 'Qori', 'Rafi', 'Salma', 'Tio'];
        $namaBelakang = ['Prameswari', 'Saputra', 'Mahendra', 'Putri', 'Ramadhan', 'Wibowo', 'Salsabila', 'Nugraha', 'Azzahra', 'Kurniawan'];

        for ($i = 1; $i <= 200; $i++) {
            $prodiCode = $kodeProdi[($i - 1) % $kodeProdi->count()];
            $name = $namaDepan[($i - 1) % count($namaDepan)] . ' ' . $namaBelakang[(int) floor(($i - 1) / count($namaDepan)) % count($namaBelakang)];
            $nim = '2024' . str_pad((string) $i, 6, '0', STR_PAD_LEFT);
            $emailName = Str::slug($name, '.') . '.' . str_pad((string) $i, 3, '0', STR_PAD_LEFT);

            $mahasiswa = User::updateOrCreate(
                ['nip_nim' => $nim],
                [
                    'name' => $name,
                    'email' => $emailName . '@student.cendekia.ac.id',
                    'email_verified_at' => now(),
                    'password' => $mahasiswaPassword,
                    'program_studi_id' => $programStudi[$prodiCode],
                ]
            );
            $mahasiswa->syncRoles('mahasiswa');
        }
    }
}
