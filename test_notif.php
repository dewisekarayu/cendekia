<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$user = App\Models\User::where('name', 'like', '%Dewi%')->first();
$notifs = $user->notifikasi()->pluck('judul')->take(10);
echo "Notifikasi: " . json_encode($notifs) . "\n";
