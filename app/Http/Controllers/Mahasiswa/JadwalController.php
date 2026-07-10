<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\KelasPerkuliahan;
use Illuminate\Support\Facades\Auth;

class JadwalController extends Controller
{
    /**
     * Menampilkan jadwal kuliah mahasiswa
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Ambil kelas yang diikuti mahasiswa
        $kelasPerkuliahan = $user->kelasDiikuti()
            ->with(['mataKuliah.programStudi', 'dosen', 'semester'])
            ->where('status_kelas', 'aktif')
            ->where('is_active', true)
            ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')")
            ->orderBy('jam_mulai')
            ->get();

        // Kelompokkan berdasarkan hari untuk tampilan yang lebih terstruktur
        $jadwalByDay = [];
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        
        foreach ($days as $day) {
            $jadwalByDay[$day] = $kelasPerkuliahan->where('hari', $day)->sortBy('jam_mulai')->values();
        }

        // Hitung total SKS
        $totalSKS = $kelasPerkuliahan->sum(function($kelas) {
            return $kelas->mataKuliah->sks ?? 0;
        });

        return view('mahasiswa.jadwal.index', compact('jadwalByDay', 'totalSKS', 'days'));
    }

    /**
     * Menampilkan detail jadwal berdasarkan semester/tahun akademik
     */
    public function showBySemester(Request $request, $semesterId = null)
    {
        $user = Auth::user();
        
        $query = $user->kelasDiikuti()
            ->with(['mataKuliah.programStudi', 'dosen', 'semester']);

        if ($semesterId) {
            $query->where('semester_id', $semesterId);
        }

        $kelasPerkuliahan = $query->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')")
            ->orderBy('jam_mulai')
            ->get();

        $semesterList = \App\Models\Semester::all();
        
        // Kelompokkan berdasarkan hari
        $jadwalByDay = [];
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        
        foreach ($days as $day) {
            $jadwalByDay[$day] = $kelasPerkuliahan->where('hari', $day)->sortBy('jam_mulai')->values();
        }

        return view('mahasiswa.jadwal.semester', compact('jadwalByDay', 'totalSKS', 'days', 'semesterList', 'semesterId'));
    }

    /**
     * Menampilkan jadwal dalam bentuk kalender
     */
    public function calendar()
    {
        $user = Auth::user();
        
        $kelasPerkuliahan = $user->kelasDiikuti()
            ->with(['mataKuliah', 'dosen'])
            ->where('status_kelas', 'aktif')
            ->where('is_active', true)
            ->get();

        // Format untuk kalender (FullCalendar)
        $calendarEvents = [];
        
        foreach ($kelasPerkuliahan as $kelas) {
            // Map hari ke format day-of-week (1=Senin, 7=Minggu)
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
                'title' => $kelas->mataKuliah->nama_mk . ' - ' . $kelas->ruangan,
                'startTime' => $kelas->jam_mulai,
                'endTime' => $kelas->jam_selesai,
                'daysOfWeek' => [$dayOfWeek],
                'extendedProps' => [
                    'kode_kelas' => $kelas->kode_kelas,
                    'dosen' => $kelas->dosen->name,
                    'ruangan' => $kelas->ruangan,
                    'sks' => $kelas->mataKuliah->sks ?? 0,
                ]
            ];
        }

        return view('mahasiswa.jadwal.calendar', compact('calendarEvents'));
    }
}
