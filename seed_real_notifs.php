<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$user = App\Models\User::where('name', 'Dewi Sayu Maharani')->first();
$kelas = $user->kelasDiikuti()->first();

// Hapus notif lama biar bersih
App\Models\Notifikasi::where('user_id', $user->id)->delete();

$inserts = [];

// Ambil pengumuman (Global + Kelas)
$pengumumans = App\Models\Pengumuman::whereNull('kelas_perkuliahan_id')
    ->orWhere('kelas_perkuliahan_id', $kelas->id)
    ->orderBy('created_at')
    ->get();

foreach ($pengumumans as $p) {
    $inserts[] = [
        'user_id' => $user->id,
        'kelas_perkuliahan_id' => $p->kelas_perkuliahan_id,
        'judul' => 'Pengumuman: ' . \Illuminate\Support\Str::limit($p->judul, 40),
        'pesan' => 'Mata Kuliah: ' . ($p->kelas_perkuliahan_id ? ($p->kelasPerkuliahan->mataKuliah->nama_mk ?? 'Global') : 'Global') . '. ' . \Illuminate\Support\Str::limit(strip_tags($p->isi ?? ''), 80),
        'tipe' => 'informasi',
        'url' => route('mahasiswa.pengumuman.index', [], false), // Absolute = false
        'created_at' => $p->created_at->format('Y-m-d H:i:s'),
        'updated_at' => $p->updated_at->format('Y-m-d H:i:s'),
        'dibaca_pada' => rand(0,1) ? now()->format('Y-m-d H:i:s') : null
    ];
}

// Ambil tugas (Kelas)
$tugasList = App\Models\Tugas::where('kelas_perkuliahan_id', $kelas->id)->orderBy('created_at')->get();
foreach ($tugasList as $t) {
    $inserts[] = [
        'user_id' => $user->id,
        'kelas_perkuliahan_id' => $t->kelas_perkuliahan_id,
        'judul' => 'Tugas Baru: ' . \Illuminate\Support\Str::limit($t->judul, 40),
        'pesan' => 'Mata Kuliah: ' . ($t->kelasPerkuliahan->mataKuliah->nama_mk ?? 'Kelas') . '. ' . \Illuminate\Support\Str::limit(strip_tags($t->deskripsi ?? ''), 80),
        'tipe' => 'tugas',
        'url' => route('mahasiswa.pengumpulan-tugas.show', $t->id, false), // Absolute = false
        'created_at' => $t->created_at->format('Y-m-d H:i:s'),
        'updated_at' => $t->updated_at->format('Y-m-d H:i:s'),
        'dibaca_pada' => rand(0,1) ? now()->format('Y-m-d H:i:s') : null
    ];
}

App\Models\Notifikasi::insert($inserts);

echo "Seeded real notifications for " . $user->name . " with RELATIVE URLs.\n";
