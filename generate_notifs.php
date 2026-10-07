<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$user = App\Models\User::where('name', 'like', '%Dewi%')->first();
$kelas = $user->kelasDiikuti()->first();

$titles = [
    'Tugas Baru: Implementasi Array',
    'Pengumuman: Perubahan Jadwal Kuliah',
    'Pengumuman: Materi Baru Pengenalan Algoritma',
    'Tugas Baru: Project Akhir Semester',
    'Pengumuman: Nilai Baru Quiz 1',
    'Pengumuman: Libur Nasional',
    'Pengumuman: Materi Baru Struktur Data Tree',
    'Tugas Baru: Latihan SQL',
];

foreach ($titles as $i => $title) {
    App\Models\Notifikasi::create([
        'user_id' => $user->id,
        'kelas_perkuliahan_id' => $kelas ? $kelas->id : null,
        'judul' => $title,
        'pesan' => 'Ini adalah detail untuk ' . $title . '. Harap diperhatikan dengan saksama.',
        'tipe' => str_contains($title, 'Tugas') ? 'tugas' : (str_contains($title, 'Nilai') ? 'nilai' : 'informasi'),
        'url' => '#',
        'dibaca_pada' => $i < 3 ? null : now()->subDays(rand(1, 5)), // First 3 are unread
        'created_at' => now()->subHours($i * 5),
        'updated_at' => now()->subHours($i * 5),
    ]);
}
echo "Generated dummy notifs for Dewi.\n";
