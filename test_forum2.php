<?php
define('LARAVEL_START', microtime(true));
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Cari dosen pertama
$dosen = App\Models\User::whereHas('roles', function($q) {
    $q->where('name', 'dosen');
})->first();

if (!$dosen) {
    echo "ERROR: Tidak ada user dengan role dosen\n";
    exit(1);
}

echo "Dosen ditemukan: {$dosen->name} (id: {$dosen->id})\n";

// Login sebagai dosen
auth()->login($dosen);

// Coba panggil controller
try {
    $request = request();
    $controller = new App\Http\Controllers\Dosen\ForumController();
    $response = $controller->index($request);
    echo "Controller berhasil dipanggil\n";
    echo "Response class: " . get_class($response) . "\n";
} catch (Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
