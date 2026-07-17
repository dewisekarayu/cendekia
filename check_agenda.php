<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$response = $kernel->handle($request = \Illuminate\Http\Request::capture());

// Now we can use Laravel
use App\Models\KalenderAkademik;

echo "=== CHECKING AGENDAS ===\n\n";

$total = KalenderAkademik::count();
echo "Total agendas: $total\n\n";

if ($total > 0) {
    echo "Latest 5 agendas:\n";
    $agendas = KalenderAkademik::latest()->take(5)->get();
    foreach ($agendas as $a) {
        $published = $a->is_published ? "YES ✓" : "NO";
        echo "- ID: {$a->id}\n";
        echo "  Judul: {$a->judul}\n";
        echo "  Tanggal: {$a->tanggal_mulai} to {$a->tanggal_selesai}\n";
        echo "  Published: $published\n";
        echo "  Dibuat: {$a->created_at}\n\n";
    }
} else {
    echo "Tidak ada data agenda di database!\n";
}

echo "\n=== CHECKING SEMESTERS ===\n";
$semesters = \App\Models\Semester::get();
echo "Total semesters: " . count($semesters) . "\n";
if (count($semesters) > 0) {
    foreach ($semesters as $s) {
        $active = $s->is_active ? "ACTIVE" : "inactive";
        echo "- {$s->tahun_ajaran} ({$s->nama_semester}) - $active\n";
    }
}
?>
