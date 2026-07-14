<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Materi;
use App\Models\KelasPerkuliahan;
use App\Services\NotificationService;
use Illuminate\Console\Command;

class TestEmailNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:test {type=materi}';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected $description = 'Test email notifications (types: materi, tugas, pengumuman, nilai, absensi, pengumpulan, pesan, user)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $type = $this->argument('type');
        
        $this->info("Testing {$type} email notification...");

        try {
            switch ($type) {
                case 'materi':
                    $this->testMateriEmail();
                    break;
                case 'tugas':
                    $this->testTugasEmail();
                    break;
                case 'pengumuman':
                    $this->testPengumumanEmail();
                    break;
                case 'nilai':
                    $this->testNilaiEmail();
                    break;
                case 'absensi':
                    $this->testAbsensiEmail();
                    break;
                case 'pengumpulan':
                    $this->testPengumpulanEmail();
                    break;
                case 'pesan':
                    $this->testPesanEmail();
                    break;
                case 'user':
                    $this->testUserEmail();
                    break;
                default:
                    $this->error("Tipe email tidak dikenal: {$type}");
                    $this->info("Tipe yang tersedia: materi, tugas, pengumuman, nilai, absensi, pengumpulan, pesan, user");
                    return;
            }

            $this->info("✓ Email {$type} queued successfully!");
            $this->info("Run 'php artisan queue:work' to process the queue.");
        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
        }
    }

    private function testMateriEmail()
    {
        $dosen = User::role('dosen')->first() ?? User::create([
            'name' => 'Test Dosen',
            'email' => 'dosen@test.com',
            'password' => bcrypt('password'),
        ]);

        $mahasiswa = User::role('mahasiswa')->first() ?? User::create([
            'name' => 'Test Mahasiswa',
            'email' => 'mahasiswa@test.com',
            'password' => bcrypt('password'),
        ]);

        $kelas = KelasPerkuliahan::first();
        
        if (!$kelas) {
            $this->error("Tidak ada kelas yang tersedia. Silakan buat kelas terlebih dahulu.");
            return;
        }

        $materi = Materi::create([
            'kelas_perkuliahan_id' => $kelas->id,
            'judul' => 'Test Materi - ' . now()->format('Y-m-d H:i:s'),
            'deskripsi' => 'Ini adalah test materi untuk testing email notification',
            'pertemuan_ke' => 1,
        ]);

        NotificationService::notifyMateriBaru($materi, $dosen);
    }

    private function testTugasEmail()
    {
        $this->info("Testing tugas email...");
    }

    private function testPengumumanEmail()
    {
        $this->info("Testing pengumuman email...");
    }

    private function testNilaiEmail()
    {
        $this->info("Testing nilai email...");
    }

    private function testAbsensiEmail()
    {
        $this->info("Testing absensi email...");
    }

    private function testPengumpulanEmail()
    {
        $this->info("Testing pengumpulan email...");
    }

    private function testPesanEmail()
    {
        $this->info("Testing pesan email...");
    }

    private function testUserEmail()
    {
        $user = User::create([
            'name' => 'Test User - ' . now()->format('Y-m-d H:i:s'),
            'email' => 'testuser' . now()->timestamp . '@test.com',
            'password' => bcrypt('password'),
            'nip_nim' => 'TEST' . now()->timestamp,  // Required field
        ]);

        NotificationService::notifyPenggunaBaru($user, 'mahasiswa');
    }
}
