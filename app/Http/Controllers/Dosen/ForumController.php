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
     * Tampilkan semua forum dari kelas yang diampu dosen.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        // Verify user is authenticated and is a Dosen
        if (!$user || !$user->isDosen()) {
            abort(403, 'Anda tidak memiliki akses ke forum.');
        }

        // Get class IDs that this Dosen teaches
        $kelasIds = $user->kelasDiampu()->pluck('id');

        if ($kelasIds->isEmpty()) {
            Log::info('Dosen has no classes assigned', ['dosen_id' => $user->id]);
        }

        // Fetch all forums from classes this Dosen teaches
        $forumList = ForumDiskusi::whereIn('kelas_perkuliahan_id', $kelasIds)
            ->with([
                'kelasPerkuliahan.mataKuliah',
                'kelasPerkuliahan.dosen',
                'komentar' => fn($q) => $q->with('user')->orderBy('created_at'),
                'pembuat',
            ])
            ->latest('updated_at')
            ->get();

        // Select active forum based on query parameter
        $activeForumId = $request->query('forum', $forumList->first()?->id);
        $activeForum = $forumList->firstWhere('id', $activeForumId) ?? $forumList->first();

        // Verify active forum belongs to user's classes
        if ($activeForum && !Gate::inspect('view', $activeForum)->allowed()) {
            abort(403, 'Forum tidak dapat diakses.');
        }

        return view('dosen.forums', compact('forumList', 'activeForum'));
    }

    /**
     * Kirim pesan ke forum kelas yang diampu dosen.
     */
    public function kirimPesan(Request $request, ForumDiskusi $forum)
    {
        $user = $request->user();

        // Step 1: Verify user is authenticated and is a Dosen
        if (!$user || !$user->isDosen()) {
            abort(403, 'Anda harus login sebagai Dosen.');
        }

        // Step 2: Check authorization using policy
        if (!Gate::inspect('sendMessage', $forum)->allowed()) {
            Log::warning('Dosen unauthorized to send forum message', [
                'user_id' => $user->id,
                'forum_id' => $forum->id,
                'reason' => Gate::inspect('sendMessage', $forum)->message(),
            ]);
            abort(403, 'Anda tidak dapat mengirim pesan di forum ini.');
        }

        // Step 3: Validate message content
        $validated = $request->validate([
            'isi' => ['required', 'string', 'max:2000'],
        ], [
            'isi.required' => 'Pesan tidak boleh kosong.',
            'isi.max' => 'Pesan maksimal 2000 karakter.',
        ]);

        // Step 4: Create the forum message
        $komentar = KomentarDiskusi::create([
            'forum_diskusi_id' => $forum->id,
            'user_id' => $user->id,
            'isi' => $validated['isi'],
        ]);

        // Step 5: Update forum's updated_at timestamp
        $forum->touch();

        Log::info('Forum message sent successfully', [
            'user_id' => $user->id,
            'forum_id' => $forum->id,
            'komentar_id' => $komentar->id,
        ]);

        return redirect()
            ->route('dosen.forums', ['forum' => $forum->id])
            ->withFragment('bottom');
    }
}
