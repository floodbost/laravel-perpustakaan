<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {
        $user = $request->user();

        if (!$user || (!is_null($user->role) && !in_array(strtolower($user->role->name), explode(':', $role)))) {
            abort(403, 'Insufficient credentials');
        }



        return $next($request);
    }
}
