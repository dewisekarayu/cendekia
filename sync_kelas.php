<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$user = App\Models\User::where('name', 'like', '%Dewi%')->first();
$user->kelasDiikuti()->sync([1]); // Assuming IF-201-A is ID 1
echo "Done syncing. Classes now: " . json_encode($user->kelasDiikuti()->pluck('kode_kelas'));
