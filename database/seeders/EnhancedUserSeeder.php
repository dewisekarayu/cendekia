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
        'Rizal Pratama',
        'Maya Lestari',
        'Fajar Nugroho',
        'Sinta Paramita',
        'Bima Arya',
        'Ratih Wulandari',
        'Hendra Wijaya',
        'Dewi Maharani'
    ];

    public function run(): void
    {
        // Create or get program studi
        $programStudi = collect([
            ['kode_prodi' => 'TI', 'nama_prodi' => 'Teknik Informatika', 'jenjang' => 'S1'],
            ['kode_prodi' => 'SI', 'nama_prodi' => 'Sistem Informasi', 'jenjang' => 'S1'],
            ['kode_prodi' => 'PPLG', 'nama_prodi' => 'Pengembangan Perangkat Lunak', 'jenjang' => 'S1'],
            ['kode_prodi' => 'TIFO', 'nama_prodi' => 'Teknologi Informasi', 'jenjang' => 'S1'],
        ])->mapWithKeys(function (array $item) {
            $prodi = ProgramStudi::updateOrCreate(['kode_prodi' => $item['kode_prodi']], $item);
            return [$item['kode_prodi'] => $prodi->id];
        });

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

        // =====================================================
        // 2. DOSEN ACCOUNTS (10 dosen)
        // =====================================================
        $prodiCodes = $programStudi->keys()->values();
        $dosenPassword = Hash::make('password123');

        $dosenData = [];
        foreach ($this->namaDosen as $index => $nama) {
            $gelarDepan = $index % 5 === 0 ? 'Prof. Dr. ' : ($index % 2 === 0 ? 'Dr. ' : '');
            $gelarBelakang = $index % 3 === 0 ? ', M.Kom' : ', S.Kom., M.T';
            $nidn = '197900' . str_pad((string) ($index + 1), 5, '0', STR_PAD_LEFT);
            $slug = Str::slug($nama, '.');
            $prodiCode = $prodiCodes[$index % $prodiCodes->count()];
            $prodiId = $programStudi[$prodiCode];

            $dosen = User::updateOrCreate(
                ['nip_nim' => $nidn],
                [
                    'name' => $gelarDepan . $nama . $gelarBelakang,
                    'email' => $slug . '@dosen.cendekia.ac.id',
                    'email_verified_at' => now(),
                    'password' => $dosenPassword,
                    'program_studi_id' => $prodiId,
                    'status' => 'aktif',
                ]
            );
            $dosen->syncRoles('dosen');

            $dosenData[$index] = [
                'id' => $dosen->id,
                'name' => $dosen->name,
                'email' => $dosen->email,
                'prodi_code' => $prodiCode,
            ];
        }

        // =====================================================
        // 3. MAHASISWA ACCOUNTS (100 total: 50 laki-laki, 50 perempuan)
        // =====================================================
        $mahasiswaPassword = Hash::make('password123');

        // Three main accounts for presentation
        $mainAccounts = [
            [
                'email' => 'kampuscendekia5@gmail.com',
                'name' => 'Muhammad Ridho Pratama',
                'nim' => '20241001',
                'gender' => 'laki-laki',
                'prodi' => 'TI'
            ],
            [
                'email' => 'maylusi431@gmail.com',
                'name' => 'Maylusi Widia Kusumaputri',
                'nim' => '20241002',
                'gender' => 'perempuan',
                'prodi' => 'TI'
            ],
            [
                'email' => 'dewisekarayu56@gmail.com',
                'name' => 'Dewi Sayu Maharani',
                'nim' => '20241003',
                'gender' => 'perempuan',
                'prodi' => 'TI'
            ],
        ];

        // Create main accounts
        foreach ($mainAccounts as $account) {
            $mahasiswa = User::updateOrCreate(
                ['nip_nim' => $account['nim']],
                [
                    'name' => $account['name'],
                    'email' => $account['email'],
                    'email_verified_at' => now(),
                    'password' => $mahasiswaPassword,
                    'program_studi_id' => $programStudi[$account['prodi']],
                    'status' => 'aktif',
                ]
            );
            $mahasiswa->syncRoles('mahasiswa');
        }

        // Create remaining 97 mahasiswa (50 laki-laki, 47 perempuan for balance)
        $lakiCounter = 1; // Start from 1 since one main account is laki-laki
        $perempuanCounter = 2; // Start from 2 since two main accounts are perempuan

        for ($i = 4; $i <= 100; $i++) {
            // Create 49 more laki-laki (total 50 dengan main account)
            // Create 47 more perempuan (total 49 dengan 2 main accounts)
            
            $alternateIndex = $i - 4;
            
            if ($alternateIndex < 49) {
                // Create laki-laki
                $nama = $this->namaLakiLaki[$lakiCounter - 1] . ' ' . $this->namaBelakang[($lakiCounter - 1) % count($this->namaBelakang)];
                $lakiCounter++;
            } else {
                // Create perempuan
                $perempuanIndex = $alternateIndex - 49;
                if ($perempuanIndex >= count($this->namaPerempuan)) {
                    continue;
                }
                $nama = $this->namaPerempuan[$perempuanIndex] . ' ' . $this->namaBelakang[$perempuanIndex % count($this->namaBelakang)];
            }

            $nim = '2024' . str_pad((string) $i, 4, '0', STR_PAD_LEFT);
            $emailName = Str::slug($nama, '.');
            $emailDomain = ['gmail.com', 'yahoo.com', 'outlook.com', 'mail.com', 'student.ac.id'];
            $domain = $emailDomain[($i - 4) % count($emailDomain)];
            $email = $emailName . '.' . str_pad((string) ($i - 3), 2, '0', STR_PAD_LEFT) . '@' . $domain;

            // Distribute students across program studi
            $prodiCode = $prodiCodes[($i - 4) % $prodiCodes->count()];

            $mahasiswa = User::updateOrCreate(
                ['nip_nim' => $nim],
                [
                    'name' => $nama,
                    'email' => $email,
                    'email_verified_at' => now(),
                    'password' => $mahasiswaPassword,
                    'program_studi_id' => $programStudi[$prodiCode],
                    'status' => 'aktif',
                ]
            );
            $mahasiswa->syncRoles('mahasiswa');
        }
    }
}
