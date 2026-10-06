<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$user = App\Models\User::where('name', 'like', '%Dewi%')->first();
$count = $user->notifikasi()->whereNull('dibaca_pada')->count();
echo "Before: $count unread.\n";

$n = $user->notifikasi()->whereNull('dibaca_pada')->first();
if ($n) {
    echo "Marking notif ID {$n->id} as read...\n";
    $n->update(['dibaca_pada' => now()]);
    $n->refresh();
    echo "dibaca_pada: " . ($n->dibaca_pada ? $n->dibaca_pada->toDateTimeString() : 'NULL') . "\n";
}

$count = $user->notifikasi()->whereNull('dibaca_pada')->count();
echo "After: $count unread.\n";
