    

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MobileApiController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/mahasiswa/dashboard', [
        MobileApiController::class,
        'dashboardMahasiswa'
    ]);

    Route::get('/dosen/dashboard', [
        MobileApiController::class,
        'dashboardDosen'
    ]);
});

Route::get('/users', function () {
    return response()->json(['message' => 'API Users aktif']);
});

Route::get('/students', function () {
    return response()->json(['message' => 'API Students aktif']);
});

Route::get('/teachers', function () {
    return response()->json(['message' => 'API Teachers aktif']);
});
