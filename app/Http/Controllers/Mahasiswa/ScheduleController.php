<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $kelasList = $request->user()->kelasDiikuti()->with('mataKuliah')->get();

        $kelasIds = $kelasList->pluck('id');
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