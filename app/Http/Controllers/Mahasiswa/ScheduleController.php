<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Semester;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $activeSemesterId = Semester::where('is_active', true)->value('id');

        $kelasDiikuti = $request->user()->kelasDiikuti()
            ->with(['mataKuliah', 'dosen', 'jadwals'])
            ->where('status_kelas', 'aktif')
            ->where('is_active', true)
            ->when($activeSemesterId, fn ($query) => $query->where('semester_id', $activeSemesterId))
            ->get();

        $kelasList = $kelasDiikuti->flatMap(function ($kelas) {
            return $kelas->jadwals->map(function ($jadwal) use ($kelas) {
                return (object) [
                    'id' => $kelas->id,
                    'mataKuliah' => $kelas->mataKuliah,
                    'dosen' => $kelas->dosen,
                    'hari' => ucfirst(mb_strtolower(trim($jadwal->hari))),
                    'jam_mulai' => $jadwal->jam_mulai,
                    'jam_selesai' => $jadwal->jam_selesai,
                    'ruangan' => $jadwal->ruangan,
                ];
            });
        })->values();

        $kelasIds = $kelasDiikuti->pluck('id');
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

        return view('mahasiswa.schedule', compact('kelasList', 'reschedules'));
    }
}