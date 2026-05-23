<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    // Usage: RoleMiddleware::class . ':ADMINISTRATOR'
    // Or pipe-separated for multiple allowed roles: RoleMiddleware::class . ':ADMINISTRATOR|INSPECTOR'
    // ADMINISTRATOR always passes regardless of which role is required.
    public function handle(Request $request, Closure $next, string ...$roles): mixed
    {
        $user = $request->user();

        if (! $user) {
            abort(403, 'Unauthorized');
        }

        // ADMINISTRATOR has implicit access to all role-protected routes
        if ($user->roles === 'ADMINISTRATOR') {
            return $next($request);
        }

        // Flatten pipe-separated roles passed as a single argument (e.g. ':ADMIN|INSPECTOR')
        $allowed = [];
        foreach ($roles as $role) {
            foreach (explode('|', $role) as $r) {
                $allowed[] = trim($r);
            }
        }

        if (! in_array($user->roles, $allowed, true)) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}