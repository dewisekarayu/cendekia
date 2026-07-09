<?php

namespace App\Http\Controllers\Mahasiswa;

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
     * Tampilkan semua forum dari kelas yang diikuti mahasiswa.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        // Step 1: Verify user is authenticated and is a Mahasiswa
        if (!$user) {
            Log::warning('MahasiswaForumController::index - Unauthenticated access', [
                'path' => $request->path(),
            ]);
            abort(403, 'Anda harus login terlebih dahulu.');
        }

        if (!$user->isMahasiswa()) {
            Log::warning('MahasiswaForumController::index - Non-Mahasiswa access attempted', [
                'user_id' => $user->id,
                'user_roles' => $user->getRoleNames()->toArray(),
                'path' => $request->path(),
            ]);
            abort(403, 'Anda tidak memiliki akses ke forum. Hanya Mahasiswa yang dapat mengakses halaman ini.');
        }

        // Step 2: Get class IDs that this Mahasiswa is enrolled in
        $kelasIds = $user->kelasDiikuti()->pluck('kelas_perkuliahan.id');

        if ($kelasIds->isEmpty()) {
            Log::info('MahasiswaForumController::index - Mahasiswa not enrolled in any classes', [
                'mahasiswa_id' => $user->id,
                'mahasiswa_name' => $user->name,
            ]);
            // Return empty list - this is valid
        }

        // Step 3: Fetch all forums from classes this Mahasiswa is enrolled in
        $forumList = ForumDiskusi::whereIn('kelas_perkuliahan_id', $kelasIds)
            ->with([
                'kelasPerkuliahan.mataKuliah',
                'kelasPerkuliahan.dosen',
                'komentar' => fn($q) => $q->with('user')->orderBy('created_at'),
                'pembuat',
            ])
            ->latest('updated_at')
            ->get();

        Log::debug('MahasiswaForumController::index - Forums retrieved', [
            'mahasiswa_id' => $user->id,
            'classes_enrolled' => $kelasIds->toArray(),
            'forums_count' => $forumList->count(),
        ]);

        // Step 4: Select active forum based on query parameter
        $activeForumId = $request->query('forum', $forumList->first()?->id);
        $activeForum = $forumList->firstWhere('id', $activeForumId) ?? $forumList->first();

        // Step 5: Verify active forum belongs to user's classes (double-check)
        if ($activeForum) {
            $canView = Gate::inspect('view', $activeForum)->allowed();
            if (!$canView) {
                Log::warning('MahasiswaForumController::index - Active forum access denied', [
                    'mahasiswa_id' => $user->id,
                    'forum_id' => $activeForum->id,
                    'forum_kelas_id' => $activeForum->kelas_perkuliahan_id,
                    'reason' => Gate::inspect('view', $activeForum)->message(),
                ]);
                abort(403, 'Forum yang dipilih tidak dapat diakses.');
            }
        }

        // Get class list for view compatibility
        $kelasList = KelasPerkuliahan::with(['mataKuliah', 'dosen'])
            ->whereIn('id', $kelasIds)
            ->get();

        return view('mahasiswa.forums', compact('forumList', 'activeForum', 'kelasList'));
    }

    /**
     * Kirim pesan (komentar) ke forum kelas.
     */
    public function kirimPesan(Request $request, ForumDiskusi $forum)
    {
        $user = $request->user();

        // Step 1: Verify user is authenticated and is a Mahasiswa
        if (!$user) {
            Log::warning('MahasiswaForumController::kirimPesan - Unauthenticated access', [
                'path' => $request->path(),
                'forum_id' => $forum->id,
            ]);
            abort(403, 'Anda harus login terlebih dahulu.');
        }

        if (!$user->isMahasiswa()) {
            Log::warning('MahasiswaForumController::kirimPesan - Non-Mahasiswa access attempted', [
                'user_id' => $user->id,
                'user_roles' => $user->getRoleNames()->toArray(),
                'forum_id' => $forum->id,
            ]);
            abort(403, 'Anda harus login sebagai Mahasiswa untuk mengirim pesan forum.');
        }

        // Step 2: Check authorization using policy with detailed logging
        $policyInspection = Gate::inspect('sendMessage', $forum);
        if (!$policyInspection->allowed()) {
            Log::warning('MahasiswaForumController::kirimPesan - Authorization denied by policy', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'forum_id' => $forum->id,
                'forum_kelas_id' => $forum->kelas_perkuliahan_id,
                'policy_reason' => $policyInspection->message(),
                'classes_enrolled' => $user->kelasDiikuti()->pluck('id')->toArray(),
            ]);
            abort(403, 'Anda tidak dapat mengirim pesan di forum ini. Pastikan Anda terdaftar di kelas ini.');
        }

        // Step 3: Validate message content
        $validated = $request->validate([
            'isi' => ['required', 'string', 'max:2000'],
        ], [
            'isi.required' => 'Pesan tidak boleh kosong.',
            'isi.max' => 'Pesan maksimal 2000 karakter.',
        ]);

        try {
            // Step 4: Create the forum message
            $komentar = KomentarDiskusi::create([
                'forum_diskusi_id' => $forum->id,
                'user_id' => $user->id,
                'isi' => $validated['isi'],
            ]);

            // Step 5: Update forum's updated_at timestamp
            $forum->touch();

            Log::info('MahasiswaForumController::kirimPesan - Message sent successfully', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'forum_id' => $forum->id,
                'komentar_id' => $komentar->id,
                'message_length' => strlen($validated['isi']),
            ]);

            return redirect()
                ->route('mahasiswa.forums', ['forum' => $forum->id])
                ->with('success', 'Pesan berhasil dikirim!')
                ->withFragment('bottom');

        } catch (\Exception $e) {
            Log::error('MahasiswaForumController::kirimPesan - Error creating message', [
                'user_id' => $user->id,
                'forum_id' => $forum->id,
                'error' => $e->getMessage(),
                'exception' => get_class($e),
            ]);
            
            return redirect()
                ->route('mahasiswa.forums', ['forum' => $forum->id])
                ->with('error', 'Gagal mengirim pesan. Silakan coba lagi.')
                ->withFragment('bottom');
        }
    }
}
