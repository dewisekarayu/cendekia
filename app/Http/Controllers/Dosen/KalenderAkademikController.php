<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\KalenderAkademik;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class KalenderAkademikController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:dosen');
    }

    public function index(Request $request)
    {
        $activeSemester     = Semester::where('is_active', true)->first();
        $selectedSemesterId = $request->get('semester_id', $activeSemester?->id ?? null);
        $selectedMonth      = (int) $request->get('month', now()->month);
        $selectedYear       = (int) $request->get('year', now()->year);

        // Validasi range bulan/tahun agar tidak out-of-bound
        $selectedMonth = max(1, min(12, $selectedMonth));
        $selectedYear  = max(2000, min(2100, $selectedYear));

        $semesters = Semester::orderBy('tahun_ajaran', 'desc')
            ->orderBy('jenis', 'desc')
            ->get();

        $startOfMonth = Carbon::createFromDate($selectedYear, $selectedMonth, 1)->startOfMonth();
        $endOfMonth   = Carbon::createFromDate($selectedYear, $selectedMonth, 1)->endOfMonth();

        $query = KalenderAkademik::published()
            ->with('semester')
            ->byDateRange($startOfMonth, $endOfMonth)
            ->orderBy('tanggal_mulai')
            ->orderBy('waktu_mulai');

        if ($selectedSemesterId) {
            $query->where('semester_id', $selectedSemesterId);
        }

        $events = $query->get();

        $eventsByDate = [];
        foreach ($events as $event) {
            $start = $event->tanggal_mulai->copy();
            $end   = $event->tanggal_selesai
                ? $event->tanggal_selesai->copy()
                : $start->copy();

            $loopStart = $start->lt($startOfMonth) ? $startOfMonth->copy() : $start->copy();
            $loopEnd   = $end->gt($endOfMonth) ? $endOfMonth->copy() : $end->copy();

            for ($date = $loopStart; $date->lte($loopEnd); $date->addDay()) {
                $key = $date->format('Y-m-d');
                $eventsByDate[$key][] = $event;
            }
        }

        $todayStr     = now()->toDateString();
        $todaysQuery  = KalenderAkademik::published()
            ->with('semester')
            ->whereDate('tanggal_mulai', '<=', $todayStr)
            ->where(function ($q) use ($todayStr) {
                $q->whereNull('tanggal_selesai')
                  ->orWhereDate('tanggal_selesai', '>=', $todayStr);
            })
            ->orderBy('tanggal_mulai')
            ->orderBy('waktu_mulai');

        if ($selectedSemesterId) {
            $todaysQuery->where('semester_id', $selectedSemesterId);
        }

        $todaysEvents = $todaysQuery->get();

        $upcomingQuery = KalenderAkademik::published()
            ->with('semester')
            ->upcoming(60);

        if ($selectedSemesterId) {
            $upcomingQuery->where('semester_id', $selectedSemesterId);
        }

        $upcomingEvents = $upcomingQuery->take(15)->get();

        $historyQuery = KalenderAkademik::published()
            ->with('semester')
            ->whereDate('tanggal_mulai', '<', $todayStr)
            ->where(function ($q) use ($todayStr) {
                $q->whereNotNull('tanggal_selesai')
                  ->whereDate('tanggal_selesai', '<', $todayStr);
            })
            ->orderByDesc('tanggal_mulai')
            ->orderByDesc('waktu_mulai');

        if ($selectedSemesterId) {
            $historyQuery->where('semester_id', $selectedSemesterId);
        }

        $historyEvents = $historyQuery->take(10)->get();

        return view('dosen.kalender-akademik.index', compact(
            'semesters',
            'selectedSemesterId',
            'selectedMonth',
            'selectedYear',
            'events',
            'eventsByDate',
            'todaysEvents',
            'upcomingEvents',
            'historyEvents'
        ));
    }
}
