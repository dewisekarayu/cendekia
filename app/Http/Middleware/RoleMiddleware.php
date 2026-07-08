<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        // Normalize roles to lowercase for consistent comparison
        $requiredRoles = array_map('strtolower', $roles);
        
        // Check if user has any of the required roles
        $hasRole = false;
        
        foreach ($requiredRoles as $role) {
            // Primary check using hasRole() with normalized case
            if ($user->hasRole($role)) {
                $hasRole = true;
                break;
            }
            
            // Fallback to helper methods for robustness
            if ($role === 'admin' && $user->isAdmin()) {
                $hasRole = true;
                break;
            }
            if ($role === 'dosen' && $user->isDosen()) {
                $hasRole = true;
                break;
            }
            if ($role === 'mahasiswa' && $user->isMahasiswa()) {
                $hasRole = true;
                break;
            }
        }

        if (!$hasRole) {
            return response()->json([
                'message' => 'User does not have the required role(s).',
                'required' => $requiredRoles,
                'current' => $user->getRoleNames()->toArray(),
            ], 403);
        }

        return $next($request);
    }
}