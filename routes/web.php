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
use App\Http\Controllers\HelpCenterController;

use App\Http\Controllers\Dosen\ProfileController as DosenProfileController;
use App\Http\Controllers\Dosen\DashboardController as DosenDashboardController;
use App\Http\Controllers\Dosen\KelasController as DosenKelasController;
use App\Http\Controllers\Dosen\GradebookController as DosenGradebookController;
use App\Http\Controllers\Dosen\ForumController as DosenForumController;
use App\Http\Controllers\Dosen\AbsensiController as DosenAbsensiController;
use App\Http\Controllers\Dosen\JadwalController as DosenJadwalController;
use App\Http\Controllers\Dosen\MateriController as DosenMateriController;

use App\Http\Controllers\Mahasiswa\DashboardController as MahasiswaDashboardController;
use App\Http\Controllers\Mahasiswa\KelasController as MahasiswaKelasController;
use App\Http\Controllers\Mahasiswa\GradebookController as MahasiswaGradebookController;
use App\Http\Controllers\Mahasiswa\ForumController as MahasiswaForumController;
use App\Http\Controllers\Mahasiswa\ScheduleController as MahasiswaScheduleController;
use App\Http\Controllers\Mahasiswa\JadwalController as MahasiswaJadwalController;
use App\Http\Controllers\Mahasiswa\SettingController as MahasiswaSettingController;
use App\Http\Controllers\Mahasiswa\AbsensiController as MahasiswaAbsensiController;
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

    if ($user->isAdmin()) {
        return redirect()->route('admin.dashboard');
    } elseif ($user->isDosen()) {
        return redirect()->route('dosen.dashboard');
    } elseif ($user->isMahasiswa()) {
        return redirect()->route('mahasiswa.dashboard');
    }

    abort(403, 'Role tidak dikenali');
})->middleware(['auth', 'verified'])->name('dashboard');

// ==========================================
// DASHBOARD & HALAMAN KHUSUS ADMIN
// ==========================================
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

    // Notification Preferences
    Route::prefix('admin/notification-preferences')->name('admin.notification-preferences.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\NotificationPreferenceController::class, 'index'])->name('index');
        Route::get('/{user}', [App\Http\Controllers\Admin\NotificationPreferenceController::class, 'show'])->name('show');
        Route::put('/{user}', [App\Http\Controllers\Admin\NotificationPreferenceController::class, 'update'])->name('update');
        Route::put('/{user}/reset', [App\Http\Controllers\Admin\NotificationPreferenceController::class, 'reset'])->name('reset');
        Route::put('/{user}/disable-all', [App\Http\Controllers\Admin\NotificationPreferenceController::class, 'disableAll'])->name('disableAll');
        Route::put('/{user}/enable-all', [App\Http\Controllers\Admin\NotificationPreferenceController::class, 'enableAll'])->name('enableAll');
    });

    // Admin Absensi CRUD
    Route::prefix('admin/absensi')->name('admin.absensi.')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\AbsensiController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\Admin\AbsensiController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\Admin\AbsensiController::class, 'store'])->name('store');
        Route::get('/{absensi}', [App\Http\Controllers\Admin\AbsensiController::class, 'show'])->name('show');
        Route::get('/{absensi}/edit', [App\Http\Controllers\Admin\AbsensiController::class, 'edit'])->name('edit');
        Route::put('/{absensi}', [App\Http\Controllers\Admin\AbsensiController::class, 'update'])->name('update');
        Route::get('/{absensi}/attendance', [App\Http\Controllers\Admin\AbsensiController::class, 'editAttendance'])->name('attendance');
        Route::put('/{absensi}/attendance', [App\Http\Controllers\Admin\AbsensiController::class, 'updateAttendance'])->name('updateAttendance');
        Route::delete('/{absensi}', [App\Http\Controllers\Admin\AbsensiController::class, 'destroy'])->name('destroy');
        Route::post('/bulk-delete', [App\Http\Controllers\Admin\AbsensiController::class, 'bulkDelete'])->name('bulkDelete');
    });

    // Admin Help Center (Overhauled with TicketController)
    Route::prefix('admin/help-center')->name('admin.help-center.')->group(function () {
        Route::get('/', [\App\Http\Controllers\TicketController::class, 'index'])->name('dashboard');
        Route::get('/tickets', [\App\Http\Controllers\TicketController::class, 'index'])->name('tickets');
        Route::get('/tickets/{id}', [\App\Http\Controllers\TicketController::class, 'show'])->name('ticket-detail');
        Route::post('/tickets/{id}/reply', [\App\Http\Controllers\TicketController::class, 'reply'])->name('reply');
        Route::post('/tickets/{id}/close', [\App\Http\Controllers\TicketController::class, 'close'])->name('close');
    });
});

// ==========================================
// DASHBOARD & HALAMAN KHUSUS DOSEN
// ==========================================
Route::middleware(['auth', 'role:dosen'])->group(function () {
    Route::get('/dosen/dashboard', [DosenDashboardController::class, 'index'])->name('dosen.dashboard');
    Route::get('/dosen/kelas-saya', [DosenKelasController::class, 'kelasSaya'])->name('dosen.kelas-saya');
    Route::get('/dosen/kelas/{id}', [DosenKelasController::class, 'show'])->name('dosen.kelas-detail');

    // Tugas Dosen
    Route::put('/dosen/kelas/{id}/tugas/{tugasId}', [DosenKelasController::class, 'updateTugas'])->name('dosen.kelas-tugas.update');
    Route::get('/dosen/kelas/{id}/tugas', [DosenKelasController::class, 'tugas'])->name('dosen.kelas-tugas');
    Route::post('/dosen/kelas/{id}/tugas', [DosenKelasController::class, 'storeTugas'])->name('dosen.kelas-tugas.store');
    Route::delete('/dosen/kelas/{id}/tugas/{tugasId}', [DosenKelasController::class, 'hapusTugas'])->name('dosen.kelas-tugas.destroy');
    
    // Gradebook Dosen
    Route::get('/dosen/gradebook', [DosenGradebookController::class, 'index'])->name('dosen.gradebook');
    
    // Materi Dosen
    Route::delete('/kelas/{id}/materi/{materiId}', [DosenKelasController::class, 'hapusMateri'])->name('dosen.kelas-materi.hapus');
    Route::put('/dosen/kelas/{id}/materi/{materiId}', [DosenKelasController::class, 'updateMateri'])->name('dosen.kelas-materi.update');

    Route::get('/dosen/kelas/{id}/materi', [DosenKelasController::class, 'materi'])->name('dosen.kelas-materi');
    Route::post('/dosen/kelas/{id}/materi', [DosenKelasController::class, 'storeMateri'])->name('dosen.kelas-materi.store');
    
    Route::get('/dosen/kelas/{kelas}/materi/{materi}/buka', [DosenKelasController::class, 'bukaMateri'])->name('dosen.materi.buka');
    Route::get('/dosen/kelas/{kelas}/materi/{materi}/preview/{file}', [DosenKelasController::class, 'previewMateri'])->name('dosen.materi.preview');
    Route::get('/dosen/kelas/{kelas}/materi/{materi}/unduh/{file}', [DosenKelasController::class, 'unduhMateri'])->name('dosen.materi.unduh');
    
    // Rekap Tugas Dosen
    Route::get('/dosen/kelas/{id}/rekap-tugas', [DosenKelasController::class, 'rekapTugas'])->name('dosen.kelas-tugas.rekap');
    
    // Forum Dosen
    Route::get('/dosen/kelas/{id}/forum', [DosenForumController::class, 'index'])->name('dosen.kelas-forum');
    Route::post('/dosen/kelas/{id}/forum/{forum}/pesan', [DosenForumController::class, 'kirimPesan'])->name('dosen.kelas-forum.pesan');
    
    //Penilaian Tugas oleh Dosen
    Route::get('/dosen/kelas/{kelas}/tugas/{tugas}/submissions', [DosenKelasController::class, 'submissions'])->name('dosen.tugas.submissions');
    Route::post('/dosen/kelas/{kelas}/tugas/{tugas}/submissions/{pengumpulan}/nilai', [DosenKelasController::class, 'simpanNilai'])->name('dosen.tugas.nilai');
    Route::post('/dosen/kelas/{kelas}/tugas/{tugas}/nilai/bulk', [DosenKelasController::class, 'simpanNilaiBulk'])->name('dosen.tugas.nilai.bulk');

    // Pengumuman Dosen
    Route::get('/dosen/pengumuman', [DosenPengumumanController::class, 'index'])->name('dosen.kelas-pengumuman.index');
    Route::post('/dosen/pengumuman', [DosenPengumumanController::class, 'store'])->name('dosen.kelas-pengumuman.store');
    Route::put('/dosen/pengumuman/{pengumuman}', [DosenPengumumanController::class, 'update'])->name('dosen.kelas-pengumuman.update');
    Route::delete('/dosen/pengumuman/{pengumuman}', [DosenPengumumanController::class, 'destroy'])->name('dosen.kelas-pengumuman.destroy');
    
    // Profil Dosen
    Route::get('/dosen/profil', [DosenProfileController::class, 'index'])->name('dosen.profil.index');
    Route::put('/dosen/profil', [DosenProfileController::class, 'updateProfile'])->name('dosen.profil.update');
    Route::put('/dosen/profil/foto', [DosenProfileController::class, 'updateFoto'])->name('dosen.profil.foto');
    Route::put('/dosen/profil/password', [DosenProfileController::class, 'updatePassword'])->name('dosen.profil.password');

    Route::get('/dosen/schedule', function () { return view('dosen.schedule'); })->name('dosen.schedule');
    Route::get('/dosen/setting', [App\Http\Controllers\Dosen\SettingController::class, 'index'])->name('dosen.setting');
    Route::post('/dosen/setting/umum', [App\Http\Controllers\Dosen\SettingController::class, 'updateUmum'])->name('dosen.setting.umum');
    Route::post('/dosen/setting/notifikasi', [App\Http\Controllers\Dosen\SettingController::class, 'updateNotifikasi'])->name('dosen.setting.notifikasi');
    
    // Management Absensi oleh Dosen (Struktur Bersih & Rapi)
    Route::prefix('dosen/kelas/{kelasId}/absensi')->name('dosen.absensi.')->group(function () {
        Route::get('/', [DosenAbsensiController::class, 'index'])->name('index');
        Route::get('/create', [DosenAbsensiController::class, 'create'])->name('create');
        Route::post('/', [DosenAbsensiController::class, 'store'])->name('store');
        Route::get('/{absensiId}', [DosenAbsensiController::class, 'show'])->name('show');
        Route::get('/{absensiId}/edit', [DosenAbsensiController::class, 'edit'])->name('edit');
        Route::put('/{absensiId}', [DosenAbsensiController::class, 'update'])->name('update');
        Route::post('/{absensiId}/buka', [DosenAbsensiController::class, 'bukaSession'])->name('buka');
        Route::post('/{absensiId}/tutup', [DosenAbsensiController::class, 'tutupSession'])->name('tutup');
        Route::get('/{absensiId}/attendance', [DosenAbsensiController::class, 'editAttendance'])->name('attendance');
        Route::post('/{absensiId}/attendance', [DosenAbsensiController::class, 'updateAttendance'])->name('updateAttendance');
        Route::delete('/{absensiId}', [DosenAbsensiController::class, 'destroy'])->name('destroy');
        Route::get('/{absensiId}/export', [DosenAbsensiController::class, 'export'])->name('export');
    });
    
    // Jadwal Dosen
    Route::prefix('dosen/jadwal')->name('dosen.jadwal.')->group(function () {
        Route::get('/', [DosenJadwalController::class, 'index'])->name('index');
        Route::get('/{id}', [DosenJadwalController::class, 'show'])->name('show');
        Route::get('/calendar', [DosenJadwalController::class, 'calendar'])->name('calendar');
        Route::get('/export', [DosenJadwalController::class, 'exportPdf'])->name('export');
    });
});

// ==========================================
// DASHBOARD & HALAMAN KHUSUS MAHASISWA
// ==========================================
Route::middleware(['auth', 'role:mahasiswa'])->group(function () {
    Route::get('/mahasiswa/dashboard', [MahasiswaDashboardController::class, 'index'])->name('mahasiswa.dashboard');
    Route::get('/mahasiswa/kelas-saya', [MahasiswaKelasController::class, 'kelasSaya'])->name('mahasiswa.kelas-saya');
    Route::get('/mahasiswa/kelas/{id}', [MahasiswaKelasController::class, 'show'])->name('mahasiswa.kelas-detail');

    Route::get('/mahasiswa/kelas/{kelas}/materi/{materi}/buka', [MahasiswaKelasController::class, 'bukaMateri'])->name('mahasiswa.materi.buka');
    Route::get('/mahasiswa/kelas/{kelas}/materi/{materi}/unduh/{file}', [MahasiswaKelasController::class, 'unduhMateri'])->name('mahasiswa.materi.unduh');
    Route::get('/mahasiswa/kelas/{kelas}/materi/{materi}/preview/{file}', [MahasiswaKelasController::class, 'previewMateri'])->name('mahasiswa.materi.preview');

    Route::get('/mahasiswa/jelajahi-kelas', [MahasiswaKelasController::class, 'jelajahi'])->name('mahasiswa.jelajahi-kelas');
    Route::post('/mahasiswa/kelas/{id}/join', [MahasiswaKelasController::class, 'join'])->name('mahasiswa.kelas.join');

    // Pengumpulan Tugas
    Route::get('/mahasiswa/tugas/{tugas}', [MahasiswaPengumpulantugasController::class, 'show'])->name('mahasiswa.pengumpulan-tugas.show');
    Route::post('/mahasiswa/tugas/{tugas}/kumpulkan', [MahasiswaPengumpulantugasController::class, 'store'])->name('mahasiswa.pengumpulan-tugas.store');
    Route::delete('/mahasiswa/tugas/{tugas}/batalkan', [MahasiswaPengumpulantugasController::class, 'destroy'])->name('mahasiswa.pengumpulan-tugas.destroy');

    Route::get('/mahasiswa/gradebook', [MahasiswaGradebookController::class, 'index'])->name('mahasiswa.gradebook');

    // Forum Mahasiswa
    Route::get('/mahasiswa/kelas/{id}/forum', [MahasiswaForumController::class, 'index'])->name('mahasiswa.kelas-forum');
    Route::post('/mahasiswa/kelas/{id}/forum/{forum}/pesan', [MahasiswaForumController::class, 'kirimPesan'])->name('mahasiswa.kelas-forum.pesan');

    Route::get('/mahasiswa/schedule', [MahasiswaScheduleController::class, 'index'])->name('mahasiswa.schedule');
    
    // Jadwal Mahasiswa
    Route::prefix('mahasiswa/jadwal')->name('mahasiswa.jadwal.')->group(function () {
        Route::get('/', [MahasiswaJadwalController::class, 'index'])->name('index');
        Route::get('/semester/{semesterId?}', [MahasiswaJadwalController::class, 'showBySemester'])->name('semester');
        Route::get('/calendar', [MahasiswaJadwalController::class, 'calendar'])->name('calendar');
    });
    
    // Absensi Mahasiswa (Terintegrasi penuh dengan tombol absen masuk)
    Route::prefix('mahasiswa/absensi')->name('mahasiswa.absensi.')->group(function () {
        Route::get('/', [MahasiswaAbsensiController::class, 'index'])->name('index');
        Route::get('/kelas/{kelasId}/masuk', [MahasiswaAbsensiController::class, 'kelasAbsensi'])->name('kelas');
        Route::post('/kelas/{kelasId}/masuk/{absensiId}', [MahasiswaAbsensiController::class, 'absenMasuk'])->name('masuk');
        Route::get('/{kelasId}', [MahasiswaAbsensiController::class, 'show'])->name('show');
    });

    // Pengaturan & Profil Mahasiswa
    Route::get('/mahasiswa/profil', [MahasiswaSettingController::class, 'profil'])->name('mahasiswa.profil');
    Route::get('/mahasiswa/setting', [MahasiswaSettingController::class, 'index'])->name('mahasiswa.setting');
    Route::patch('/mahasiswa/setting/profile', [MahasiswaSettingController::class, 'updateProfile'])->name('mahasiswa.setting.profile');
    Route::put('/mahasiswa/setting/foto', [MahasiswaSettingController::class, 'updateFoto'])->name('mahasiswa.setting.foto');
    Route::patch('/mahasiswa/setting/password', [MahasiswaSettingController::class, 'updatePassword'])->name('mahasiswa.setting.password');
    Route::post('/mahasiswa/setting/umum', [MahasiswaSettingController::class, 'updateUmum'])->name('mahasiswa.setting.umum');
    Route::post('/mahasiswa/setting/notifikasi', [MahasiswaSettingController::class, 'updateNotifikasi'])->name('mahasiswa.setting.notifikasi');
});

// Profile umum bawaan Laravel Breeze/Jetstream (opsional)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ==========================================
// HELP CENTER ROUTES (Accessible to All)
// ==========================================
Route::prefix('help-center')->name('help-center.')->group(function () {
    Route::get('/', [HelpCenterController::class, 'index'])->name('index');
    Route::get('/faq', [HelpCenterController::class, 'faqPage'])->name('faq');
    Route::get('/search-faq', [HelpCenterController::class, 'searchFaq'])->name('search-faq');
    Route::get('/faq/{faq}', [HelpCenterController::class, 'faqDetail'])->name('faq-detail');
    Route::post('/faq/feedback', [HelpCenterController::class, 'faqFeedback'])->name('faq-feedback');
    Route::get('/guides', [HelpCenterController::class, 'guides'])->name('guides');
    Route::get('/guides/{id}', [HelpCenterController::class, 'guideDetail'])->name('guide-detail');
    Route::post('/ticket', [HelpCenterController::class, 'storeTicket'])->name('store-ticket');
});

require __DIR__ . '/auth.php';