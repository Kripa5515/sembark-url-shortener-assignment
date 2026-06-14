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
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (! $user || (! in_array($user->role?->name, $roles) && ! $user->isSuperAdmin())) {
            abort(403, 'You do not have the required role to access this resource.');
        }

        return $next($request);
    }
}
