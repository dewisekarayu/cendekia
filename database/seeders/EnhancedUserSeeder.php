<?php

namespace Database\Seeders;

use App\Models\ProgramStudi;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EnhancedUserSeeder extends Seeder
{
    /**
     * Nama-nama laki-laki Indonesia
     */
    private array $namaLakiLaki = [
        'Ahmad', 'Ridho', 'Budi', 'Doni', 'Eka', 'Fahri', 'Gani', 'Hendra', 'Indra', 'Jaka',
        'Kuat', 'Lukman', 'Miftah', 'Noval', 'Oscar', 'Pandu', 'Qomaruddin', 'Raka', 'Saiful', 'Teguh',
        'Ujang', 'Vicky', 'Wahyu', 'Yonatan', 'Zainal', 'Agus', 'Bagas', 'Citra', 'Dimas', 'Endra',
        'Fajar', 'Galih', 'Hario', 'Ilham', 'Joko', 'Karim', 'Luthfi', 'Malik', 'Nanda', 'Orang',
        'Parman', 'Rafi', 'Sutrisno', 'Tommy', 'Umar', 'Viyan', 'Wirawan', 'Yusuf', 'Zaky', 'Arianto'
    ];

    /**
     * Nama-nama perempuan Indonesia
     */
    private array $namaPerempuan = [
        'Alya', 'Bella', 'Chitra', 'Dewi', 'Elisa', 'Fatima', 'Gita', 'Hani', 'Intan', 'Jihan',
        'Khansa', 'Laila', 'Maya', 'Nita', 'Olivia', 'Putri', 'Qonita', 'Ratih', 'Sita', 'Tina',
        'Uswah', 'Vina', 'Wiwid', 'Yani', 'Zahira', 'Asma', 'Bunga', 'Cinta', 'Dina', 'Eka',
        'Fitri', 'Galuh', 'Hilda', 'Ika', 'Janna', 'Kayla', 'Lina', 'Mira', 'Nadia', 'Okta',
        'Prima', 'Ririn', 'Sinta', 'Tifani', 'Ulia', 'Vandi', 'Wisda', 'Yunita', 'Zara', 'Astrid'
    ];

    /**
     * Nama belakang Indonesia
     */
    private array $namaBelakang = [
        'Pratama', 'Saputra', 'Wijaya', 'Kusuma', 'Gunawan', 'Santoso', 'Hermawan', 'Nugroho',
        'Sugiono', 'Hidayat', 'Ramadhan', 'Setiawan', 'Suryanto', 'Wibowo', 'Hartono', 'Sugianto',
        'Pramono', 'Riyanto', 'Sumardi', 'Sutrisno', 'Tarwoto', 'Untoro', 'Verdianto', 'Wardoyo',
        'Xtofanus', 'Yanuar', 'Zainuddin', 'Abdurachman', 'Budiono', 'Cahyono', 'Darmawan', 'Efendi',
        'Firmansyah', 'Gunardi', 'Hermansyah', 'Ismail', 'Jatmiko', 'Kartawinata', 'Lamadani', 'Marhaban'
    ];

    /**
     * Nama-nama dosen Indonesia
     */
    private array $namaDosen = [
        'Ahmad Subagjo',
        'Nadia Kurniasari',
        'Rizal Pratama'
    ];

    public function run(): void
    {
        // Get all program studi with their fakultas
        $allProdis = ProgramStudi::with('fakultas')->get();

        // =====================================================
        // 1. ADMIN ACCOUNT
        // =====================================================
        $admin = User::updateOrCreate(
            ['nip_nim' => 'ADM0001'],
            [
                'name' => 'Admin Cendekia',
                'email' => 'admin@cendekia.ac.id',
                'email_verified_at' => now(),
                'password' => Hash::make('admin123'),
                'status' => 'aktif',
            ]
        );
        $admin->syncRoles('admin');

        $dosenPassword = Hash::make('password123');
        $mahasiswaPassword = Hash::make('password123');

        // Main accounts for TI
        $mainAccounts = [
            '20241001' => [
                'email' => 'kampuscendekia5@gmail.com',
                'name' => 'Muhammad Ridho Pratama',
                'gender' => 'laki-laki',
            ],
            '20241002' => [
                'email' => 'maylusi431@gmail.com',
                'name' => 'Maylusi Widia Kusumaputri',
                'gender' => 'perempuan',
            ],
            '20241003' => [
                'email' => 'dewisekarayu56@gmail.com',
                'name' => 'Dewi Sayu Maharani',
                'gender' => 'perempuan',
            ],
        ];

        $dosenIndex = 0;
        $mahasiswaIndex = 0;

        foreach ($allProdis as $prodi) {
            // Generate 2 Dosen per prodi (Total = 50 Dosen across 25 prodi)
            for ($d = 1; $d <= 2; $d++) {
                $gelarDepan = $dosenIndex % 5 === 0 ? 'Prof. Dr. ' : ($dosenIndex % 2 === 0 ? 'Dr. ' : '');
                $gelarBelakang = $dosenIndex % 3 === 0 ? ', M.Kom' : ', S.Kom., M.T';
                $nidn = '197900' . str_pad((string) ($dosenIndex + 1), 5, '0', STR_PAD_LEFT);
                
                // Pick name based on index, loop if out of bounds
                if ($dosenIndex < count($this->namaLakiLaki)) {
                    $namaDasar = $this->namaLakiLaki[$dosenIndex];
                } else {
                    $namaDasar = $this->namaLakiLaki[$dosenIndex % count($this->namaLakiLaki)] . ' ' . $this->namaBelakang[$dosenIndex % count($this->namaBelakang)];
                }
                
                $nama = $gelarDepan . $namaDasar . $gelarBelakang;
                $slug = Str::slug($namaDasar, '.');

                $dosen = User::updateOrCreate(
                    ['nip_nim' => $nidn],
                    [
                        'name' => $nama,
                        'email' => $slug . '@dosen.cendekia.ac.id',
                        'email_verified_at' => now(),
                        'password' => $dosenPassword,
                        'program_studi_id' => $prodi->id,
                        'status' => 'aktif',
                    ]
                );
                $dosen->syncRoles('dosen');
                $dosenIndex++;
            }

            // Generate 4 Mahasiswa per prodi (Total = 100 Mahasiswa across 25 prodi)
            for ($m = 1; $m <= 4; $m++) {
                $nim = '2024' . str_pad((string) ($mahasiswaIndex + 1), 4, '0', STR_PAD_LEFT);

                // Check if this NIM is one of our main accounts (ensure they get mapped to TI, or just let them take these NIMs wherever they fall, wait, main accounts are specifically for TI)
                // Actually, let's inject main accounts into TI directly.
                if ($prodi->kode_prodi === 'TI' && isset($mainAccounts[$nim])) {
                    $account = $mainAccounts[$nim];
                    $mahasiswa = User::updateOrCreate(
                        ['nip_nim' => $nim],
                        [
                            'name' => $account['name'],
                            'email' => $account['email'],
                            'email_verified_at' => now(),
                            'password' => $mahasiswaPassword,
                            'program_studi_id' => $prodi->id,
                            'status' => 'aktif',
                        ]
                    );
                } else {
                    $isLaki = $mahasiswaIndex % 2 === 0;
                    $namaDepan = $isLaki ? $this->namaLakiLaki[($mahasiswaIndex / 2) % count($this->namaLakiLaki)] : $this->namaPerempuan[floor($mahasiswaIndex / 2) % count($this->namaPerempuan)];
                    $namaLengkap = $namaDepan . ' ' . $this->namaBelakang[$mahasiswaIndex % count($this->namaBelakang)];
                    $emailName = Str::slug($namaLengkap, '.');
                    
                    $mahasiswa = User::updateOrCreate(
                        ['nip_nim' => $nim],
                        [
                            'name' => $namaLengkap,
                            'email' => $emailName . '@student.ac.id',
                            'email_verified_at' => now(),
                            'password' => $mahasiswaPassword,
                            'program_studi_id' => $prodi->id,
                            'status' => 'aktif',
                        ]
                    );
                }
                $mahasiswa->syncRoles('mahasiswa');
                $mahasiswaIndex++;
            }
        }
    }
}
