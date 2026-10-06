<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$users = App\Models\User::where('name', 'like', '%Dewi%')->get();
foreach ($users as $u) {
    echo "ID: {$u->id}, Name: {$u->name}, Notifs: " . $u->notifikasi()->count() . "\n";
}
