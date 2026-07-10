<?php

use App\Http\Controllers\Admin\ProgramStudiController;
use App\Http\Controllers\Admin\MataKuliahController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\DosenController as AdminDosenController;
use App\Http\Controllers\Admin\MahasiswaController as AdminMahasiswaController;
use App\Http\Controllers\Admin\PengumumanController as AdminPengumumanController;
use App\Http\Controllers\Dosen\PengumumanController as DosenPengumumanController;
use App\Http\Controllers\Admin\UserController;

use App\Http\Controllers\Admin\KelasController;

use App\Http\Controllers\Dosen\ProfileController as DosenProfileController;
use App\Http\Controllers\Dosen\DashboardController as DosenDashboardController;
use App\Http\Controllers\Dosen\KelasController as DosenKelasController;
use App\Http\Controllers\Dosen\GradebookController as DosenGradebookController;
use App\Http\Controllers\Dosen\ForumController as DosenForumController;
use App\Http\Controllers\Dosen\MateriController as DosenMateriController;


use App\Http\Controllers\Mahasiswa\DashboardController as MahasiswaDashboardController;
use App\Http\Controllers\Mahasiswa\KelasController as MahasiswaKelasController;
use App\Http\Controllers\Mahasiswa\GradebookController as MahasiswaGradebookController;
use App\Http\Controllers\Mahasiswa\ForumController as MahasiswaForumController;
use App\Http\Controllers\Mahasiswa\ScheduleController as MahasiswaScheduleController;
use App\Http\Controllers\Mahasiswa\JadwalController as MahasiswaJadwalController;
use App\Http\Controllers\Dosen\JadwalController as DosenJadwalController;
use App\Http\Controllers\Mahasiswa\SettingController as MahasiswaSettingController;
use App\Http\Controllers\Mahasiswa\PengumpulantugasController as MahasiswaPengumpulantugasController;

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use Illuminate\Http\Request;


Route::get('/', function () {
    return view('welcome');
});

// Dashboard umum -> redirect otomatis sesuai role
Route::get('/dashboard', function () {
    $user = auth()->user();
    if (!$user) {
        abort(401);
    }

    // Use the centralized user helper methods
    if ($user->isAdmin()) {
        return redirect()->route('admin.dashboard');
    } elseif ($user->isDosen()) {
        return redirect()->route('dosen.dashboard');
    } elseif ($user->isMahasiswa()) {
        return redirect()->route('mahasiswa.dashboard');
    }

    abort(403, 'Role tidak dikenali');
})->middleware(['auth', 'verified'])->name('dashboard');

// Dashboard & halaman khusus Admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    Route::resource('admin/program-studi', ProgramStudiController::class)
        ->except(['show'])
        ->names('admin.program-studi')
        ->parameters(['program-studi' => 'programStudi']);

    Route::resource('admin/mata-kuliah', MataKuliahController::class)
        ->except(['show'])
        ->names('admin.mata-kuliah')
        ->parameters(['mata-kuliah' => 'mataKuliah']);

    Route::resource('admin/dosen', AdminDosenController::class)
        ->only(['index', 'create', 'store', 'edit', 'update', 'destroy'])
        ->names('admin.dosen')
        ->parameters(['dosen' => 'dosen']);

    Route::resource('admin/mahasiswa', AdminMahasiswaController::class)
        ->only(['index', 'create', 'store', 'edit', 'update', 'destroy'])
        ->names('admin.mahasiswa')
        ->parameters(['mahasiswa' => 'mahasiswa']);

        Route::resource('admin/pengumuman', AdminPengumumanController::class)
        ->only(['index', 'store', 'update', 'destroy'])
        ->names('admin.pengumuman');
    Route::resource('admin/kelas', KelasController::class)
        ->names('admin.kelas')
        ->parameters(['kelas' => 'kelas']);

    Route::resource('admin/user', UserController::class)
        ->names('admin.user')
        ->parameters(['user' => 'user']);
});

// Dashboard & halaman khusus Dosen
Route::middleware(['auth', 'role:dosen'])->group(function () {
    Route::get('/dosen/dashboard', [DosenDashboardController::class, 'index'])->name('dosen.dashboard');
    Route::get('/dosen/kelas-saya', [DosenKelasController::class, 'kelasSaya'])->name('dosen.kelas-saya');
    Route::get('/dosen/kelas/{id}', [DosenKelasController::class, 'show'])->name('dosen.kelas-detail');
    Route::get('/dosen/kelas/{id}/tugas', [DosenKelasController::class, 'tugas'])->name('dosen.kelas-tugas');
    Route::post('/dosen/kelas/{id}/tugas', [DosenKelasController::class, 'storeTugas'])->name('dosen.kelas-tugas.store');
    Route::get('/dosen/kelas/{id}/materi', [DosenKelasController::class, 'materi'])->name('dosen.kelas-materi');
    Route::post('/dosen/kelas/{id}/materi', [DosenKelasController::class, 'storeMateri'])->name('dosen.kelas-materi.store');
    Route::get('/dosen/gradebook', [DosenGradebookController::class, 'index'])->name('dosen.gradebook');
    Route::get('/dosen/kelas/{kelas}/materi/{materi}/buka', [DosenKelasController::class, 'bukaMateri'])->name('dosen.materi.buka');
    
    // Forum - akses halaman forum umum dosen
    Route::get('/dosen/forums', [DosenForumController::class, 'index'])->name('dosen.forums');
    
    // Forum - akses dari dalam kelas & kirim pesan
    Route::get('/dosen/kelas/{kelas}/materi/{materi}/buka', [DosenKelasController::class, 'bukaMateri'])->name('dosen.materi.buka');
    Route::get('/dosen/kelas/{kelas}/materi/{materi}/unduh', [DosenKelasController::class, 'unduhMateri'])->name('dosen.materi.unduh');
    Route::get('/dosen/kelas/{id}/forum', [DosenForumController::class, 'index'])->name('dosen.kelas-forum');
    Route::post('/dosen/kelas/{id}/forum/{forum}/pesan', [DosenForumController::class, 'kirimPesan'])->name('dosen.kelas-forum.pesan');
    Route::get('/dosen/kelas/{kelas}/tugas/{tugas}/submissions', [DosenKelasController::class, 'submissions'])->name('dosen.tugas.submissions');
Route::post('/dosen/kelas/{kelas}/tugas/{tugas}/submissions/{pengumpulan}/nilai', [DosenKelasController::class, 'simpanNilai'])->name('dosen.tugas.nilai');
    // Pengumuman
    Route::get('/dosen/pengumuman', [DosenPengumumanController::class, 'index'])->name('dosen.kelas-pengumuman.index');
    Route::post('/dosen/pengumuman', [DosenPengumumanController::class, 'store'])->name('dosen.kelas-pengumuman.store');
    Route::put('/dosen/pengumuman/{pengumuman}', [DosenPengumumanController::class, 'update'])->name('dosen.kelas-pengumuman.update');
    Route::delete('/dosen/pengumuman/{pengumuman}', [DosenPengumumanController::class, 'destroy'])->name('dosen.kelas-pengumuman.destroy');
    
    // Profil, Jadwal, dan Pengaturan Dosen
    // GANTI dengan:
    // Profil Dosen
    Route::get('/dosen/profil', [DosenProfileController::class, 'index'])->name('dosen.profil.index');
    Route::put('/dosen/profil', [DosenProfileController::class, 'updateProfile'])->name('dosen.profil.update');
    Route::put('/dosen/profil/foto', [DosenProfileController::class, 'updateFoto'])->name('dosen.profil.foto');
    Route::put('/dosen/profil/password', [DosenProfileController::class, 'updatePassword'])->name('dosen.profil.password');
    Route::get('/dosen/profil', [App\Http\Controllers\Dosen\ProfileController::class, 'index'])->name('dosen.profil.index');
    Route::put('/dosen/profil', [App\Http\Controllers\Dosen\ProfileController::class, 'updateProfile'])->name('dosen.profil.update');
    Route::put('/dosen/profil/password', [App\Http\Controllers\Dosen\ProfileController::class, 'updatePassword'])->name('dosen.profil.password');

    Route::get('/dosen/schedule', function () { return view('dosen.schedule'); })->name('dosen.schedule');
    
    // Jadwal Dosen
    Route::prefix('dosen/jadwal')->group(function () {
        Route::get('/', [DosenJadwalController::class, 'index'])->name('dosen.jadwal.index');
        Route::get('/{id}', [DosenJadwalController::class, 'show'])->name('dosen.jadwal.show');
        Route::get('/calendar', [DosenJadwalController::class, 'calendar'])->name('dosen.jadwal.calendar');
        Route::get('/export', [DosenJadwalController::class, 'exportPdf'])->name('dosen.jadwal.export');
    });
    Route::get('/dosen/setting', function () { return view('dosen.setting'); })->name('dosen.setting');

});

// Dashboard & halaman khusus Mahasiswa
Route::middleware(['auth', 'role:mahasiswa'])->group(function () {
    Route::get('/mahasiswa/dashboard', [MahasiswaDashboardController::class, 'index'])->name('mahasiswa.dashboard');

    Route::get('/mahasiswa/kelas-saya', [MahasiswaKelasController::class, 'kelasSaya'])->name('mahasiswa.kelas-saya');
    Route::get('/mahasiswa/kelas/{id}', [MahasiswaKelasController::class, 'show'])->name('mahasiswa.kelas-detail');

    Route::get('/mahasiswa/kelas/{kelas}/materi/{materi}/buka', [MahasiswaKelasController::class, 'bukaMateri'])->name('mahasiswa.materi.buka');
    Route::get('/mahasiswa/kelas/{kelas}/materi/{materi}/unduh', [MahasiswaKelasController::class, 'unduhMateri'])->name('mahasiswa.materi.unduh');
    Route::get('/mahasiswa/jelajahi-kelas', [MahasiswaKelasController::class, 'jelajahi'])->name('mahasiswa.jelajahi-kelas');
    Route::post('/mahasiswa/kelas/{id}/join', [MahasiswaKelasController::class, 'join'])->name('mahasiswa.kelas.join');

    // Pengumpulan Tugas
    Route::get('/mahasiswa/tugas/{tugas}', [MahasiswaPengumpulantugasController::class, 'show'])->name('mahasiswa.pengumpulan-tugas.show');
    Route::post('/mahasiswa/tugas/{tugas}/kumpulkan', [MahasiswaPengumpulantugasController::class, 'store'])->name('mahasiswa.pengumpulan-tugas.store');
    Route::delete('/mahasiswa/tugas/{tugas}/batalkan', [MahasiswaPengumpulantugasController::class, 'destroy'])->name('mahasiswa.pengumpulan-tugas.destroy');

    Route::get('/mahasiswa/gradebook', [MahasiswaGradebookController::class, 'index'])->name('mahasiswa.gradebook');

    // Forum - akses hanya dari dalam kelas
    Route::get('/mahasiswa/kelas/{id}/forum', [MahasiswaForumController::class, 'index'])->name('mahasiswa.kelas-forum');
    Route::post('/mahasiswa/kelas/{id}/forum/{forum}/pesan', [MahasiswaForumController::class, 'kirimPesan'])->name('mahasiswa.kelas-forum.pesan');

    Route::get('/mahasiswa/schedule', [MahasiswaScheduleController::class, 'index'])->name('mahasiswa.schedule');
    
    // Jadwal Mahasiswa
    Route::prefix('mahasiswa/jadwal')->group(function () {
        Route::get('/', [MahasiswaJadwalController::class, 'index'])->name('mahasiswa.jadwal.index');
        Route::get('/semester/{semesterId?}', [MahasiswaJadwalController::class, 'showBySemester'])->name('mahasiswa.jadwal.semester');
        Route::get('/calendar', [MahasiswaJadwalController::class, 'calendar'])->name('mahasiswa.jadwal.calendar');
    });
    Route::get('/mahasiswa/setting', [MahasiswaSettingController::class, 'index'])->name('mahasiswa.setting');
    Route::patch('/mahasiswa/setting/profile', [MahasiswaSettingController::class, 'updateProfile'])->name('mahasiswa.setting.profile');
    Route::patch('/mahasiswa/setting/password', [MahasiswaSettingController::class, 'updatePassword'])->name('mahasiswa.setting.password');

    // Profil Mahasiswa
    Route::get('/mahasiswa/profil', function () { return view('mahasiswa.profil'); })->name('mahasiswa.profil');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';