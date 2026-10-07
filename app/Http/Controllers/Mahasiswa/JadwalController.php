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
            ->with(['mataKuliah.programStudi', 'dosen', 'semester', 'jadwals'])
            ->where('status_kelas', 'aktif')
            ->where('is_active', true)
            ->get();

        // Kelompokkan berdasarkan hari untuk tampilan yang lebih terstruktur
        $jadwalByDay = [];
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        
        foreach ($days as $day) {
            $jadwalByDay[$day] = collect();
        }
        
        foreach ($kelasPerkuliahan as $kelas) {
            foreach ($kelas->jadwals as $jadwal) {
                $day = ucfirst(strtolower(trim($jadwal->hari)));
                if (isset($jadwalByDay[$day])) {
                    $kelasItem = clone $kelas;
                    $kelasItem->hari = $jadwal->hari;
                    $kelasItem->jam_mulai = $jadwal->jam_mulai;
                    $kelasItem->jam_selesai = $jadwal->jam_selesai;
                    $kelasItem->ruangan = $jadwal->ruangan;
                    $jadwalByDay[$day]->push($kelasItem);
                }
            }
        }
        
        foreach ($days as $day) {
            $jadwalByDay[$day] = $jadwalByDay[$day]->sortBy('jam_mulai')->values();
        }

        // Hitung total SKS
        $totalSKS = $kelasPerkuliahan->sum(function($kelas) {
            return $kelas->mataKuliah->sks ?? 0;
        });

        // Ambil data kelas pengganti (reschedule) aktif untuk kelas mahasiswa
        // Hanya yang tanggalnya hari ini atau akan datang
        $kelasIds = $kelasPerkuliahan->pluck('id');
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
            ->with(['kelasPerkuliahan.mataKuliah', 'kelasPerkuliahan.dosen'])
            ->orderBy('tanggal')
            ->get();

        return view('mahasiswa.jadwal.index', compact('kelasPerkuliahan', 'jadwalByDay', 'totalSKS', 'days', 'reschedules'));
    }

    /**
     * Menampilkan detail jadwal berdasarkan semester/tahun akademik
     */
    public function showBySemester(Request $request, $semesterId = null)
    {
        $user = Auth::user();
        
        $query = $user->kelasDiikuti()
            ->with(['mataKuliah.programStudi', 'dosen', 'semester', 'jadwals']);

        if ($semesterId) {
            $query->where('semester_id', $semesterId);
        }

        $kelasPerkuliahan = $query->get();

        $semesterList = \App\Models\Semester::all();
        
        // Kelompokkan berdasarkan hari
        $jadwalByDay = [];
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        
        foreach ($days as $day) {
            $jadwalByDay[$day] = collect();
        }
        
        foreach ($kelasPerkuliahan as $kelas) {
            foreach ($kelas->jadwals as $jadwal) {
                $day = ucfirst(strtolower(trim($jadwal->hari)));
                if (isset($jadwalByDay[$day])) {
                    $kelasItem = clone $kelas;
                    $kelasItem->hari = $jadwal->hari;
                    $kelasItem->jam_mulai = $jadwal->jam_mulai;
                    $kelasItem->jam_selesai = $jadwal->jam_selesai;
                    $kelasItem->ruangan = $jadwal->ruangan;
                    $jadwalByDay[$day]->push($kelasItem);
                }
            }
        }
        
        foreach ($days as $day) {
            $jadwalByDay[$day] = $jadwalByDay[$day]->sortBy('jam_mulai')->values();
        }

        $totalSKS = $kelasPerkuliahan->sum(function($kelas) {
            return $kelas->mataKuliah->sks ?? 0;
        });

        return view('mahasiswa.jadwal.semester', compact('jadwalByDay', 'totalSKS', 'days', 'semesterList', 'semesterId'));
    }

    /**
     * Menampilkan jadwal dalam bentuk kalender
     */
    public function calendar()
    {
        $user = Auth::user();
        
        $kelasPerkuliahan = $user->kelasDiikuti()
            ->with(['mataKuliah', 'dosen', 'jadwals'])
            ->where('status_kelas', 'aktif')
            ->where('is_active', true)
            ->get();

        // Format untuk kalender (FullCalendar)
        $calendarEvents = [];
        
        foreach ($kelasPerkuliahan as $kelas) {
            foreach ($kelas->jadwals as $jadwal) {
                // Map hari ke format day-of-week (1=Senin, 7=Minggu)
                $dayMap = [
                    'senin' => 1,
                    'selasa' => 2,
                    'rabu' => 3,
                    'kamis' => 4,
                    'jumat' => 5,
                    'sabtu' => 6,
                    'minggu' => 7
                ];
                
                $hariKey = strtolower(trim($jadwal->hari));
                $dayOfWeek = $dayMap[$hariKey] ?? 1;
                
                $calendarEvents[] = [
                    'id' => $kelas->id . '-' . $jadwal->id,
                    'title' => $kelas->mataKuliah->nama_mk . ' - ' . $jadwal->ruangan,
                    'startTime' => $jadwal->jam_mulai,
                    'endTime' => $jadwal->jam_selesai,
                    'daysOfWeek' => [$dayOfWeek],
                    'extendedProps' => [
                        'kode_kelas' => $kelas->kode_kelas,
                        'dosen' => $kelas->dosen->name,
                        'ruangan' => $jadwal->ruangan,
                        'sks' => $kelas->mataKuliah->sks ?? 0,
                    ]
                ];
            }
        }

        return view('mahasiswa.jadwal.calendar', compact('calendarEvents'));
    }
}
