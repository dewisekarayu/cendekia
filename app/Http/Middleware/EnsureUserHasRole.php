<?php

namespace App\Http\Middleware;

use App\Services\RoleService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
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
            abort(401, 'Unauthenticated.');
        }

        // Check if user has any of the required roles
        $hasValidRole = false;
        foreach ($roles as $role) {
            $roleService = app(RoleService::class);
            if ($roleService->validateRoleAccess($user, $role)) {
                $hasValidRole = true;
                break;
            }
        }

        if (!$hasValidRole) {
            abort(403, 'User does not have the required role(s).');
        }

        return $next($request);
    }
}