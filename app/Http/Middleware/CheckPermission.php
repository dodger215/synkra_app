<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $module, string $action): Response
    {
        $user = Auth::user();

        if (!$user) {
            abort(403, 'Unauthorized access.');
        }

        if ($user->role === \App\Enums\UserRole::OWNER || $user->role === \App\Enums\UserRole::ADMIN) {
            return $next($request);
        }

        $permissions = $user->permissions ?? [];

        if (isset($permissions[$module][$action]) && $permissions[$module][$action] === true) {
            return $next($request);
        }

        abort(403, 'You do not have permission to perform this action.');
    }
}
