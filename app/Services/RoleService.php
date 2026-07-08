<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Log;

class RoleService
{
    /**
     * Validate user role for route access
     * Supports case-insensitive role matching
     */
    public function validateRoleAccess(User $user, string $requiredRole): bool
    {
        if (!$user || !$user->id) {
            return false;
        }

        // Normalize role to lowercase
        $requiredRole = strtolower(trim($requiredRole));

        // Admin can access everything
        if ($user->isAdmin()) {
            return true;
        }

        // Check specific role with multiple strategies
        return $this->checkUserHasRole($user, $requiredRole);
    }

    /**
     * Check if user has a specific role using multiple strategies
     */
    private function checkUserHasRole(User $user, string $role): bool
    {
        // Strategy 1: Use hasRole() from HasRoles trait
        if ($user->hasRole($role)) {
            return true;
        }

        // Strategy 2: Use helper methods as fallback
        return match($role) {
            'admin' => $user->isAdmin(),
            'dosen' => $user->isDosen(),
            'mahasiswa' => $user->isMahasiswa(),
            default => false,
        };
    }

    /**
     * Get allowed roles for user (for UI display)
     */
    public function getAllowedRoles(User $user): array
    {
        if (!$user || !$user->id) {
            return [];
        }

        $roles = [];
        
        if ($user->isAdmin()) {
            $roles[] = 'admin';
        }
        
        if ($user->isDosen()) {
            $roles[] = 'dosen';
        }
        
        if ($user->isMahasiswa()) {
            $roles[] = 'mahasiswa';
        }

        return $roles;
    }

    /**
     * Redirect user to appropriate dashboard
     */
    public function redirectToDashboard(User $user)
    {
        return redirect($user->getDashboardRoute());
    }

    /**
     * Check forum access with detailed validation
     * Supports both Dosen and Mahasiswa with proper role checking
     */
    public function checkForumAccess(User $user, $forumId): array
    {
        if (!$user) {
            return ['allowed' => false, 'reason' => 'User not authenticated'];
        }

        // Get user's roles
        $userRoles = $user->getRoleNames()->toArray();

        if (empty($userRoles)) {
            return ['allowed' => false, 'reason' => 'User has no valid role'];
        }

        // Admin can access all forums
        if ($user->isAdmin()) {
            return ['allowed' => true, 'reason' => 'Admin access'];
        }

        // Get the forum
        $forum = \App\Models\ForumDiskusi::find($forumId);
        if (!$forum) {
            return ['allowed' => false, 'reason' => 'Forum not found'];
        }

        // Dosen check - must teach the class
        if ($user->isDosen()) {
            $hasAccess = $user->kelasDiampu()
                ->where('id', $forum->kelas_perkuliahan_id)
                ->exists();
            
            if ($hasAccess) {
                return ['allowed' => true, 'reason' => 'Dosen teaches this class'];
            }
            return ['allowed' => false, 'reason' => 'Dosen does not teach this class'];
        }

        // Mahasiswa check - must be enrolled in the class
        if ($user->isMahasiswa()) {
            $hasAccess = $user->kelasDiikuti()
                ->where('id', $forum->kelas_perkuliahan_id)
                ->exists();
            
            if ($hasAccess) {
                return ['allowed' => true, 'reason' => 'Mahasiswa enrolled in this class'];
            }
            return ['allowed' => false, 'reason' => 'Mahasiswa not enrolled in this class'];
        }

        return ['allowed' => false, 'reason' => 'User role not recognized'];
    }

    /**
     * Get user's primary role (for dashboard redirects)
     */
    public function getPrimaryRole(User $user): string
    {
        return $user->getPrimaryRole();
    }

    /**
     * Get all user roles
     */
    public function getUserRoles(User $user): array
    {
        return $user->getRoleNames()->toArray();
    }
}

        return ['allowed' => true, 'reason' => 'Access granted'];
    }

    /**
     * Validate user can send message in forum
     */
    public function canSendMessage(User $user, $forum): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isDosen()) {
            // Dosen must be teaching the class
            return $user->kelasDiampu()->where('id', $forum->kelas_perkuliahan_id)->exists();
        }

        if ($user->isMahasiswa()) {
            // Mahasiswa must be enrolled in the class
            return $user->kelasDiikuti()->where('id', $forum->kelas_perkuliahan_id)->exists();
        }

        return false;
    }
}