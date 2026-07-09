<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
            Log::warning('RoleMiddleware: Unauthenticated access attempt', [
                'path' => $request->path(),
                'required_roles' => $roles,
            ]);
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        // Normalize roles to lowercase for consistent comparison with database
        $requiredRoles = array_map('strtolower', $roles);
        
        // Check if user has any of the required roles
        $hasRole = false;
        $failureReason = null;
        
        foreach ($requiredRoles as $role) {
            // Strategy 1: Primary check using hasRole() from Spatie Permission
            if ($user->hasRole($role)) {
                $hasRole = true;
                Log::debug('RoleMiddleware: Role check passed (Spatie)', [
                    'user_id' => $user->id,
                    'role' => $role,
                    'method' => 'hasRole',
                ]);
                break;
            }
            
            // Strategy 2: Fallback to helper methods for robustness
            // This handles edge cases where Spatie Permission might fail
            $helperCheck = match($role) {
                'admin' => $user->isAdmin(),
                'dosen' => $user->isDosen(),
                'mahasiswa' => $user->isMahasiswa(),
                default => false,
            };
            
            if ($helperCheck) {
                $hasRole = true;
                Log::debug('RoleMiddleware: Role check passed (helper method)', [
                    'user_id' => $user->id,
                    'role' => $role,
                    'method' => 'helper_' . $role,
                ]);
                break;
            }
            
            $failureReason = "User lacks role: {$role}";
        }

        if (!$hasRole) {
            Log::warning('RoleMiddleware: Authorization denied', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'user_email' => $user->email,
                'required_roles' => $requiredRoles,
                'current_roles' => $user->getRoleNames()->toArray(),
                'failure_reason' => $failureReason,
                'path' => $request->path(),
                'method' => $request->method(),
            ]);
            
            return response()->json([
                'message' => 'User does not have the required role(s).',
                'required' => $requiredRoles,
                'current' => $user->getRoleNames()->toArray(),
                'user_id' => $user->id,
            ], 403);
        }

        return $next($request);
    }
}