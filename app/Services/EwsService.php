<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Logika Early Warning System (EWS) tunggal, dipakai oleh dosen & mahasiswa
 * supaya status risiko selalu konsisten.
 */
class EwsService
{
    /**
     * Hitung metrik mentah untuk sekumpulan mahasiswa pada sekumpulan kelas.
     * Hanya kelas yang benar-benar diikuti tiap mahasiswa yang dihitung.
     *
     * @return array<int, array{total_sesi:int,hadir:int,total_tugas:int,dikerjakan:int,nilai_sum:float,nilai_count:int}>
     *         keyed by "mahasiswaId:kelasId"
     */
    public function metrics(Collection $mahasiswaIds, Collection $kelasIds): array
    {
        if ($mahasiswaIds->isEmpty() || $kelasIds->isEmpty()) {
            return [];
        }

        $enrolments = DB::table('kelas_mahasiswa')
            ->whereIn('mahasiswa_id', $mahasiswaIds)
            ->whereIn('kelas_perkuliahan_id', $kelasIds)
            ->get(['mahasiswa_id', 'kelas_perkuliahan_id']);

        // Total pertemuan per kelas
        $sesiPerKelas = DB::table('absensi')
            ->whereIn('kelas_perkuliahan_id', $kelasIds)
            ->groupBy('kelas_perkuliahan_id')
            ->pluck(DB::raw('COUNT(*)'), 'kelas_perkuliahan_id');

        // Jumlah hadir per mahasiswa per kelas
        $hadir = DB::table('absensi_mahasiswa')
            ->join('absensi', 'absensi_mahasiswa.absensi_id', '=', 'absensi.id')
            ->whereIn('absensi.kelas_perkuliahan_id', $kelasIds)
            ->whereIn('absensi_mahasiswa.mahasiswa_id', $mahasiswaIds)
            ->where('absensi_mahasiswa.status', 'hadir')
            ->groupBy('absensi_mahasiswa.mahasiswa_id', 'absensi.kelas_perkuliahan_id')
            ->get([
                'absensi_mahasiswa.mahasiswa_id',
                'absensi.kelas_perkuliahan_id',
                DB::raw('COUNT(*) as jumlah'),
            ])
            ->keyBy(fn ($r) => $r->mahasiswa_id . ':' . $r->kelas_perkuliahan_id);

        // Tugas yang sudah lewat deadline (atau tanpa deadline) per kelas
        $tugasJatuhTempo = DB::table('tugas')
            ->whereIn('kelas_perkuliahan_id', $kelasIds)
            ->where(fn ($q) => $q->whereNull('deadline')->orWhere('deadline', '<=', now()))
            ->get(['id', 'kelas_perkuliahan_id']);
        $tugasPerKelas = $tugasJatuhTempo->groupBy('kelas_perkuliahan_id');
        $tugasJatuhTempoIds = $tugasJatuhTempo->pluck('id')->flip();

        // Semua pengumpulan mahasiswa di kelas-kelas ini
        $pengumpulan = DB::table('pengumpulan_tugas')
            ->join('tugas', 'pengumpulan_tugas.tugas_id', '=', 'tugas.id')
            ->whereIn('tugas.kelas_perkuliahan_id', $kelasIds)
            ->whereIn('pengumpulan_tugas.mahasiswa_id', $mahasiswaIds)
            ->get([
                'pengumpulan_tugas.mahasiswa_id',
                'pengumpulan_tugas.tugas_id',
                'pengumpulan_tugas.nilai',
                'tugas.kelas_perkuliahan_id',
            ])
            ->groupBy(fn ($r) => $r->mahasiswa_id . ':' . $r->kelas_perkuliahan_id);

        $result = [];
        foreach ($enrolments as $e) {
            $key = $e->mahasiswa_id . ':' . $e->kelas_perkuliahan_id;
            $subs = $pengumpulan->get($key, collect());
            $dinilai = $subs->whereNotNull('nilai');

            $result[$key] = [
                'total_sesi'  => (int) ($sesiPerKelas[$e->kelas_perkuliahan_id] ?? 0),
                'hadir'       => (int) ($hadir->get($key)->jumlah ?? 0),
                'total_tugas' => $tugasPerKelas->get($e->kelas_perkuliahan_id, collect())->count(),
                // hanya pengumpulan untuk tugas yang sudah jatuh tempo
                'dikerjakan'  => $subs->filter(fn ($s) => isset($tugasJatuhTempoIds[$s->tugas_id]))->count(),
                'nilai_sum'   => (float) $dinilai->sum('nilai'),
                'nilai_count' => $dinilai->count(),
            ];
        }

        return $result;
    }

    /**
     * Gabungkan beberapa metrik (mis. lintas kelas) menjadi satu.
     */
    public function combine(array $list): array
    {
        $out = ['total_sesi' => 0, 'hadir' => 0, 'total_tugas' => 0, 'dikerjakan' => 0, 'nilai_sum' => 0.0, 'nilai_count' => 0];
        foreach ($list as $m) {
            foreach ($out as $k => $_) {
                $out[$k] += $m[$k];
            }
        }
        return $out;
    }

    /**
     * Ubah metrik mentah jadi skor risiko + alasan (aturan tunggal).
     */
    public function evaluate(array $m): array
    {
        $attendance = $m['total_sesi'] > 0 ? (int) round($m['hadir'] / $m['total_sesi'] * 100) : 100;
        $avgScore   = $m['nilai_count'] > 0 ? (int) round($m['nilai_sum'] / $m['nilai_count']) : 0;
        $missed     = max(0, $m['total_tugas'] - $m['dikerjakan']);

        $score = 0;
        $reasons = [];

        if ($attendance < 75) {
            $score += 40;
            $reasons[] = "Kehadiran sangat rendah ($attendance%)";
        } elseif ($attendance < 85) {
            $score += 20;
            $reasons[] = "Kehadiran perlu ditingkatkan ($attendance%)";
        }

        if ($avgScore > 0 && $avgScore < 60) {
            $score += 40;
            $reasons[] = "Rata-rata tugas rendah ($avgScore)";
        } elseif ($avgScore > 0 && $avgScore < 70) {
            $score += 20;
            $reasons[] = "Rata-rata tugas batas bawah ($avgScore)";
        }

        if ($missed >= 2) {
            $score += 30;
            $reasons[] = "Tidak mengerjakan $missed tugas";
        } elseif ($missed === 1) {
            $score += 10;
            $reasons[] = 'Tidak mengerjakan 1 tugas';
        }

        $level = $score >= 70 ? 'High' : ($score >= 40 ? 'Medium' : 'Low');

        return [
            'attendance_rate'    => $attendance,
            'avg_score'          => $avgScore,
            'missed_assignments' => $missed,
            'risk_score'         => $score,
            'risk_level'         => $level,
            'reasons'            => $reasons,
        ];
    }
}
