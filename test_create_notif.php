<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$user = App\Models\User::where('name', 'like', '%Dewi%')->first();
$n = App\Models\Notifikasi::create([
    'user_id' => $user->id,
    'kelas_perkuliahan_id' => 1,
    'judul' => 'Test Notif',
    'pesan' => 'Halo ini test',
    'tipe' => 'pengumuman',
    'url' => 'http://localhost'
]);
echo "Created notif ID: {$n->id}\n";
