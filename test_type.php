<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$n = App\Models\Notifikasi::first();
var_dump($n->user_id);
var_dump(auth()->loginUsingId($n->user_id)->id);
