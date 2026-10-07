<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KelasPerkuliahan;
use App\Models\Absensi;
use App\Models\Pengumuman;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class JadwalController extends Controller
{
    /**
     * Menampilkan jadwal mengajar dan log mengajar dosen dalam satu halaman terpadu
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Ambil kelas yang diampu dosen (baik sebagai dosen utama maupun team teaching)
        $kelasPerkuliahan = KelasPerkuliahan::where(function($query) use ($user) {
                $query->where('dosen_id', $user->id)
                    ->orWhereHas('dosenPengampuTambahan', function ($q) use ($user) {
                        $q->where('users.id', $user->id);
                    });
            })
            ->with([
                'mataKuliah.programStudi',
                'semester',
                'mahasiswa',
                'dosen',
                'dosenPengampuTambahan',
                'absensi' => fn ($q) => $q
                    ->withCount([
                        'absensiMahasiswa as hadir_count' => fn ($query) => $query->where('status', 'hadir'),
                    ])
                    ->orderBy('pertemuan_ke'),
            ])
            ->where('status_kelas', 'aktif')
            ->where('is_active', true)
            ->orderByRaw("FIELD(LOWER(TRIM(hari)), 'senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu')")
            ->orderBy('jam_mulai')
            ->get();

        // Kelompokkan berdasarkan hari (case-insensitive & trim)
        $jadwalByDay = [];
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];

        foreach ($days as $day) {
            $jadwalByDay[$day] = $kelasPerkuliahan
                ->filter(function ($kelas) use ($day) {
                    return strtolower(trim($kelas->hari ?? '')) === strtolower($day);
                })
                ->sortBy('jam_mulai')
                ->values();
        }

        // Tampilan log mengajar dikelompokkan berdasarkan kelas & mata kuliah
        $kelasList = $kelasPerkuliahan
            ->filter(fn (KelasPerkuliahan $kelas) => $kelas->mataKuliah !== null)
            ->groupBy(fn (KelasPerkuliahan $kelas) => $kelas->kode_kelas ?: 'Tanpa Kode Kelas')
            ->map(function ($mataKuliahDalamKelas, $kodeKelas) {
                return (object) [
                    'id' => md5($kodeKelas),
                    'kode' => $kodeKelas,
                    'jumlah_mata_kuliah' => $mataKuliahDalamKelas->count(),
                    'total_pertemuan' => $mataKuliahDalamKelas->sum(fn ($k) => $k->absensi->count()),
                    'mata_kuliah' => $mataKuliahDalamKelas
                        ->sortBy(fn (KelasPerkuliahan $kelas) => $kelas->mataKuliah->nama_mk)
                        ->values(),
                ];
            })
            ->sortBy('kode')
            ->values();

        // Hitung statistik komprehensif
        $totalKelas = $kelasPerkuliahan->count();
        $totalMahasiswa = $kelasPerkuliahan->sum(fn($k) => $k->mahasiswa->count());
        $totalSKS = $kelasPerkuliahan->sum(function($kelas) {
            return $kelas->mataKuliah->sks ?? 0;
        });

        // Statistik Sesi Pertemuan (Log Mengajar)
        $allAbsensi = $kelasPerkuliahan->flatMap->absensi;
        $totalSesi = $allAbsensi->count();
        $sesiBuka = $allAbsensi->where('session_status', 'buka')->count();
        $sesiDraft = $allAbsensi->where('session_status', 'draft')->count();
        $sesiTutup = $allAbsensi->where('session_status', 'tutup')->count();

        // Daftar 12 bulan tetap (Januari - Desember) untuk filter dropdown
        $namaBulan = [
            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
            '04' => 'April',   '05' => 'Mei',      '06' => 'Juni',
            '07' => 'Juli',    '08' => 'Agustus',  '09' => 'September',
            '10' => 'Oktober', '11' => 'November', '12' => 'Desember',
        ];

        $monthCounts = $allAbsensi
            ->filter(fn ($a) => !empty($a->tanggal))
            ->groupBy(fn ($a) => \Carbon\Carbon::parse($a->tanggal)->format('m'))
            ->map->count();

        $availableMonths = collect($namaBulan)->map(fn ($label, $key) => [
            'key'   => $key,
            'label' => $label,
            'count' => $monthCounts[$key] ?? 0,
        ])->values();

        // Agenda Hari Ini
        $dayMap = [
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
        ];
        $todayEnglish = now()->format('l');
        $todayName = $dayMap[$todayEnglish] ?? 'Senin';
        $kelasHariIni = $jadwalByDay[$todayName] ?? collect();

        // Tab aktif default (bisa diatur via parameter ?tab=log atau jika dari route log-book)
        $activeTab = $request->query('tab');
        if (!$activeTab) {
            $activeTab = $request->routeIs('dosen.log-book') ? 'log' : 'jadwal';
        }

        $totalSesi = $kelasList->flatMap->mata_kuliah->flatMap->absensi->count();

        // Ambil data jadwal pengganti (Reschedule)
        $kelasIds = $kelasPerkuliahan->pluck('id')->toArray();
        $nowDate = now()->toDateString();
        $nowTime = now()->toTimeString();

        $reschedules = \App\Models\Absensi::where('is_pengganti', true)
            ->whereIn('kelas_perkuliahan_id', $kelasIds)
            ->where(function($query) use ($nowDate, $nowTime) {
                $query->whereDate('tanggal', '>', $nowDate)
                      ->orWhere(function($q) use ($nowDate, $nowTime) {
                          $q->whereDate('tanggal', '=', $nowDate)
                            ->where('jam_selesai', '>=', $nowTime);
                      });
            })
            ->with(['kelasPerkuliahan.mataKuliah'])
            ->orderBy('tanggal')
            ->get();

        // Ambil daftar ruangan unik dari semua kelas yang ada di tabel kelas_perkuliahan
        $availableRooms = \App\Models\KelasPerkuliahan::whereNotNull('ruangan')
            ->where('ruangan', '!=', '')
            ->distinct()
            ->pluck('ruangan')
            ->sort()
            ->values();

        return view('dosen.jadwal.index', compact(
            'kelasPerkuliahan',
            'jadwalByDay',
            'kelasList',
            'totalKelas',
            'totalMahasiswa',
            'totalSKS',
            'totalSesi',
            'sesiBuka',
            'sesiDraft',
            'sesiTutup',
            'availableMonths',
            'days',
            'todayName',
            'kelasHariIni',
            'activeTab',
            'availableRooms',
            'reschedules'
        ));
    }

    /**
     * Menampilkan detail kelas yang diampu
     */
    public function show($id)
    {
        $user = Auth::user();
        
        $kelas = KelasPerkuliahan::where('id', $id)
            ->where(function($query) use ($user) {
                $query->where('dosen_id', $user->id)
                    ->orWhereHas('dosenPengampuTambahan', function ($q) use ($user) {
                        $q->where('users.id', $user->id);
                    });
            })
            ->with(['mataKuliah.programStudi', 'semester', 'mahasiswa', 'absensi'])
            ->firstOrFail();

        // Data untuk grafik/chart (jika diperlukan)
        $presensiData = $this->getPresensiData($kelas);
        $nilaiData = $this->getNilaiData($kelas);

        return view('dosen.jadwal.show', compact('kelas', 'presensiData', 'nilaiData'));
    }

    /**
     * Menampilkan jadwal dalam bentuk kalender
     */
    public function calendar()
    {
        $user = Auth::user();
        
        $kelasPerkuliahan = KelasPerkuliahan::where(function($query) use ($user) {
                $query->where('dosen_id', $user->id)
                    ->orWhereHas('dosenPengampuTambahan', function ($q) use ($user) {
                        $q->where('users.id', $user->id);
                    });
            })
            ->with(['mataKuliah', 'mahasiswa'])
            ->where('status_kelas', 'aktif')
            ->where('is_active', true)
            ->get();

        // Format untuk kalender
        $calendarEvents = [];
        
        foreach ($kelasPerkuliahan as $kelas) {
            // Map hari ke format day-of-week (case-insensitive & trim)
            $dayMap = [
                'senin' => 1,
                'selasa' => 2,
                'rabu' => 3,
                'kamis' => 4,
                'jumat' => 5,
                'sabtu' => 6,
                'minggu' => 7,
            ];

            $hariKey = strtolower(trim($kelas->hari));
            $dayOfWeek = $dayMap[$hariKey] ?? 1;
            
            $calendarEvents[] = [
                'id' => $kelas->id,
                'title' => $kelas->mataKuliah->nama_mk . ' - ' . $kelas->kode_kelas,
                'startTime' => $kelas->jam_mulai,
                'endTime' => $kelas->jam_selesai,
                'daysOfWeek' => [$dayOfWeek],
                'backgroundColor' => $this->getColorByProgramStudi($kelas->program_studi_id),
                'extendedProps' => [
                    'kode_kelas' => $kelas->kode_kelas,
                    'ruangan' => $kelas->ruangan,
                    'jumlah_mahasiswa' => $kelas->jumlah_mahasiswa,
                    'sks' => $kelas->mataKuliah->sks ?? 0,
                ]
            ];
        }

        return view('dosen.jadwal.calendar', compact('calendarEvents'));
    }

    /**
     * Simpan data reschedule kelas pengganti.
     * - Membuat record Absensi baru dengan flag is_pengganti = true.
     * - Otomatis membuat Pengumuman untuk seluruh mahasiswa di kelas tersebut.
     */
    public function reschedule(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'kelas_perkuliahan_id' => ['required', 'exists:kelas_perkuliahan,id'],
            'tanggal_pengganti'    => ['required', 'date', 'after_or_equal:today'],
            'jam_mulai'            => ['required', 'string', 'max:10'],
            'jam_selesai'          => ['required', 'string', 'max:10', 'after:jam_mulai'],
            'ruangan_pengganti'    => ['required', 'string', 'max:100'],
            'alasan_pengganti'     => ['nullable', 'string', 'max:1000'],
        ], [
            'tanggal_pengganti.after_or_equal' => 'Tanggal pengganti tidak boleh di masa lalu.',
            'jam_selesai.after' => 'Jam selesai harus setelah jam mulai.',
            'alasan_pengganti.required' => 'Alasan reschedule wajib diisi.',
            'ruangan_pengganti.required' => 'Ruangan pengganti wajib diisi.',
        ]);

        // Verifikasi bahwa dosen memang mengampu kelas ini
        $kelas = KelasPerkuliahan::where('id', $validated['kelas_perkuliahan_id'])
            ->where(function ($query) use ($user) {
                $query->where('dosen_id', $user->id)
                    ->orWhereHas('dosenPengampuTambahan', function ($q) use ($user) {
                        $q->where('users.id', $user->id);
                    });
            })
            ->with('mataKuliah')
            ->firstOrFail();

        // Hitung pertemuan_ke berikutnya
        $nextPertemuan = (Absensi::where('kelas_perkuliahan_id', $kelas->id)->max('pertemuan_ke') ?? 0) + 1;

        $tanggalFormatted = Carbon::parse($validated['tanggal_pengganti'])->translatedFormat('l, d F Y');
        $jamRange = substr($validated['jam_mulai'], 0, 5) . ' - ' . substr($validated['jam_selesai'], 0, 5) . ' WIB';

        \Illuminate\Support\Facades\DB::transaction(function() use ($validated, $kelas, $user, $tanggalFormatted, $jamRange, $nextPertemuan) {
            // Buat sesi absensi pengganti
            $absensi = Absensi::create([
                'kelas_perkuliahan_id' => $kelas->id,
                'pertemuan_ke'         => $nextPertemuan,
                'tanggal'              => $validated['tanggal_pengganti'],
                'jam_mulai'            => $validated['jam_mulai'],
                'jam_selesai'          => $validated['jam_selesai'],
                'session_status'       => 'draft',
                'is_pengganti'         => true,
                'ruangan_pengganti'    => $validated['ruangan_pengganti'],
                'alasan_pengganti'     => $validated['alasan_pengganti'],
            ]);

            // Auto-create pengumuman untuk seluruh mahasiswa di kelas
            $pengumuman = Pengumuman::create([
                'kelas_perkuliahan_id' => $kelas->id,
                'dibuat_oleh'          => $user->id,
                'judul'                => '🔄 Kelas Pengganti: ' . $kelas->mataKuliah->nama_mk,
                'isi'                  => "Diberitahukan kepada seluruh mahasiswa bahwa perkuliahan **{$kelas->mataKuliah->nama_mk}** ({$kelas->kode_kelas}) akan diadakan kelas pengganti dengan jadwal sebagai berikut:\n\n"
                                        . "📅 **Tanggal:** {$tanggalFormatted}\n"
                                        . "🕐 **Jam:** {$jamRange}\n"
                                        . "🏫 **Ruangan:** {$validated['ruangan_pengganti']}\n\n"
                                        . "📝 **Alasan:** " . ($validated['alasan_pengganti'] ?: '-') . "\n\n"
                                        . "Mohon untuk hadir tepat waktu. Terima kasih.",
                'untuk_semua'          => false,
            ]);

            // Buat notifikasi untuk semua mahasiswa di kelas
            $mahasiswaIds = $kelas->mahasiswa->pluck('id');
            $notifikasiData = [];
            $now = now();
            foreach ($mahasiswaIds as $mhsId) {
                $notifikasiData[] = [
                    'user_id' => $mhsId,
                    'kelas_perkuliahan_id' => $kelas->id,
                    'judul' => 'Pengumuman: Kelas Pengganti ' . $kelas->mataKuliah->nama_mk,
                    'pesan' => "Terdapat perubahan jadwal (reschedule) untuk kelas {$kelas->mataKuliah->nama_mk}. Silakan cek pengumuman atau jadwal terbaru.",
                    'tipe' => 'informasi',
                    'url' => route('mahasiswa.schedule'),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            if (!empty($notifikasiData)) {
                \App\Models\Notifikasi::insert($notifikasiData);
            }
        });

        return redirect()->route('dosen.jadwal.index')
            ->with('success', 'Kelas pengganti berhasil dijadwalkan untuk ' . $tanggalFormatted . '. Pengumuman otomatis telah dikirim ke seluruh mahasiswa.');
    }

    /**
     * Membatalkan/Menghapus jadwal kelas pengganti (Undo)
     */
    public function undoReschedule(Request $request, $id)
    {
        $user = Auth::user();
        
        $absensi = \App\Models\Absensi::where('id', $id)
            ->where('is_pengganti', true)
            ->firstOrFail();

        // Pastikan dosen ini berhak menghapus jadwal di kelas tersebut
        $kelas = KelasPerkuliahan::where('id', $absensi->kelas_perkuliahan_id)
            ->where(function ($query) use ($user) {
                $query->where('dosen_id', $user->id)
                    ->orWhereHas('dosenPengampuTambahan', function ($q) use ($user) {
                        $q->where('users.id', $user->id);
                    });
            })->firstOrFail();

        // Hapus absensi (reschedule)
        $absensi->delete();

        return redirect()->route('dosen.jadwal.index')
            ->with('success', 'Jadwal kelas pengganti berhasil dibatalkan.');
    }

    /**
     * Export jadwal ke PDF
     */
    public function exportPdf(Request $request)
    {
        $user = Auth::user();
        
        $kelasPerkuliahan = KelasPerkuliahan::where(function($query) use ($user) {
                $query->where('dosen_id', $user->id)
                    ->orWhereHas('dosenPengampuTambahan', function ($q) use ($user) {
                        $q->where('users.id', $user->id);
                    });
            })
            ->with(['mataKuliah.programStudi', 'semester'])
            ->where('status_kelas', 'aktif')
            ->where('is_active', true)
            ->orderByRaw("FIELD(LOWER(TRIM(hari)), 'senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu')")
            ->orderBy('jam_mulai')
            ->get();

        // Data untuk PDF
        $data = [
            'dosen' => $user->name,
            'nip' => $user->nip_nim,
            'kelasPerkuliahan' => $kelasPerkuliahan,
            'tanggal' => now()->format('d F Y'),
        ];

        // Implementasi PDF akan dilakukan sesuai library yang digunakan
        // return view('dosen.jadwal.pdf', $data);
        
        return redirect()->back()->with('info', 'Fitur export PDF akan segera tersedia.');
    }

    /**
     * Helper: Get data presensi untuk kelas
     */
    private function getPresensiData($kelas)
    {
        // Implementasi data presensi
        return [
            'hadir' => $kelas->absensi->where('status', 'hadir')->count(),
            'izin' => $kelas->absensi->where('status', 'izin')->count(),
            'sakit' => $kelas->absensi->where('status', 'sakit')->count(),
            'alpha' => $kelas->absensi->where('status', 'alpha')->count(),
        ];
    }

    /**
     * Helper: Get data nilai untuk kelas
     */
    private function getNilaiData($kelas)
    {
        // Implementasi data nilai
        return [
            'rata_rata' => 85.5,
            'tertinggi' => 98.0,
            'terendah' => 65.0,
            'lulus' => 25,
            'tidak_lulus' => 3,
        ];
    }

    /**
     * Helper: Get warna berdasarkan program studi
     */
    private function getColorByProgramStudi($programStudiId)
    {
        $colors = [
            '#3B82F6', // Biru
            '#10B981', // Hijau
            '#F59E0B', // Kuning
            '#EF4444', // Merah
            '#8B5CF6', // Ungu
            '#EC4899', // Pink
            '#06B6D4', // Cyan
        ];
        
        return $colors[$programStudiId % count($colors)];
    }
}