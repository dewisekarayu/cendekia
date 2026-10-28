<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\ForumDiskusi;
use App\Models\KomentarDiskusi;
use App\Models\KelasPerkuliahan;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class ForumController extends Controller
{
    /**
     * Tampilkan semua forum dari kelas spesifik.
     */
    public function index(Request $request, $id)
    {
        $user = $request->user();
        
        // Step 1: Verify user is authenticated and is a Mahasiswa
        if (!$user) {
            abort(403, 'Anda harus login terlebih dahulu.');
        }

        if (!$user->isMahasiswa()) {
            abort(403, 'Anda tidak memiliki akses ke forum. Hanya Mahasiswa yang dapat mengakses halaman ini.');
        }

        // Step 2: Verify mahasiswa is enrolled in this class
        $kelas = KelasPerkuliahan::findOrFail($id);
        $isEnrolled = $user->kelasDiikuti()->where('kelas_perkuliahan.id', $id)->exists();
        
        if (!$isEnrolled) {
            abort(403, 'Anda tidak terdaftar di kelas ini.');
        }

        $kelas->forumDiskusiDefault();

        // Step 3: Fetch forums from this specific class
        $forumList = ForumDiskusi::where('kelas_perkuliahan_id', $id)
            ->with([
                'kelasPerkuliahan.mataKuliah',
                'kelasPerkuliahan.dosen',
                'komentar' => fn($q) => $q->with('user')->orderBy('created_at'),
                'pembuat',
            ])
            ->latest('updated_at')
            ->get();

        // Step 4: Select active forum based on query parameter
        $activeForumId = $request->query('forum', $forumList->first()?->id);
        $activeForum = $forumList->firstWhere('id', $activeForumId) ?? $forumList->first();

        // Step 5: Verify active forum belongs to this class (double-check)
        if ($activeForum && $activeForum->kelas_perkuliahan_id != $id) {
            abort(403, 'Forum yang dipilih tidak ada di kelas ini.');
        }

        $kelasList = [$kelas];

        return view('mahasiswa.forums', compact('forumList', 'activeForum', 'kelasList', 'kelas'));
    }

    /**
     * Polling pesan baru secara real-time.
     */
    public function getMessages(Request $request, $id, ForumDiskusi $forum)
    {
        $user = $request->user();

        if (!$user || !$user->isMahasiswa()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($forum->kelas_perkuliahan_id != $id) {
            return response()->json(['error' => 'Forum tidak ditemukan di kelas ini.'], 404);
        }

        if (!Gate::allows('view', $forum)) {
            return response()->json(['error' => 'Akses ditolak.'], 403);
        }

        $afterId = (int) $request->query('after_id', 0);

        $messages = KomentarDiskusi::where('forum_diskusi_id', $forum->id)
            ->when($afterId > 0, fn($q) => $q->where('id', '>', $afterId))
            ->with('user')
            ->orderBy('id', 'asc')
            ->get();

        $palette = ['#720c16','#0C4A6E','#1E3A8A','#164E63','#1C3144','#0F172A','#1F2937'];

        $formatted = $messages->map(function ($msg) use ($user, $palette) {
            $sName = $msg->user?->name ?? 'Anonim';
            $sFirst = explode(' ', $sName)[0];
            $sClr = $palette[abs(crc32($sName)) % count($palette)];
            $createdAt = $msg->created_at;

            return [
                'id' => $msg->id,
                'isi' => $msg->isi,
                'mine' => $msg->user_id === $user->id,
                'is_dosen' => (bool) $msg->user?->hasRole('dosen'),
                'user_id' => $msg->user_id,
                'user_name' => $sName,
                'user_first_name' => $sFirst,
                'avatar_color' => $sClr,
                'time_str' => $createdAt->format('H:i'),
                'date_str' => $createdAt->format('Y-m-d'),
                'date_label' => $createdAt->isToday() ? 'Hari Ini' : ($createdAt->isYesterday() ? 'Kemarin' : $createdAt->translatedFormat('l, d F Y')),
                'created_at_ts' => $createdAt->timestamp,
            ];
        });

        return response()->json([
            'messages' => $formatted,
            'total' => KomentarDiskusi::where('forum_diskusi_id', $forum->id)->count(),
        ]);
    }

    /**
     * Kirim pesan (komentar) ke forum kelas.
     */
    public function kirimPesan(Request $request, $id, ForumDiskusi $forum)
    {
        $user = $request->user();

        // Step 1: Verify user is authenticated and is a Mahasiswa
        if (!$user) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Anda harus login terlebih dahulu.'], 401);
            }
            abort(403, 'Anda harus login terlebih dahulu.');
        }

        if (!$user->isMahasiswa()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Anda harus login sebagai Mahasiswa untuk mengirim pesan forum.'], 403);
            }
            abort(403, 'Anda harus login sebagai Mahasiswa untuk mengirim pesan forum.');
        }

        // Step 2: Verify forum belongs to this class
        if ($forum->kelas_perkuliahan_id != $id) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Forum tidak ada di kelas ini.'], 404);
            }
            abort(403, 'Forum tidak ada di kelas ini.');
        }

        // Step 3: Check authorization
        $policyInspection = Gate::inspect('sendMessage', $forum);
        if (!$policyInspection->allowed()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Anda tidak dapat mengirim pesan di forum ini.'], 403);
            }
            abort(403, 'Anda tidak dapat mengirim pesan di forum ini.');
        }

        // Step 4: Validate message content
        $validated = $request->validate([
            'isi' => ['required', 'string', 'max:2000'],
        ], [
            'isi.required' => 'Pesan tidak boleh kosong.',
            'isi.max' => 'Pesan maksimal 2000 karakter.',
        ]);

        try {
            // Step 5: Create the forum message
            $komentar = KomentarDiskusi::create([
                'forum_diskusi_id' => $forum->id,
                'user_id' => $user->id,
                'isi' => $validated['isi'],
            ]);

            // Step 6: Update forum's updated_at timestamp
            $forum->touch();

            // Step 7: Send notification to dosen and other participants
            $kelas = $forum->kelasPerkuliahan;
            $dosen = $kelas->dosen;
            
            // Notify dosen about new message in forum
            if ($dosen && $dosen->id !== $user->id) {
                NotificationService::notifyPesanBaru($forum, $dosen, $user);
            }

            if ($request->expectsJson()) {
                $palette = ['#720c16','#0C4A6E','#1E3A8A','#164E63','#1C3144','#0F172A','#1F2937'];
                $sName = $user->name;
                $sFirst = explode(' ', $sName)[0];
                $sClr = $palette[abs(crc32($sName)) % count($palette)];
                $createdAt = $komentar->created_at;

                return response()->json([
                    'success' => true,
                    'message' => [
                        'id' => $komentar->id,
                        'isi' => $komentar->isi,
                        'mine' => true,
                        'is_dosen' => false,
                        'user_id' => $user->id,
                        'user_name' => $sName,
                        'user_first_name' => $sFirst,
                        'avatar_color' => $sClr,
                        'time_str' => $createdAt->format('H:i'),
                        'date_str' => $createdAt->format('Y-m-d'),
                        'date_label' => $createdAt->isToday() ? 'Hari Ini' : ($createdAt->isYesterday() ? 'Kemarin' : $createdAt->translatedFormat('l, d F Y')),
                        'created_at_ts' => $createdAt->timestamp,
                    ],
                ]);
            }

            return redirect()
                ->route('mahasiswa.kelas-forum', ['id' => $id, 'forum' => $forum->id])
                ->with('success', 'Pesan berhasil dikirim!')
                ->withFragment('bottom');

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Gagal mengirim pesan. Silakan coba lagi.'], 500);
            }

            return redirect()
                ->route('mahasiswa.kelas-forum', ['id' => $id, 'forum' => $forum->id])
                ->with('error', 'Gagal mengirim pesan. Silakan coba lagi.')
                ->withFragment('bottom');
        }
    }
}
