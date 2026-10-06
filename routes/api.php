<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MobileApiController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Mahasiswa
    Route::get('/mahasiswa/dashboard', [MobileApiController::class, 'dashboardMahasiswa']);
    
    // Dosen
    Route::get('/dosen/dashboard', [MobileApiController::class, 'dashboardDosen']);
});
