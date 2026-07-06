<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Mahasiswa\DashboardController as MahasiswaDashboardController;
use App\Http\Controllers\Mahasiswa\KelasController as MahasiswaKelasController;
use App\Http\Controllers\Mahasiswa\GradebookController as MahasiswaGradebookController;
use App\Http\Controllers\Mahasiswa\ForumController as MahasiswaForumController;
use App\Http\Controllers\Mahasiswa\ScheduleController as MahasiswaScheduleController;
use App\Http\Controllers\Dosen\DashboardController as DosenDashboardController;
use App\Http\Controllers\Dosen\KelasController as DosenKelasController;
use App\Http\Controllers\Admin\ProgramStudiController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Dashboard umum -> redirect otomatis sesuai role
Route::get('/dashboard', function () {
    $user = auth()->user();

    if ($user->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    } elseif ($user->hasRole('dosen')) {
        return redirect()->route('dosen.dashboard');
    } elseif ($user->hasRole('mahasiswa')) {
        return redirect()->route('mahasiswa.dashboard');
    }

    abort(403, 'Role tidak dikenali');
})->middleware(['auth', 'verified'])->name('dashboard');

// Dashboard & halaman khusus Admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::resource('admin/program-studi', ProgramStudiController::class)
        ->names('admin.program-studi')
        ->parameters(['program-studi' => 'programStudi']);
});

// Dashboard & halaman khusus Dosen
Route::middleware(['auth', 'role:dosen'])->group(function () {
    Route::get('/dosen/dashboard', [DosenDashboardController::class, 'index'])->name('dosen.dashboard');
    Route::get('/dosen/kelas-saya', [DosenKelasController::class, 'kelasSaya'])->name('dosen.kelas-saya');
    Route::get('/dosen/kelas/{id}', [DosenKelasController::class, 'show'])->name('dosen.kelas-detail');
    Route::get('/dosen/kelas/{id}/tugas', [DosenKelasController::class, 'tugas'])->name('dosen.kelas-tugas');
});

// Dashboard & halaman khusus Mahasiswa
Route::middleware(['auth', 'role:mahasiswa'])->group(function () {
    Route::get('/mahasiswa/dashboard', [MahasiswaDashboardController::class, 'index'])->name('mahasiswa.dashboard');
    Route::post('/mahasiswa/pilih-prodi/{id}', [MahasiswaDashboardController::class, 'pilihProdi'])->name('mahasiswa.pilih-prodi');
    Route::post('/mahasiswa/keluar-prodi', [MahasiswaDashboardController::class, 'keluarProdi'])->name('mahasiswa.keluar-prodi');

    Route::get('/mahasiswa/kelas-saya', [MahasiswaKelasController::class, 'kelasSaya'])->name('mahasiswa.kelas-saya');
    Route::get('/mahasiswa/kelas/{id}', [MahasiswaKelasController::class, 'show'])->name('mahasiswa.kelas-detail');
    Route::get('/mahasiswa/jelajahi-kelas', [MahasiswaKelasController::class, 'jelajahi'])->name('mahasiswa.jelajahi-kelas');
    Route::post('/mahasiswa/kelas/{id}/join', [MahasiswaKelasController::class, 'join'])->name('mahasiswa.kelas.join');

    Route::get('/mahasiswa/gradebook', [MahasiswaGradebookController::class, 'index'])->name('mahasiswa.gradebook');
    Route::get('/mahasiswa/forums', [MahasiswaForumController::class, 'index'])->name('mahasiswa.forums');
    Route::get('/mahasiswa/schedule', [MahasiswaScheduleController::class, 'index'])->name('mahasiswa.schedule');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';