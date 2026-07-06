<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Mahasiswa\DashboardController as MahasiswaDashboardController;
use App\Http\Controllers\Mahasiswa\KelasController as MahasiswaKelasController;
use App\Http\Controllers\Mahasiswa\GradebookController as MahasiswaGradebookController;
use App\Http\Controllers\Mahasiswa\ForumController as MahasiswaForumController;
use App\Http\Controllers\Mahasiswa\ScheduleController as MahasiswaScheduleController;
use App\Http\Controllers\Dosen\DashboardController as DosenDashboardController;
use App\Http\Controllers\Dosen\KelasController as DosenKelasController;
use App\Models\MataKuliah;
use App\Models\ProgramStudi;
use App\Models\User;
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
        $totalMahasiswa = User::role('mahasiswa')->count();
        $totalDosen = User::role('dosen')->count();
        $totalMataKuliah = MataKuliah::count();
        $totalProgramStudi = ProgramStudi::count();

        return view('admin.dashboard', compact('totalMahasiswa', 'totalDosen', 'totalMataKuliah', 'totalProgramStudi'));
    })->name('admin.dashboard');

    // Master Data - Dosen
    Route::get('/admin/dosen', function () {
        $dosen = User::role('dosen')->latest()->paginate(20);

        return view('admin.dosen.index', compact('dosen'));
    })->name('admin.dosen.index');

    Route::get('/admin/dosen/tambah', function () {
        return view('admin.dosen.create');
    })->name('admin.dosen.create');

    // Master Data - Mahasiswa
    Route::get('/admin/mahasiswa', function () {
        $mahasiswa = User::role('mahasiswa')->latest()->paginate(20);

        return view('admin.mahasiswa.index', compact('mahasiswa'));
    })->name('admin.mahasiswa.index');

    Route::get('/admin/mahasiswa/tambah', function () {
        return view('admin.mahasiswa.create');
    })->name('admin.mahasiswa.create');

    Route::get('/admin/mata-kuliah', function () {
        $mataKuliah = App\Models\MataKuliah::latest()->paginate(20);

        return view('admin.mata-kuliah.index', compact('mataKuliah'));
    })->name('admin.mata-kuliah.index');

    Route::get('/admin/mata-kuliah/{id}/edit', function ($id) {
        $mk = App\Models\MataKuliah::findOrFail($id);
        $dosenList = App\Models\User::role('dosen')->get();
        $prodiList = App\Models\ProgramStudi::all();

        return view('admin.mata-kuliah.edit', compact('mk', 'dosenList', 'prodiList'));
    })->name('admin.mata-kuliah.edit');

    Route::put('/admin/mata-kuliah/{id}', function (Illuminate\Http\Request $request, $id) {
        $mk = App\Models\MataKuliah::findOrFail($id);
        $mk->update($request->only(['kode_mk', 'nama_mk', 'sks', 'semester_ke', 'program_studi_id', 'dosen_id', 'deskripsi']));

        return redirect()->route('admin.mata-kuliah.index')->with('success', 'Mata kuliah diperbarui.');
    })->name('admin.mata-kuliah.update');

    Route::get('/admin/program-studi', function () {
        return view('admin.program-studi.index');
    })->name('admin.program-studi.index');

    // Content
    Route::get('/admin/pengumuman', function () {
        return view('admin.pengumuman.index');
    })->name('admin.pengumuman.index');

    Route::get('/admin/kelas', function () {
        return view('admin.kelas.index');
    })->name('admin.kelas.index');
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