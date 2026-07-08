<?php
define('LARAVEL_START', microtime(true));
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';

$user = App\Models\User::whereHas('roles', function($q) {
    $q->where('name','dosen');
})->first();

if (!$user) {
    echo "ERROR: No dosen user found\n";
    exit(1);
}

echo "Dosen found: " . $user->name . "\n";

try {
    $request = Illuminate\Http\Request::create('/dosen/forums', 'GET');
    $request->setUserResolver(function() use ($user) { return $user; });
    
    $controller = new App\Http\Controllers\Dosen\ForumController();
    $response = $controller->index($request);
    echo "Controller OK - returned: " . get_class($response) . "\n";
} catch(Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}
