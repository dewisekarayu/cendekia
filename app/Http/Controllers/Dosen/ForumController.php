<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\ForumDiskusi;
use App\Models\KomentarDiskusi;
use App\Models\KelasPerkuliahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class ForumController extends Controller
{
    /**
     * Tampilkan forum untuk satu kelas spesifik yang diampu dosen.
     */
    public function index(Request $request, $id)
    {
        $user = $request->user();

        // Step 1: Verify user is authenticated and is a Dosen
        if (!$user) {
            Log::warning('DosenForumController::index - Unauthenticated access', [
                'path' => $request->path(),
            ]);
            abort(403, 'Anda harus login terlebih dahulu.');
        }

        if (!$user->isDosen()) {
            Log::warning('DosenForumController::index - Non-Dosen access attempted', [
                'user_id' => $user->id,
                'user_roles' => $user->getRoleNames()->toArray(),
                'path' => $request->path(),
            ]);
            abort(403, 'Anda tidak memiliki akses ke forum. Hanya Dosen yang dapat mengakses halaman ini.');
        }

        // Step 2: Verify class exists and dosen actually teaches it
        $kelas = KelasPerkuliahan::findOrFail($id);

        $mengajarKelasIni = $user->kelasDiampu()->where('kelas_perkuliahan.id', $id)->exists();

        if (!$mengajarKelasIni) {
            Log::warning('DosenForumController::index - Dosen tried to access class not taught', [
                'dosen_id' => $user->id,
                'kelas_id' => $id,
            ]);
            abort(403, 'Anda tidak mengajar kelas ini.');
        }

        $kelas->forumDiskusiDefault();

        // Step 3: Fetch forums from this specific class only
        $forumList = ForumDiskusi::where('kelas_perkuliahan_id', $id)
            ->with([
                'kelasPerkuliahan.mataKuliah',
                'kelasPerkuliahan.dosen',
                'komentar' => fn($q) => $q->with('user')->orderBy('created_at'),
                'pembuat',
            ])
            ->latest('updated_at')
            ->get();

        Log::debug('DosenForumController::index - Forums retrieved', [
            'dosen_id' => $user->id,
            'kelas_id' => $id,
            'forums_count' => $forumList->count(),
        ]);

        // Step 4: Select active forum based on query parameter
        $activeForumId = $request->query('forum', $forumList->first()?->id);
        $activeForum = $forumList->firstWhere('id', $activeForumId) ?? $forumList->first();

        // Step 5: Verify active forum belongs to this class (double-check)
        if ($activeForum) {
            $canView = Gate::inspect('view', $activeForum)->allowed();
            if (!$canView || $activeForum->kelas_perkuliahan_id != $id) {
                Log::warning('DosenForumController::index - Active forum access denied', [
                    'dosen_id' => $user->id,
                    'forum_id' => $activeForum->id,
                    'forum_kelas_id' => $activeForum->kelas_perkuliahan_id,
                ]);
                abort(403, 'Forum yang dipilih tidak dapat diakses.');
            }
        }

        return view('dosen.forums', compact('forumList', 'activeForum', 'kelas'));
    }

    /**
     * Polling pesan baru secara real-time.
     */
    public function getMessages(Request $request, $id, ForumDiskusi $forum)
    {
        $user = $request->user();

        if (!$user || !$user->isDosen()) {
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
     * Kirim pesan ke forum kelas yang diampu dosen.
     */
    public function kirimPesan(Request $request, $id, ForumDiskusi $forum)
    {
        $user = $request->user();

        if (!$user) {
            Log::warning('DosenForumController::kirimPesan - Unauthenticated access', [
                'path' => $request->path(),
                'forum_id' => $forum->id,
            ]);
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Anda harus login terlebih dahulu.'], 401);
            }
            abort(403, 'Anda harus login terlebih dahulu.');
        }

        if (!$user->isDosen()) {
            Log::warning('DosenForumController::kirimPesan - Non-Dosen access attempted', [
                'user_id' => $user->id,
                'user_roles' => $user->getRoleNames()->toArray(),
                'forum_id' => $forum->id,
            ]);
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Anda harus login sebagai Dosen untuk mengirim pesan forum.'], 403);
            }
            abort(403, 'Anda harus login sebagai Dosen untuk mengirim pesan forum.');
        }

        // Verify forum belongs to this class
        if ($forum->kelas_perkuliahan_id != $id) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Forum tidak ada di kelas ini.'], 404);
            }
            abort(403, 'Forum tidak ada di kelas ini.');
        }

        $policyInspection = Gate::inspect('sendMessage', $forum);
        if (!$policyInspection->allowed()) {
            Log::warning('DosenForumController::kirimPesan - Authorization denied by policy', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'forum_id' => $forum->id,
                'forum_kelas_id' => $forum->kelas_perkuliahan_id,
                'policy_reason' => $policyInspection->message(),
            ]);
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Anda tidak dapat mengirim pesan di forum ini. Pastikan Anda mengajar kelas ini.'], 403);
            }
            abort(403, 'Anda tidak dapat mengirim pesan di forum ini. Pastikan Anda mengajar kelas ini.');
        }

        $validated = $request->validate([
            'isi' => ['required', 'string', 'max:2000'],
        ], [
            'isi.required' => 'Pesan tidak boleh kosong.',
            'isi.max' => 'Pesan maksimal 2000 karakter.',
        ]);

        try {
            $komentar = KomentarDiskusi::create([
                'forum_diskusi_id' => $forum->id,
                'user_id' => $user->id,
                'isi' => $validated['isi'],
            ]);

            $forum->touch();

            Log::info('DosenForumController::kirimPesan - Message sent successfully', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'forum_id' => $forum->id,
                'komentar_id' => $komentar->id,
                'message_length' => strlen($validated['isi']),
            ]);

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
                        'is_dosen' => true,
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
                ->route('dosen.kelas-forum', ['id' => $id, 'forum' => $forum->id])
                ->with('success', 'Pesan berhasil dikirim!')
                ->withFragment('bottom');

        } catch (\Exception $e) {
            Log::error('DosenForumController::kirimPesan - Error creating message', [
                'user_id' => $user->id,
                'forum_id' => $forum->id,
                'error' => $e->getMessage(),
                'exception' => get_class($e),
            ]);

            if ($request->expectsJson()) {
                return response()->json(['error' => 'Gagal mengirim pesan. Silakan coba lagi.'], 500);
            }

            return redirect()
                ->route('dosen.kelas-forum', ['id' => $id, 'forum' => $forum->id])
                ->with('error', 'Gagal mengirim pesan. Silakan coba lagi.')
                ->withFragment('bottom');
        }
    }
}