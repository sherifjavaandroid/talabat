<?php

// app/Http/Middleware/RestrictRolesMiddleware.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RestrictRolesMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {

        if (!Auth::check()) {
            // If the user is not logged in, redirect to login page
            return redirect()->route('login');
        }

        if (Auth::user()->hasAnyRole($roles)) {
            //throw a 403 error
            abort(403);
        }

        return $next($request);
    }
}
