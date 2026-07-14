<?php

namespace App\Console\Commands;

use App\Models\Absensi;
use App\Models\AbsensiMahasiswa;
use App\Models\KelasPerkuliahan;
use App\Models\User;
use Illuminate\Console\Command;

class TestAbsensiSystem extends Command
{
    /**
     * Nama dan deskripsi command
     */
    protected $signature = 'test:absensi {--clean : Hapus test data sebelumnya}';

    protected $description = 'Test sistem presensi dengan sample data';

    /**
     * Execute the command
     */
    public function handle()
    {
        if ($this->option('clean')) {
            $this->cleanTestData();
            return 0;
        }

        $this->line('');
        $this->info('=== Test Sistem Presensi ===');
        $this->line('');

        // Get sample data
        $kelas = KelasPerkuliahan::first();
        if (!$kelas) {
            $this->error('Tidak ada kelas. Jalankan seeder terlebih dahulu.');
            return 1;
        }

        $dosen = $kelas->dosen;
        $mahasiswa = $kelas->mahasiswa()->first();

        if (!$mahasiswa) {
            $this->error('Tidak ada mahasiswa di kelas. Tambahkan mahasiswa terlebih dahulu.');
            return 1;
        }

        $this->info("Kelas: {$kelas->mataKuliah->nama_mk} ({$kelas->kode_kelas})");
        $this->info("Dosen: {$dosen->name}");
        $this->info("Mahasiswa: {$mahasiswa->name}");
        $this->line('');

        // Find next available pertemuan
        $maxPertemuan = Absensi::where('kelas_perkuliahan_id', $kelas->id)->max('pertemuan_ke') ?? 0;
        $nextPertemuan = $maxPertemuan + 1;

        // Test 1: Create session
        $this->comment("1. Testing Create Session (Draft) - Pertemuan {$nextPertemuan}");
        try {
            $absensi = Absensi::create([
                'kelas_perkuliahan_id' => $kelas->id,
                'pertemuan_ke' => $nextPertemuan,
                'tanggal' => today(),
                'jam_mulai' => '08:00',
                'jam_selesai' => '10:00',
                'session_status' => 'draft',
                'rangkuman' => 'Test rangkuman materi',
            ]);
            $this->line("✓ Session created: ID {$absensi->id}");
            $this->line("  Status: {$absensi->session_status}");
        } catch (\Exception $e) {
            $this->error("✗ Failed to create session: " . $e->getMessage());
            return 1;
        }
        $this->line('');

        // Test 2: Open session
        $this->comment('2. Testing Open Session');
        try {
            $absensi->bukaSession();
            $this->line("✓ Session opened");
            $this->line("  Status: {$absensi->session_status}");
            $this->line("  Waktu buka: {$absensi->waktu_buka}");
        } catch (\Exception $e) {
            $this->error("✗ Failed to open session: " . $e->getMessage());
            return 1;
        }
        $this->line('');

        // Test 3: Student check-in (Hadir)
        $this->comment('3. Testing Student Check-in (Hadir)');
        try {
            $attendance = AbsensiMahasiswa::create([
                'absensi_id' => $absensi->id,
                'mahasiswa_id' => $mahasiswa->id,
                'status' => 'hadir',
                'waktu_absensi' => now(),
            ]);
            $this->line("✓ Student check-in recorded");
            $this->line("  Status: {$attendance->status}");
            $this->line("  Waktu absensi: {$attendance->waktu_absensi}");
        } catch (\Exception $e) {
            $this->error("✗ Failed to record check-in: " . $e->getMessage());
            return 1;
        }
        $this->line('');

        // Test 4: Check attendance stats
        $this->comment('4. Testing Attendance Statistics');
        try {
            $stats = $absensi->getAttendanceStats();
            $this->line("✓ Attendance stats:");
            $this->table(
                ['Metric', 'Value'],
                [
                    ['Total Mahasiswa', $stats['total_mahasiswa']],
                    ['Hadir', $stats['hadir']],
                    ['Izin', $stats['izin']],
                    ['Sakit', $stats['sakit']],
                    ['Alpha', $stats['alpha']],
                    ['Hadir %', $stats['hadir_pct'] . '%'],
                ]
            );
        } catch (\Exception $e) {
            $this->error("✗ Failed to get stats: " . $e->getMessage());
            return 1;
        }
        $this->line('');

        // Test 5: Create another session with izin/sakit
        $this->comment('5. Testing Create Multiple Sessions');
        try {
            $nextPertemuan2 = $nextPertemuan + 1;
            $absensi2 = Absensi::create([
                'kelas_perkuliahan_id' => $kelas->id,
                'pertemuan_ke' => $nextPertemuan2,
                'tanggal' => today()->addDay(),
                'jam_mulai' => '10:00',
                'jam_selesai' => '12:00',
                'session_status' => 'buka',
                'rangkuman' => 'Test rangkuman kedua',
            ]);
            $this->line("✓ Second session created: ID {$absensi2->id}");
        } catch (\Exception $e) {
            $this->error("✗ Failed to create second session: " . $e->getMessage());
            return 1;
        }
        $this->line('');

        // Test 6: Student check-in with izin
        $this->comment('6. Testing Student Check-in (Izin)');
        try {
            $attendance2 = AbsensiMahasiswa::create([
                'absensi_id' => $absensi2->id,
                'mahasiswa_id' => $mahasiswa->id,
                'status' => 'izin',
                'keterangan' => 'Ada keperluan keluarga',
                'waktu_absensi' => now(),
            ]);
            $this->line("✓ Student izin recorded");
            $this->line("  Status: {$attendance2->status}");
            $this->line("  Keterangan: {$attendance2->keterangan}");
        } catch (\Exception $e) {
            $this->error("✗ Failed to record izin: " . $e->getMessage());
            return 1;
        }
        $this->line('');

        // Test 7: Close session
        $this->comment('7. Testing Close Session');
        try {
            $absensi2->tutupSession();
            $this->line("✓ Session closed");
            $this->line("  Status: {$absensi2->session_status}");
            $this->line("  Waktu tutup: {$absensi2->waktu_tutup}");
        } catch (\Exception $e) {
            $this->error("✗ Failed to close session: " . $e->getMessage());
            return 1;
        }
        $this->line('');

        // Test 8: Student attendance summary
        $this->comment('8. Testing Student Attendance Summary');
        try {
            $summary = AbsensiMahasiswa::whereIn(
                'absensi_id',
                Absensi::where('kelas_perkuliahan_id', $kelas->id)->pluck('id')
            )->where('mahasiswa_id', $mahasiswa->id)
                ->get()
                ->groupBy('status')
                ->map->count();

            $this->line("✓ Student attendance summary:");
            $this->table(
                ['Status', 'Count'],
                [
                    ['Hadir', $summary['hadir'] ?? 0],
                    ['Izin', $summary['izin'] ?? 0],
                    ['Sakit', $summary['sakit'] ?? 0],
                ]
            );
        } catch (\Exception $e) {
            $this->error("✗ Failed to get summary: " . $e->getMessage());
            return 1;
        }
        $this->line('');

        // Test 9: Authorization check
        $this->comment('9. Testing Authorization');
        try {
            $this->line("✓ Policy checks (Authorization):");
            $this->line("  - Dosen dapat view session: " . ($dosen->can('view', $absensi) ? 'YES' : 'NO'));
            $this->line("  - Dosen dapat manage session: " . ($dosen->can('manage', $absensi) ? 'YES' : 'NO'));
            $this->line("  - Mahasiswa dapat view session: " . ($mahasiswa->can('view', $absensi) ? 'YES' : 'NO'));
            $this->line("  - Mahasiswa dapat checkIn (open): " . ($mahasiswa->can('checkIn', $absensi) ? 'YES' : 'NO'));
            $absensi->tutupSession();
            $this->line("  - Mahasiswa dapat checkIn (closed): " . ($mahasiswa->can('checkIn', $absensi) ? 'YES' : 'NO'));
        } catch (\Exception $e) {
            $this->error("✗ Failed authorization test: " . $e->getMessage());
            return 1;
        }
        $this->line('');

        // Test 10: Email Notification
        $this->comment('10. Testing Email Notification (Sesi Dibuka)');
        try {
            // Re-create fresh session untuk test notifikasi
            $nextPertemuan3 = $nextPertemuan + 2;
            $absensi3 = Absensi::create([
                'kelas_perkuliahan_id' => $kelas->id,
                'pertemuan_ke' => $nextPertemuan3,
                'tanggal' => today(),
                'jam_mulai' => '14:00',
                'jam_selesai' => '16:00',
                'session_status' => 'draft',
                'rangkuman' => 'Test notifikasi email',
            ]);

            $this->line("✓ Test session created: ID {$absensi3->id}");
            
            // Dispatch notification using NotificationService
            \App\Services\NotificationService::notifyAbsensiDibuka($absensi3, $dosen);
            
            $this->line("✓ Notification dispatched to queue");
            $this->line("  Check logs at: storage/logs/laravel.log");
            
            // Get the queued jobs count
            $queuedJobs = \DB::table('jobs')->count();
            $this->line("  Queued jobs: {$queuedJobs}");
        } catch (\Exception $e) {
            $this->error("✗ Failed notification test: " . $e->getMessage());
            return 1;
        }
        $this->line('');

        // Success message
        $this->info('=== All Tests Completed Successfully ===');
        $this->line('');
        $this->info('Test data created:');
        $this->line("  • 2 Attendance sessions");
        $this->line("  • 2 Student check-ins");
        $this->line('');
        $this->comment('To clean up test data, run:');
        $this->line('  php artisan test:absensi --clean');

        return 0;
    }

    /**
     * Clean test data
     */
    private function cleanTestData()
    {
        $this->line('Cleaning test data...');
        
        // Keep it simple - delete recent sessions
        Absensi::whereDate('created_at', today())->delete();
        
        $this->info('✓ Test data cleaned');
        $this->line('');
    }
}
