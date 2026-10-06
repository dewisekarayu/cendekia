<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$user = App\Models\User::where('name', 'like', '%Dewi%')->first();
echo "Notifikasi for Dewi: " . App\Models\Notifikasi::where('user_id', $user->id)->count() . "\n";
echo "Global Pengumuman: " . App\Models\Pengumuman::whereNull('kelas_perkuliahan_id')->count() . "\n";

$kelas = $user->kelasDiikuti()->first();
if ($kelas) {
    echo "Pengumuman for Kelas {$kelas->id}: " . App\Models\Pengumuman::where('kelas_perkuliahan_id', $kelas->id)->count() . "\n";
    echo "Tugas for Kelas {$kelas->id}: " . App\Models\Tugas::where('kelas_perkuliahan_id', $kelas->id)->count() . "\n";
} else {
    echo "Dewi no kelas!\n";
}
