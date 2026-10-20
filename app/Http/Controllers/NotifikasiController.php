<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use App\Models\Pengumuman;
use App\Models\KelasMahasiswa;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    /**
     * Tampilkan semua notifikasi pengguna dengan pagination dan filter tipe ala Google Classroom.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $isMahasiswa = $user->hasRole('mahasiswa');
        $selectedDate = null;
        $selectedTipe = $request->input('tipe', 'semua');

        $query = $user->notifikasi()->with(['kelasPerkuliahan.mataKuliah']);

        // Filter berdasarkan tipe
        if (!empty($selectedTipe) && $selectedTipe !== 'semua') {
            if ($selectedTipe === 'pengumuman') {
                $query->whereIn('tipe', ['pengumuman', 'informasi']);
            } else {
                $query->where('tipe', $selectedTipe);
            }
        }

        // Filter berdasarkan tanggal
        if ($request->filled('tanggal')) {
            $validated = $request->validate([
                'tanggal' => ['nullable', 'date_format:Y-m-d'],
            ]);

            if (!empty($validated['tanggal'])) {
                $selectedDate = $validated['tanggal'];
                $query->whereDate('created_at', $selectedDate);
            }
        }

        $notifikasis = $query
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        // Hitung unread count per tipe untuk filter badge
        $counts = [
            'semua' => $user->notifikasi()->whereNull('dibaca_pada')->count(),
            'tugas' => $user->notifikasi()->where('tipe', 'tugas')->whereNull('dibaca_pada')->count(),
            'materi' => $user->notifikasi()->where('tipe', 'materi')->whereNull('dibaca_pada')->count(),
            'presensi' => $user->notifikasi()->where('tipe', 'presensi')->whereNull('dibaca_pada')->count(),
            'nilai' => $user->notifikasi()->where('tipe', 'nilai')->whereNull('dibaca_pada')->count(),
            'pengumuman' => $user->notifikasi()->whereIn('tipe', ['pengumuman', 'informasi'])->whereNull('dibaca_pada')->count(),
        ];

        $pengumuman = null;
        if ($isMahasiswa) {
            $kelasIds = KelasMahasiswa::where('mahasiswa_id', $user->id)
                ->pluck('kelas_perkuliahan_id');

            $pengumuman = Pengumuman::with(['pembuat', 'kelasPerkuliahan'])
                ->where(function ($announcementQuery) use ($kelasIds) {
                    $announcementQuery->whereNull('kelas_perkuliahan_id')
                        ->orWhereIn('kelas_perkuliahan_id', $kelasIds);
                })
                ->latest()
                ->paginate(5, ['*'], 'pengumuman_page')
                ->withQueryString();
        }

        return view('notifikasi.index', compact(
            'notifikasis',
            'isMahasiswa',
            'selectedDate',
            'selectedTipe',
            'counts',
            'pengumuman'
        ));
    }

    /**
     * Tandai satu notifikasi sebagai dibaca dan redirect ke URL-nya secara aman.
     */
    public function baca(Notifikasi $notifikasi)
    {
        if ($notifikasi->user_id == auth()->id()) {
            if (is_null($notifikasi->dibaca_pada)) {
                $notifikasi->update(['dibaca_pada' => now()]);
            }
        }

        $rawUrl = trim($notifikasi->url ?? '');

        // Tentukan fallback default berdasarkan role pengguna
        $fallbackUrl = route('notifikasi.index');
        if (auth()->check()) {
            $user = auth()->user();
            if ($user->hasRole('admin')) {
                $fallbackUrl = route('admin.dashboard');
            } elseif ($user->hasRole('dosen')) {
                $fallbackUrl = route('dosen.dashboard');
            } elseif ($user->hasRole('mahasiswa')) {
                $fallbackUrl = route('mahasiswa.dashboard');
            }
        }

        // Cegah redirect ke root webserver atau url kosong/dummy
        if (empty($rawUrl) || $rawUrl === '#' || $rawUrl === '/' || $rawUrl === 'http://localhost' || $rawUrl === 'http://localhost/') {
            return redirect($fallbackUrl);
        }

        // Jika URL tersimpan dengan skema http/https
        if (str_starts_with($rawUrl, 'http://') || str_starts_with($rawUrl, 'https://')) {
            $parsed = parse_url($rawUrl);
            $path = $parsed['path'] ?? '';
            $query = isset($parsed['query']) ? '?' . $parsed['query'] : '';
            $fragment = isset($parsed['fragment']) ? '#' . $parsed['fragment'] : '';

            // Jika hanya root server "/", jangan biarkan redirect keluar aplikasi
            if (empty($path) || $path === '/') {
                return redirect($fallbackUrl);
            }

            // Arahkan ke path di dalam domain aktif saat ini
            return redirect(url($path . $query . $fragment));
        }

        return redirect(url($rawUrl));
    }

    /**
     * Tandai semua notifikasi pengguna saat ini sebagai dibaca.
     */
    public function bacaSemua()
    {
        auth()->user()->notifikasi()
            ->whereNull('dibaca_pada')
            ->update(['dibaca_pada' => now()]);

        return back()->with('success', 'Semua notifikasi telah ditandai sebagai dibaca.');
    }

    /**
     * Lightweight endpoint untuk polling status notifikasi lonceng secara berkala
     */
    public function poll(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['unread_count' => 0, 'notifications' => []]);
        }

        $unreadCount = $user->notifikasi()->whereNull('dibaca_pada')->count();
        $recentNotifs = $user->notifikasi()
            ->with(['kelasPerkuliahan.mataKuliah'])
            ->orderBy('created_at', 'desc')
            ->take(7)
            ->get()
            ->map(function ($n) {
                $meta = $n->getTypeMeta();
                $namaMk = $n->kelasPerkuliahan->mataKuliah->nama_mk ?? null;
                return [
                    'id' => $n->id,
                    'judul' => $n->judul,
                    'pesan' => $n->pesan,
                    'tipe' => $n->tipe,
                    'meta' => $meta,
                    'mata_kuliah' => $namaMk,
                    'is_unread' => is_null($n->dibaca_pada),
                    'read_url' => route('notifikasi.baca', $n->id),
                    'time_ago' => $n->created_at->diffForHumans(),
                    'time_full' => $n->created_at->format('d M Y, H:i'),
                ];
            });

        return response()->json([
            'unread_count' => $unreadCount,
            'notifications' => $recentNotifs,
        ]);
    }
}
