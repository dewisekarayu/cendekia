<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KelasPerkuliahan;
use Illuminate\Support\Facades\Auth;

class JadwalController extends Controller
{
    /**
     * Menampilkan jadwal mengajar dosen
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Ambil kelas yang diampu dosen (baik sebagai dosen utama maupun team teaching)
        $kelasPerkuliahan = KelasPerkuliahan::where(function($query) use ($user) {
                $query->where('dosen_id', $user->id)
                    ->orWhereJsonContains('dosen_pengampu', $user->id);
            })
            ->with(['mataKuliah.programStudi', 'semester', 'mahasiswa'])
            ->where('status_kelas', 'aktif')
            ->where('is_active', true)
            ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')")
            ->orderBy('jam_mulai')
            ->get();

        // Kelompokkan berdasarkan hari
        $jadwalByDay = [];
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        
        foreach ($days as $day) {
            $jadwalByDay[$day] = $kelasPerkuliahan->where('hari', $day)->sortBy('jam_mulai')->values();
        }

        // Hitung statistik
        $totalKelas = $kelasPerkuliahan->count();
        $totalMahasiswa = $kelasPerkuliahan->sum('jumlah_mahasiswa');
        $totalSKS = $kelasPerkuliahan->sum(function($kelas) {
            return $kelas->mataKuliah->sks ?? 0;
        });

        return view('dosen.jadwal.index', compact('jadwalByDay', 'totalKelas', 'totalMahasiswa', 'totalSKS', 'days'));
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
                    ->orWhereJsonContains('dosen_pengampu', $user->id);
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
                    ->orWhereJsonContains('dosen_pengampu', $user->id);
            })
            ->with(['mataKuliah', 'mahasiswa'])
            ->where('status_kelas', 'aktif')
            ->where('is_active', true)
            ->get();

        // Format untuk kalender
        $calendarEvents = [];
        
        foreach ($kelasPerkuliahan as $kelas) {
            // Map hari ke format day-of-week
            $dayMap = [
                'Senin' => 1,
                'Selasa' => 2,
                'Rabu' => 3,
                'Kamis' => 4,
                'Jumat' => 5,
                'Sabtu' => 6,
                'Minggu' => 7
            ];
            
            $dayOfWeek = $dayMap[$kelas->hari] ?? 1;
            
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
     * Export jadwal ke PDF
     */
    public function exportPdf(Request $request)
    {
        $user = Auth::user();
        
        $kelasPerkuliahan = KelasPerkuliahan::where(function($query) use ($user) {
                $query->where('dosen_id', $user->id)
                    ->orWhereJsonContains('dosen_pengampu', $user->id);
            })
            ->with(['mataKuliah.programStudi', 'semester'])
            ->where('status_kelas', 'aktif')
            ->where('is_active', true)
            ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')")
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
