<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\KelasPerkuliahan, App\Models\Tugas, App\Services\NotificationService;

$kelas = KelasPerkuliahan::first();
$mahasiswa = $kelas->mahasiswa()->first();
$dosen = $kelas->dosen;

echo "\n=== Testing Tugas Notification System ===\n\n";
echo "Kelas: " . $kelas->mataKuliah->nama_mk . "\n";
echo "Dosen: " . $dosen->name . " (" . $dosen->email . ")\n";
echo "Mahasiswa: " . $mahasiswa->name . " (" . $mahasiswa->email . ")\n\n";

// Create tugas
$tugas = Tugas::create([
    'kelas_perkuliahan_id' => $kelas->id,
    'judul' => 'Test Tugas - ' . now(),
    'instruksi' => 'Test task for email notification system',
    'deadline' => now()->addDays(7),
    'bobot_nilai' => 10,
]);

echo "✓ Tugas created: ID " . $tugas->id . "\n";

// Trigger notification
NotificationService::notifyTugasBaru($tugas, $dosen);
echo "✓ Notification dispatched to queue!\n\n";

// Check queue
$jobs = \Illuminate\Support\Facades\DB::table('jobs')->count();
echo "Queue status: " . $jobs . " job(s) waiting\n";
echo "\nNext step: Run 'php artisan queue:work --stop-when-empty'\n";
echo "\n=== Test Complete ===\n\n";
