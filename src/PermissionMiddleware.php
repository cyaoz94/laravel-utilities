<?php

namespace Cyaoz94\LaravelUtilities;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Exceptions\UnauthorizedException;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $permission, $guard = null)
    {
        if ($guard == null) {
            // sanctum
            $user = Auth::user();
        } else {
            if (app('auth')->guard($guard)->guest()) {
                throw UnauthorizedException::notLoggedIn();
            }

            $user = app('auth')->guard($guard)->user();
        }

        $permissions = is_array($permission) ? $permission : explode('|', $permission);

        foreach ($permissions as $permission) {
            if (!(in_array($permission, $user->getAllPermissions()->pluck('name')->toArray()))) {
                throw UnauthorizedException::forPermissions($permissions);
            }
        }

        return $next($request);
    }
}
