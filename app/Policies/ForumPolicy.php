<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ForumDiskusi;

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
            return true;
        }

        if ($user->isDosen()) {
            // Dosen must be teaching the class
            return $user->kelasDiampu()->where('kelas_perkuliahan.id', $forum->kelas_perkuliahan_id)->exists();
        }

        if ($user->isMahasiswa()) {
            // Mahasiswa must be enrolled in the class
            return $user->kelasDiikuti()->where('kelas_perkuliahan.id', $forum->kelas_perkuliahan_id)->exists();
        }

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
            return true;
        }

        if ($user->isDosen()) {
            // Dosen must be teaching the class
            return $user->kelasDiampu()->where('kelas_perkuliahan.id', $forum->kelas_perkuliahan_id)->exists();
        }

        if ($user->isMahasiswa()) {
            // Mahasiswa must be enrolled in the class
            return $user->kelasDiikuti()->where('kelas_perkuliahan.id', $forum->kelas_perkuliahan_id)->exists();
        }

        return false;
    }

    /**
     * Determine whether the user can delete messages in forum.
     */
    public function deleteMessage(User $user, ForumDiskusi $forum): bool
    {
        // Only admin and dosen teaching the class can delete messages
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isDosen()) {
            return $user->kelasDiampu()->where('kelas_perkuliahan.id', $forum->kelas_perkuliahan_id)->exists();
        }

        return false;
    }
}