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
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! $request->user() || ! in_array($request->user()->role, $roles)) {
            if ($request->user()) {
                // Redirect to appropriate dashboard based on role
                return redirect()->route($request->user()->role.'.dashboard');
            }

            return redirect()->route('login');
        }

        return $next($request);
    }
}
