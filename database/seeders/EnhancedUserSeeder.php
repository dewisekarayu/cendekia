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

        $programCodes = ['PD', 'KG', 'FAR', 'KEP', 'KEB', 'TI', 'TS', 'TE', 'TIN', 'SI'];
        $programs = ProgramStudi::whereIn('kode_prodi', $programCodes)->get()->keyBy('kode_prodi');
        if ($programs->count() !== count($programCodes)) {
            throw new \RuntimeException('Seeder pengguna membutuhkan 10 program studi. Jalankan ProgramStudiSeeder terlebih dahulu.');
        }

        $dosenPassword = Hash::make('password123');
        $mahasiswaPassword = Hash::make('password123');
        $mainAccounts = [
            ['email' => 'kampuscendekia5@gmail.com', 'name' => 'Muhammad Ridho Pratama'],
            ['email' => 'maylusi431@gmail.com', 'name' => 'Maylusi Widia Kusumaputri'],
            ['email' => 'dewisekarayu56@gmail.com', 'name' => 'Dewi Sayu Maharani'],
        ];
        $lecturerIndex = 0;
        $studentIndex = 0;

        foreach ($programCodes as $programCode) {
            $program = $programs->get($programCode);

            for ($lecturerInProgram = 0; $lecturerInProgram < 2; $lecturerInProgram++) {
                $nameIndex = $lecturerIndex % count($this->namaLakiLaki);
                $nameBase = $this->namaLakiLaki[$nameIndex].' '.$this->namaBelakang[$lecturerIndex % count($this->namaBelakang)];
                $nidn = '1980'.str_pad((string) ($lecturerIndex + 1), 6, '0', STR_PAD_LEFT);
                $lecturer = User::updateOrCreate(
                    ['nip_nim' => $nidn],
                    [
                        'name' => $nameBase.($lecturerIndex % 2 === 0 ? ', M.Kom' : ', M.T'),
                        'email' => Str::slug($nameBase, '.').'@dosen.cendekia.ac.id',
                        'email_verified_at' => now(),
                        'password' => $dosenPassword,
                        'program_studi_id' => $program->id,
                        'status' => 'aktif',
                    ]
                );
                $lecturer->syncRoles('dosen');
                $lecturerIndex++;
            }

            for ($studentInProgram = 0; $studentInProgram < 30; $studentInProgram++) {
                $nim = '2024'.str_pad((string) ($studentIndex + 1), 4, '0', STR_PAD_LEFT);
                $account = $programCode === 'TI' && $studentInProgram < count($mainAccounts)
                    ? $mainAccounts[$studentInProgram]
                    : null;
                if ($account && User::where('email', $account['email'])->where('nip_nim', '!=', $nim)->exists()) {
                    $account = null;
                }
                $isMale = $studentIndex % 2 === 0;
                $firstNameIndex = intdiv($studentIndex, 2);
                $firstName = $isMale
                    ? $this->namaLakiLaki[$firstNameIndex % count($this->namaLakiLaki)]
                    : $this->namaPerempuan[$firstNameIndex % count($this->namaPerempuan)];
                $fullName = $account['name'] ?? $firstName.' '.$this->namaBelakang[$studentIndex % count($this->namaBelakang)];
                $email = $account['email'] ?? Str::slug($fullName, '.').'.'.$nim.'@student.ac.id';
                $student = User::where('email', $email)->first()
                    ?? User::where('nip_nim', $nim)->first()
                    ?? new User();
                $student->fill([
                    'name' => $fullName,
                    'nip_nim' => $nim,
                    'email' => $email,
                    'email_verified_at' => now(),
                    'password' => $mahasiswaPassword,
                    'program_studi_id' => $program->id,
                    'status' => 'aktif',
                ]);
                $student->save();
                $student->syncRoles('mahasiswa');
                $studentIndex++;
            }
        }
    }
}
