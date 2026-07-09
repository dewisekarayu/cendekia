<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ForumDiskusi;
use Illuminate\Support\Facades\Log;

class ForumPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Any authenticated user can view forums (admin, dosen, mahasiswa)
        return $user !== null;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ForumDiskusi $forum): bool
    {
        if ($user->isAdmin()) {
            Log::debug('ForumPolicy::view - Allowed for Admin', [
                'user_id' => $user->id,
                'forum_id' => $forum->id,
                'reason' => 'admin_user',
            ]);
            return true;
        }

        if ($user->isDosen()) {
            // Dosen must be teaching the class
            $hasAccess = $user->kelasDiampu()
                ->where('kelas_perkuliahan.id', $forum->kelas_perkuliahan_id)
                ->exists();
            
            if ($hasAccess) {
                Log::debug('ForumPolicy::view - Allowed for Dosen', [
                    'user_id' => $user->id,
                    'forum_id' => $forum->id,
                    'reason' => 'dosen_teaches_class',
                ]);
            } else {
                Log::warning('ForumPolicy::view - Denied for Dosen', [
                    'user_id' => $user->id,
                    'forum_id' => $forum->id,
                    'forum_kelas_id' => $forum->kelas_perkuliahan_id,
                    'reason' => 'dosen_not_teaching_class',
                    'classes_taught' => $user->kelasDiampu()->pluck('id')->toArray(),
                ]);
            }
            return $hasAccess;
        }

        if ($user->isMahasiswa()) {
            // Mahasiswa must be enrolled in the class
            $hasAccess = $user->kelasDiikuti()
                ->where('kelas_perkuliahan.id', $forum->kelas_perkuliahan_id)
                ->exists();
            
            if ($hasAccess) {
                Log::debug('ForumPolicy::view - Allowed for Mahasiswa', [
                    'user_id' => $user->id,
                    'forum_id' => $forum->id,
                    'reason' => 'mahasiswa_enrolled_in_class',
                ]);
            } else {
                Log::warning('ForumPolicy::view - Denied for Mahasiswa', [
                    'user_id' => $user->id,
                    'forum_id' => $forum->id,
                    'forum_kelas_id' => $forum->kelas_perkuliahan_id,
                    'reason' => 'mahasiswa_not_enrolled_in_class',
                    'classes_enrolled' => $user->kelasDiikuti()->pluck('id')->toArray(),
                ]);
            }
            return $hasAccess;
        }

        Log::warning('ForumPolicy::view - Denied (no valid role)', [
            'user_id' => $user->id,
            'forum_id' => $forum->id,
            'user_roles' => $user->getRoleNames()->toArray(),
        ]);
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only admin can create forums (they are seeded, not created via UI)
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ForumDiskusi $forum): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ForumDiskusi $forum): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can send messages in forum.
     */
    public function sendMessage(User $user, ForumDiskusi $forum): bool
    {
        if ($user->isAdmin()) {
            Log::debug('ForumPolicy::sendMessage - Allowed for Admin', [
                'user_id' => $user->id,
                'forum_id' => $forum->id,
                'reason' => 'admin_user',
            ]);
            return true;
        }

        if ($user->isDosen()) {
            // Dosen must be teaching the class
            $hasAccess = $user->kelasDiampu()
                ->where('kelas_perkuliahan.id', $forum->kelas_perkuliahan_id)
                ->exists();
            
            if ($hasAccess) {
                Log::debug('ForumPolicy::sendMessage - Allowed for Dosen', [
                    'user_id' => $user->id,
                    'forum_id' => $forum->id,
                    'reason' => 'dosen_teaches_class',
                ]);
            } else {
                Log::warning('ForumPolicy::sendMessage - Denied for Dosen', [
                    'user_id' => $user->id,
                    'forum_id' => $forum->id,
                    'forum_kelas_id' => $forum->kelas_perkuliahan_id,
                    'reason' => 'dosen_not_teaching_class',
                    'classes_taught' => $user->kelasDiampu()->pluck('id')->toArray(),
                ]);
            }
            return $hasAccess;
        }

        if ($user->isMahasiswa()) {
            // Mahasiswa must be enrolled in the class
            $hasAccess = $user->kelasDiikuti()
                ->where('kelas_perkuliahan.id', $forum->kelas_perkuliahan_id)
                ->exists();
            
            if ($hasAccess) {
                Log::debug('ForumPolicy::sendMessage - Allowed for Mahasiswa', [
                    'user_id' => $user->id,
                    'forum_id' => $forum->id,
                    'reason' => 'mahasiswa_enrolled_in_class',
                ]);
            } else {
                Log::warning('ForumPolicy::sendMessage - Denied for Mahasiswa', [
                    'user_id' => $user->id,
                    'forum_id' => $forum->id,
                    'forum_kelas_id' => $forum->kelas_perkuliahan_id,
                    'reason' => 'mahasiswa_not_enrolled_in_class',
                    'classes_enrolled' => $user->kelasDiikuti()->pluck('id')->toArray(),
                ]);
            }
            return $hasAccess;
        }

        Log::warning('ForumPolicy::sendMessage - Denied (no valid role)', [
            'user_id' => $user->id,
            'forum_id' => $forum->id,
            'user_roles' => $user->getRoleNames()->toArray(),
        ]);
        return false;
    }

    /**
     * Determine whether the user can delete messages in forum.
     */
    public function deleteMessage(User $user, ForumDiskusi $forum): bool
    {
        // Only admin and dosen teaching the class can delete messages
        if ($user->isAdmin()) {
            Log::debug('ForumPolicy::deleteMessage - Allowed for Admin', [
                'user_id' => $user->id,
                'forum_id' => $forum->id,
                'reason' => 'admin_user',
            ]);
            return true;
        }

        if ($user->isDosen()) {
            $hasAccess = $user->kelasDiampu()
                ->where('kelas_perkuliahan.id', $forum->kelas_perkuliahan_id)
                ->exists();
            
            if ($hasAccess) {
                Log::debug('ForumPolicy::deleteMessage - Allowed for Dosen', [
                    'user_id' => $user->id,
                    'forum_id' => $forum->id,
                    'reason' => 'dosen_teaches_class',
                ]);
            } else {
                Log::warning('ForumPolicy::deleteMessage - Denied for Dosen', [
                    'user_id' => $user->id,
                    'forum_id' => $forum->id,
                    'forum_kelas_id' => $forum->kelas_perkuliahan_id,
                    'reason' => 'dosen_not_teaching_class',
                    'classes_taught' => $user->kelasDiampu()->pluck('id')->toArray(),
                ]);
            }
            return $hasAccess;
        }

        Log::warning('ForumPolicy::deleteMessage - Denied (Mahasiswa cannot delete)', [
            'user_id' => $user->id,
            'forum_id' => $forum->id,
            'reason' => 'mahasiswa_no_delete_permission',
        ]);
        return false;
    }
}