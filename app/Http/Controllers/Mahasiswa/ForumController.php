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
     * Kirim pesan (komentar) ke forum kelas.
     */
    public function kirimPesan(Request $request, $id, ForumDiskusi $forum)
    {
        $user = $request->user();

        // Step 1: Verify user is authenticated and is a Mahasiswa
        if (!$user) {
            abort(403, 'Anda harus login terlebih dahulu.');
        }

        if (!$user->isMahasiswa()) {
            abort(403, 'Anda harus login sebagai Mahasiswa untuk mengirim pesan forum.');
        }

        // Step 2: Verify forum belongs to this class
        if ($forum->kelas_perkuliahan_id != $id) {
            abort(403, 'Forum tidak ada di kelas ini.');
        }

        // Step 3: Check authorization
        $policyInspection = Gate::inspect('sendMessage', $forum);
        if (!$policyInspection->allowed()) {
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

            return redirect()
                ->route('mahasiswa.kelas-forum', ['id' => $id, 'forum' => $forum->id])
                ->with('success', 'Pesan berhasil dikirim!')
                ->withFragment('bottom');

        } catch (\Exception $e) {
            return redirect()
                ->route('mahasiswa.kelas-forum', ['id' => $id, 'forum' => $forum->id])
                ->with('error', 'Gagal mengirim pesan. Silakan coba lagi.')
                ->withFragment('bottom');
        }
    }
}
