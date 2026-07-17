<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KalenderAkademik;
use App\Models\KalenderAktivitasLog;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

class KalenderAkademikController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:admin');
    }

    public function index(Request $request)
    {
        $query = KalenderAkademik::with(['semester', 'creator', 'updater'])
            ->orderBy('tanggal_mulai', 'asc')
            ->orderBy('waktu_mulai', 'asc');

        // Filters
        if ($request->filled('semester_id')) {
            $query->where('semester_id', $request->semester_id);
        }

        if ($request->filled('jenis_kegiatan')) {
            $query->where('jenis_kegiatan', $request->jenis_kegiatan);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%")
                  ->orWhere('lokasi', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $status = $request->status;
            if ($status === 'published') {
                $query->published();
            } elseif ($status === 'draft') {
                $query->where('is_published', false);
            } elseif ($status === 'upcoming') {
                $query->upcoming();
            } elseif ($status === 'ongoing') {
                $query->today();
            }
        }

        if ($request->filled('date_from')) {
            $query->whereDate('tanggal_mulai', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('tanggal_mulai', '<=', $request->date_to);
        }

        $kalender = $query->paginate(15)->withQueryString();

        $semesters = Semester::orderBy('tahun_ajaran', 'desc')
            ->orderBy('jenis', 'desc')
            ->get();

        $jenisKegiatanOptions = KalenderAkademik::getJenisKegiatanOptions();

        $selectedSemesterId = $request->filled('semester_id')
            ? $request->semester_id
            : ($semesters->firstWhere('is_active', true)?->id ?? $semesters->first()?->id);

        $selectedMonth = $request->filled('month') ? (int) $request->month : (int) now()->month;
        $selectedYear  = $request->filled('year')  ? (int) $request->year  : (int) now()->year;

        // Validasi range
        $selectedMonth = max(1, min(12, $selectedMonth));
        $selectedYear  = max(2000, min(2100, $selectedYear));

        $startDate = Carbon::createFromDate($selectedYear, $selectedMonth, 1)->startOfMonth();
        $endDate   = Carbon::createFromDate($selectedYear, $selectedMonth, 1)->endOfMonth();

        // ============================================================
        // BUG FIX: Admin sebelumnya groupBy('tanggal_mulai') saja —
        // event multi-hari hanya muncul di tanggal mulai.
        // Perbaikan: sama seperti controller mahasiswa, expand per hari.
        // ============================================================
        $monthEventsQuery = KalenderAkademik::published()
            ->byDateRange($startDate, $endDate)
            ->with('semester')
            ->orderBy('tanggal_mulai')
            ->orderBy('waktu_mulai');

        if ($selectedSemesterId) {
            $monthEventsQuery->where('semester_id', $selectedSemesterId);
        }

        $monthEvents = $monthEventsQuery->get();

        $eventsByDate = [];
        foreach ($monthEvents as $event) {
            $start     = $event->tanggal_mulai->copy();
            $end       = $event->tanggal_selesai ? $event->tanggal_selesai->copy() : $start->copy();
            $loopStart = $start->lt($startDate) ? $startDate->copy() : $start->copy();
            $loopEnd   = $end->gt($endDate)     ? $endDate->copy()   : $end->copy();

            for ($date = $loopStart; $date->lte($loopEnd); $date->addDay()) {
                $key = $date->format('Y-m-d');
                $eventsByDate[$key][] = $event;
            }
        }

        $todaysEvents   = KalenderAkademik::published()->today()->with('semester')->get();
        $upcomingEvents = KalenderAkademik::published()->upcoming(30)->with('semester')->get();
        
        // History events (past events)
        $todayStr = now()->toDateString();
        $historyEvents = KalenderAkademik::published()
            ->with('semester')
            ->whereDate('tanggal_mulai', '<', $todayStr)
            ->where(function ($q) use ($todayStr) {
                $q->whereNotNull('tanggal_selesai')
                  ->whereDate('tanggal_selesai', '<', $todayStr);
            })
            ->orderByDesc('tanggal_mulai')
            ->orderByDesc('waktu_mulai')
            ->take(10)
            ->get();

        // Alias untuk view
        $events = $monthEvents;

        return view('admin.kalender-akademik.index', compact(
            'kalender',
            'semesters',
            'jenisKegiatanOptions',
            'selectedSemesterId',
            'selectedMonth',
            'selectedYear',
            'eventsByDate',
            'todaysEvents',
            'upcomingEvents',
            'historyEvents',
            'events'
        ));
    }

    public function create()
    {
        $semesters = Semester::orderBy('tahun_ajaran', 'desc')
            ->orderBy('jenis', 'desc')
            ->get();

        $jenisKegiatanOptions = KalenderAkademik::getJenisKegiatanOptions();
        $warnaPresets         = KalenderAkademik::getWarnaPresets();

        return view('admin.kalender-akademik.create', compact(
            'semesters',
            'jenisKegiatanOptions',
            'warnaPresets'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'semester_id'    => ['required', 'exists:semesters,id'],
            'judul'          => ['required', 'string', 'max:255'],
            'deskripsi'      => ['nullable', 'string'],
            'catatan'        => ['nullable', 'string'],
            'tanggal_mulai'  => ['required', 'date'],
            'tanggal_selesai' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
            'jenis_kegiatan' => ['required', Rule::in(array_keys(KalenderAkademik::getJenisKegiatanOptions()))],
            'warna'          => ['required', 'string', 'size:7', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'is_published'   => ['boolean'],
            'is_all_day'     => ['boolean'],
            'waktu_mulai'    => ['nullable', 'date_format:H:i'],
            'waktu_selesai'  => ['nullable', 'date_format:H:i', 'after:waktu_mulai'],
            'lokasi'         => ['nullable', 'string', 'max:255'],
        ]);

        $validated['is_published'] = $request->boolean('is_published');
        $validated['is_all_day']   = $request->boolean('is_all_day');
        $validated['created_by']   = auth()->id();

        // Jika all_day, kosongkan waktu
        if ($validated['is_all_day']) {
            $validated['waktu_mulai']  = null;
            $validated['waktu_selesai'] = null;
        }

        $kalender = KalenderAkademik::create($validated);

        KalenderAktivitasLog::log('created', $kalender, [], $validated, "Membuat agenda kalender akademik: {$kalender->judul}");

        return redirect()->route('admin.kalender-akademik.index')
            ->with('success', 'Agenda kalender akademik berhasil dibuat.');
    }

    public function show(KalenderAkademik $kalenderAkademik)
    {
        $kalenderAkademik->load([
            'semester',
            'creator',
            'updater',
            'aktivitasLogs' => fn ($q) => $q->latest('occurred_at')->limit(20),
        ]);

        return view('admin.kalender-akademik.show', compact('kalenderAkademik'));
    }

    public function edit(KalenderAkademik $kalenderAkademik)
    {
        $semesters = Semester::orderBy('tahun_ajaran', 'desc')
            ->orderBy('jenis', 'desc')
            ->get();

        $jenisKegiatanOptions = KalenderAkademik::getJenisKegiatanOptions();
        $warnaPresets         = KalenderAkademik::getWarnaPresets();

        return view('admin.kalender-akademik.edit', compact(
            'kalenderAkademik',
            'semesters',
            'jenisKegiatanOptions',
            'warnaPresets'
        ));
    }

    public function update(Request $request, KalenderAkademik $kalenderAkademik)
    {
        $validated = $request->validate([
            'semester_id'    => ['required', 'exists:semesters,id'],
            'judul'          => ['required', 'string', 'max:255'],
            'deskripsi'      => ['nullable', 'string'],
            'catatan'        => ['nullable', 'string'],
            'tanggal_mulai'  => ['required', 'date'],
            'tanggal_selesai' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
            'jenis_kegiatan' => ['required', Rule::in(array_keys(KalenderAkademik::getJenisKegiatanOptions()))],
            'warna'          => ['required', 'string', 'size:7', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'is_published'   => ['boolean'],
            'is_all_day'     => ['boolean'],
            'waktu_mulai'    => ['nullable', 'date_format:H:i'],
            'waktu_selesai'  => ['nullable', 'date_format:H:i', 'after:waktu_mulai'],
            'lokasi'         => ['nullable', 'string', 'max:255'],
        ]);

        $validated['is_published'] = $request->boolean('is_published');
        $validated['is_all_day']   = $request->boolean('is_all_day');
        $validated['updated_by']   = auth()->id();

        if ($validated['is_all_day']) {
            $validated['waktu_mulai']  = null;
            $validated['waktu_selesai'] = null;
        }

        $oldValues = $kalenderAkademik->getOriginal();
        $kalenderAkademik->update($validated);

        KalenderAktivitasLog::log('updated', $kalenderAkademik, $oldValues, $validated, "Mengubah agenda kalender akademik: {$kalenderAkademik->judul}");

        return redirect()->route('admin.kalender-akademik.index')
            ->with('success', 'Agenda kalender akademik berhasil diperbarui.');
    }

    public function destroy(KalenderAkademik $kalenderAkademik)
    {
        $judul     = $kalenderAkademik->judul;
        $oldValues = $kalenderAkademik->getOriginal();

        $kalenderAkademik->delete();

        KalenderAktivitasLog::log('deleted', null, $oldValues, [], "Menghapus agenda kalender akademik: {$judul}");

        return redirect()->route('admin.kalender-akademik.index')
            ->with('success', 'Agenda kalender akademik berhasil dihapus.');
    }

    public function activities(KalenderAkademik $kalenderAkademik)
    {
        $logs = $kalenderAkademik->aktivitasLogs()
            ->with('user')
            ->latest('occurred_at')
            ->paginate(20);

        return view('admin.kalender-akademik.activities', compact('kalenderAkademik', 'logs'));
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action'  => ['required', 'in:publish,unpublish,delete'],
            'ids'     => ['required', 'array'],
            'ids.*'   => ['exists:kalender_akademik,id'],
        ]);

        $ids    = $request->ids;
        $action = $request->action;

        $kalenderItems = KalenderAkademik::whereIn('id', $ids)->get();

        foreach ($kalenderItems as $item) {
            $oldValues = $item->getOriginal();

            match ($action) {
                'publish'   => $item->update(['is_published' => true]),
                'unpublish' => $item->update(['is_published' => false]),
                'delete'    => $item->delete(),
            };

            KalenderAktivitasLog::log(
                $action === 'delete' ? 'deleted' : 'updated',
                $action === 'delete' ? null : $item,
                $oldValues,
                $action === 'delete' ? [] : $item->getOriginal(),
                "Bulk {$action} agenda: {$item->judul}"
            );
        }

        $messages = [
            'publish'   => 'Agenda berhasil dipublikasikan.',
            'unpublish' => 'Agenda berhasil di-unpublish.',
            'delete'    => 'Agenda berhasil dihapus.',
        ];

        return back()->with('success', $messages[$action]);
    }
}