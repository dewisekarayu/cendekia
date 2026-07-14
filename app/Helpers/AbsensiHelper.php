<?php

namespace App\Helpers;

use App\Models\Absensi;
use App\Models\AbsensiMahasiswa;
use App\Models\KelasPerkuliahan;
use Illuminate\Support\Collection;

/**
 * Helper functions untuk sistem presensi
 */
class AbsensiHelper
{
    /**
     * Get all active attendance sessions for a student
     * (Sesi presensi yang status "buka" untuk hari ini)
     */
    public static function getActiveSessions($mahasiswaId): Collection
    {
        return Absensi::where('session_status', 'buka')
            ->whereDate('tanggal', today())
            ->whereHas('kelasPerkuliahan', function ($query) use ($mahasiswaId) {
                $query->whereHas('mahasiswa', function ($subQuery) use ($mahasiswaId) {
                    $subQuery->where('users.id', $mahasiswaId);
                });
            })
            ->with(['kelasPerkuliahan.mataKuliah'])
            ->get();
    }

    /**
     * Get attendance summary for a student in a class
     */
    public static function getStudentClassSummary($mahasiswaId, $kelasId): array
    {
        $totalSessions = Absensi::where('kelas_perkuliahan_id', $kelasId)->count();

        $attendances = AbsensiMahasiswa::whereIn(
            'absensi_id',
            Absensi::where('kelas_perkuliahan_id', $kelasId)->pluck('id')
        )->where('mahasiswa_id', $mahasiswaId)
            ->get()
            ->groupBy('status')
            ->map->count();

        return [
            'total_sessions' => $totalSessions,
            'hadir' => $attendances['hadir'] ?? 0,
            'izin' => $attendances['izin'] ?? 0,
            'sakit' => $attendances['sakit'] ?? 0,
            'alpha' => $totalSessions - ($attendances->sum()),
            'attendance_rate' => $totalSessions > 0
                ? round((($attendances['hadir'] ?? 0) / $totalSessions) * 100)
                : 0,
        ];
    }

    /**
     * Get session attendance statistics
     */
    public static function getSessionStats($absensiId): array
    {
        $absensi = Absensi::findOrFail($absensiId);
        return $absensi->getAttendanceStats();
    }

    /**
     * Check if student already attended a session
     */
    public static function hasStudentAttended($absensiId, $mahasiswaId): bool
    {
        return AbsensiMahasiswa::where('absensi_id', $absensiId)
            ->where('mahasiswa_id', $mahasiswaId)
            ->exists();
    }

    /**
     * Get student attendance status for a session
     */
    public static function getStudentAttendanceStatus($absensiId, $mahasiswaId): ?AbsensiMahasiswa
    {
        return AbsensiMahasiswa::where('absensi_id', $absensiId)
            ->where('mahasiswa_id', $mahasiswaId)
            ->first();
    }

    /**
     * Get all sessions (with optional filters)
     */
    public static function getAllSessions(
        $kelasId = null,
        $status = null,
        $orderBy = 'desc',
        $paginate = true,
        $perPage = 10
    ) {
        $query = Absensi::query();

        if ($kelasId) {
            $query->where('kelas_perkuliahan_id', $kelasId);
        }

        if ($status) {
            $query->where('session_status', $status);
        }

        $query->orderBy('tanggal', $orderBy);

        return $paginate ? $query->paginate($perPage) : $query->get();
    }

    /**
     * Get attendance report for a class
     */
    public static function getClassAttendanceReport($kelasId): array
    {
        $kelas = KelasPerkuliahan::findOrFail($kelasId);
        $sessions = Absensi::where('kelas_perkuliahan_id', $kelasId)
            ->orderBy('pertemuan_ke')
            ->get();

        $report = [];
        foreach ($kelas->mahasiswa as $mahasiswa) {
            $attendance = [];
            $totalHadir = 0;
            $totalIzin = 0;
            $totalSakit = 0;
            $totalAlpha = 0;

            foreach ($sessions as $session) {
                $studentAttendance = AbsensiMahasiswa::where('absensi_id', $session->id)
                    ->where('mahasiswa_id', $mahasiswa->id)
                    ->first();

                if ($studentAttendance) {
                    $status = $studentAttendance->status;
                    $attendance[] = [
                        'session' => $session->pertemuan_ke,
                        'status' => $status,
                    ];
                    if ($status === 'hadir') $totalHadir++;
                    elseif ($status === 'izin') $totalIzin++;
                    elseif ($status === 'sakit') $totalSakit++;
                } else {
                    $attendance[] = [
                        'session' => $session->pertemuan_ke,
                        'status' => 'alpha',
                    ];
                    $totalAlpha++;
                }
            }

            $report[] = [
                'mahasiswa_id' => $mahasiswa->id,
                'nama' => $mahasiswa->name,
                'nim' => $mahasiswa->nim,
                'hadir' => $totalHadir,
                'izin' => $totalIzin,
                'sakit' => $totalSakit,
                'alpha' => $totalAlpha,
                'total' => count($sessions),
                'attendance_rate' => count($sessions) > 0
                    ? round(($totalHadir / count($sessions)) * 100)
                    : 0,
                'detail' => $attendance,
            ];
        }

        return $report;
    }

    /**
     * Get attendance statistics for all classes taught by a lecturer
     */
    public static function getLecturerStatistics($dosenId): array
    {
        $classes = KelasPerkuliahan::where('dosen_id', $dosenId)
            ->orWhereJsonContains('dosen_pengampu', $dosenId)
            ->get();

        $statistics = [
            'total_classes' => $classes->count(),
            'total_sessions' => 0,
            'total_students' => 0,
            'average_attendance_rate' => 0,
        ];

        $totalAttendanceRate = 0;
        $classCount = 0;

        foreach ($classes as $class) {
            $sessions = Absensi::where('kelas_perkuliahan_id', $class->id)->count();
            $statistics['total_sessions'] += $sessions;
            $statistics['total_students'] += $class->mahasiswa->count();

            if ($sessions > 0) {
                $hadirCount = AbsensiMahasiswa::whereIn(
                    'absensi_id',
                    Absensi::where('kelas_perkuliahan_id', $class->id)->pluck('id')
                )->where('status', 'hadir')->count();

                $totalStudentSessions = $class->mahasiswa->count() * $sessions;
                $rate = $totalStudentSessions > 0 ? round(($hadirCount / $totalStudentSessions) * 100) : 0;
                $totalAttendanceRate += $rate;
                $classCount++;
            }
        }

        if ($classCount > 0) {
            $statistics['average_attendance_rate'] = round($totalAttendanceRate / $classCount);
        }

        return $statistics;
    }

    /**
     * Generate attendance certificate template
     * (For export to PDF later)
     */
    public static function generateCertificateTemplate($mahasiswaId, $kelasId, $minAttendanceRate = 80): array
    {
        $summary = self::getStudentClassSummary($mahasiswaId, $kelasId);

        $kelas = KelasPerkuliahan::findOrFail($kelasId);
        $mahasiswa = $kelas->mahasiswa()->where('users.id', $mahasiswaId)->firstOrFail();

        return [
            'mahasiswa_name' => $mahasiswa->name,
            'mahasiswa_nim' => $mahasiswa->nim,
            'mata_kuliah' => $kelas->mataKuliah->nama_mk,
            'kode_kelas' => $kelas->kode_kelas,
            'dosen_name' => $kelas->dosen->name,
            'attendance_summary' => $summary,
            'eligible' => $summary['attendance_rate'] >= $minAttendanceRate,
            'generated_at' => now(),
        ];
    }

    /**
     * Get color code for attendance status
     */
    public static function getStatusColor($status): string
    {
        return match ($status) {
            'hadir' => 'green',
            'izin' => 'blue',
            'sakit' => 'yellow',
            'alpha' => 'red',
            'draft' => 'gray',
            'buka' => 'emerald',
            'tutup' => 'red',
            default => 'gray',
        };
    }

    /**
     * Get badge class for attendance status (Tailwind)
     */
    public static function getStatusBadgeClass($status): string
    {
        return match ($status) {
            'hadir' => 'bg-green-100 text-green-800',
            'izin' => 'bg-blue-100 text-blue-800',
            'sakit' => 'bg-yellow-100 text-yellow-800',
            'alpha' => 'bg-red-100 text-red-800',
            'draft' => 'bg-gray-100 text-gray-800',
            'buka' => 'bg-emerald-100 text-emerald-800',
            'tutup' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}
