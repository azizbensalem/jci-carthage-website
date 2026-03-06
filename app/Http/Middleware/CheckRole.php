<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string  $role
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, string $role)
    {
        if (!auth()->check()) {
            return redirect('/login');
        }

        $user = auth()->user();

        if ($role === 'admin' && !$user->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        if ($role === 'vice-president' && !$user->isVicePresident() && !$user->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}
