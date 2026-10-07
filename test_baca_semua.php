<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$user = App\Models\User::whereHas('roles', function($q){$q->where('name', 'mahasiswa');})->first();
auth()->login($user);

echo "Before bacaSemua: " . auth()->user()->notifikasi()->whereNull('dibaca_pada')->count() . "\n";
app(\App\Http\Controllers\NotifikasiController::class)->bacaSemua();
echo "After bacaSemua: " . auth()->user()->notifikasi()->whereNull('dibaca_pada')->count() . "\n";
