<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$users = App\Models\User::whereHas('roles', function($q){$q->where('name', 'mahasiswa');})->take(5)->get();
foreach ($users as $u) {
    $count = $u->notifikasi()->whereNull('dibaca_pada')->count();
    echo $u->name . " -> " . $count . " unread notifs\n";
}
