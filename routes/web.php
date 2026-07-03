<?php

use App\Http\Controllers\ProfileController;
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

// Dashboard khusus Admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});

// Dashboard & halaman khusus Dosen
Route::middleware(['auth', 'role:dosen'])->group(function () {
    Route::get('/dosen/dashboard', function () {
        return view('dosen.dashboard');
    })->name('dosen.dashboard');

    Route::get('/dosen/kelas-saya', function () {
        $kelasList = auth()->user()->kelasDiampu()->with(['mataKuliah', 'mahasiswa'])->get();
        return view('dosen.kelas-saya', compact('kelasList'));
    })->name('dosen.kelas-saya');

    Route::get('/dosen/kelas/{id}', function ($id) {
        $kelas = \App\Models\KelasPerkuliahan::with(['mataKuliah.programStudi', 'mahasiswa'])
            ->where('dosen_id', auth()->id())
            ->findOrFail($id);
        return view('dosen.kelas-detail', compact('kelas'));
    })->name('dosen.kelas-detail');

    Route::get('/dosen/kelas/{id}/tugas', function ($id) {
        $kelas = \App\Models\KelasPerkuliahan::with(['mataKuliah', 'mahasiswa'])
            ->where('dosen_id', auth()->id())
            ->findOrFail($id);

        $tugasList = \App\Models\Tugas::where('kelas_perkuliahan_id', $kelas->id)
            ->withCount([
                'pengumpulan as submitted_count' => fn ($q) => $q->where('status', '!=', 'belum_dikumpulkan'),
            ])
            ->latest()
            ->get();

        return view('dosen.kelas-tugas', compact('kelas', 'tugasList'));
    })->name('dosen.kelas-tugas');
});

// Dashboard khusus Mahasiswa
Route::middleware(['auth', 'role:mahasiswa'])->group(function () {
    Route::get('/mahasiswa/dashboard', function () {
        $user = auth()->user();

        // Kalau belum pilih prodi, siapkan daftar prodi untuk ditampilkan
        if (! $user->program_studi_id) {
            $prodiList = \App\Models\ProgramStudi::all();
            return view('mahasiswa.dashboard', [
                'needProdi' => true,
                'prodiList' => $prodiList,
                'courses' => collect(),
                'deadlines' => collect(),
                'announcements' => collect(),
            ]);
        }

        $kelasDiikuti = $user->kelasDiikuti()->with(['mataKuliah.programStudi', 'dosen'])->get();

        $courses = $kelasDiikuti->map(function ($kelas) use ($user) {
            $totalTugas = \App\Models\Tugas::where('kelas_perkuliahan_id', $kelas->id)->count();
            $submitted = \App\Models\PengumpulanTugas::whereHas('tugas', fn ($q) => $q->where('kelas_perkuliahan_id', $kelas->id))
                ->where('mahasiswa_id', $user->id)
                ->where('status', '!=', 'belum_dikumpulkan')
                ->count();

            return [
                'id' => $kelas->id,
                'tag' => strtoupper($kelas->mataKuliah?->programStudi?->nama_prodi ?? 'UMUM'),
                'title' => $kelas->mataKuliah?->nama_mk ?? '-',
                'dosen' => $kelas->dosen?->name ?? '-',
                'progress' => $totalTugas > 0 ? round(($submitted / $totalTugas) * 100) : 0,
            ];
        });

        $kelasIds = $kelasDiikuti->pluck('id');

        $deadlines = \App\Models\Tugas::whereIn('kelas_perkuliahan_id', $kelasIds)
            ->where('deadline', '>=', now())
            ->with('kelasPerkuliahan.mataKuliah')
            ->orderBy('deadline')
            ->take(3)
            ->get();

        $announcements = \App\Models\Pengumuman::where(function ($q) use ($kelasIds) {
                $q->whereIn('kelas_perkuliahan_id', $kelasIds)->orWhere('untuk_semua', true);
            })
            ->latest()
            ->take(2)
            ->get();

        return view('mahasiswa.dashboard', [
            'needProdi' => false,
            'courses' => $courses,
            'deadlines' => $deadlines,
            'announcements' => $announcements,
        ]);
    })->name('mahasiswa.dashboard');

    Route::post('/mahasiswa/pilih-prodi/{id}', function ($id) {
        $prodi = \App\Models\ProgramStudi::findOrFail($id);
        auth()->user()->update(['program_studi_id' => $prodi->id]);

        return redirect()->route('mahasiswa.dashboard')
            ->with('success', 'Berhasil bergabung ke program studi ' . $prodi->nama_prodi . '!');
    })->name('mahasiswa.pilih-prodi');

    Route::get('/mahasiswa/kelas-saya', function () {
        $kelasList = auth()->user()->kelasDiikuti()->with(['mataKuliah.programStudi', 'dosen'])->get();
        return view('mahasiswa.kelas-saya', compact('kelasList'));
    })->name('mahasiswa.kelas-saya');

    Route::get('/mahasiswa/kelas/{id}', function ($id) {
        $kelas = \App\Models\KelasPerkuliahan::with(['mataKuliah.programStudi', 'dosen'])
            ->findOrFail($id);

        $sudahGabung = \App\Models\KelasMahasiswa::where('kelas_perkuliahan_id', $kelas->id)
            ->where('mahasiswa_id', auth()->id())
            ->exists();

        abort_unless($sudahGabung, 403, 'Kamu belum bergabung ke kelas ini.');

        return view('mahasiswa.kelas-detail', compact('kelas'));
    })->name('mahasiswa.kelas-detail');

    Route::get('/mahasiswa/jelajahi-kelas', function () {
        $joinedIds = auth()->user()->kelasDiikuti()->pluck('kelas_perkuliahan.id');

        $kelasList = \App\Models\KelasPerkuliahan::with(['mataKuliah.programStudi', 'dosen', 'semester'])
            ->whereHas('mataKuliah', fn ($q) => $q->where('program_studi_id', auth()->user()->program_studi_id))
            ->whereHas('semester', fn ($q) => $q->where('is_active', true))
            ->whereNotIn('id', $joinedIds)
            ->get();

        return view('mahasiswa.jelajahi-kelas', compact('kelasList'));
    })->name('mahasiswa.jelajahi-kelas');

    Route::post('/mahasiswa/kelas/{id}/join', function ($id) {
        $kelas = \App\Models\KelasPerkuliahan::findOrFail($id);

        $sudahGabung = \App\Models\KelasMahasiswa::where('kelas_perkuliahan_id', $kelas->id)
            ->where('mahasiswa_id', auth()->id())
            ->exists();

        if (! $sudahGabung) {
            \App\Models\KelasMahasiswa::create([
                'kelas_perkuliahan_id' => $kelas->id,
                'mahasiswa_id' => auth()->id(),
                'tanggal_daftar' => now(),
            ]);
        }

        return back()->with('success', 'Berhasil bergabung ke kelas!');
    })->name('mahasiswa.kelas.join');

    Route::get('/mahasiswa/gradebook', function () {
        $nilaiList = \App\Models\PengumpulanTugas::with('tugas.kelasPerkuliahan.mataKuliah')
            ->where('mahasiswa_id', auth()->id())
            ->whereNotNull('nilai')
            ->get();
        return view('mahasiswa.gradebook', compact('nilaiList'));
    })->name('mahasiswa.gradebook');

    Route::get('/mahasiswa/forums', function () {
        $kelasIds = auth()->user()->kelasDiikuti()->pluck('kelas_perkuliahan.id');
        $forumList = \App\Models\ForumDiskusi::whereIn('kelas_perkuliahan_id', $kelasIds)
            ->with(['kelasPerkuliahan.mataKuliah', 'pembuat'])
            ->latest()
            ->get();
        return view('mahasiswa.forums', compact('forumList'));
    })->name('mahasiswa.forums');

    Route::get('/mahasiswa/schedule', function () {
        $kelasList = auth()->user()->kelasDiikuti()->with('mataKuliah')->get();
        return view('mahasiswa.schedule', compact('kelasList'));
    })->name('mahasiswa.schedule');
});

require __DIR__ . '/auth.php';
