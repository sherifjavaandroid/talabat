<?php

// app/Http/Middleware/RestrictRolesMiddleware.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserActiveCheck
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

        if (Auth::check()) {
            //check if user is active ore deleted
            if (Auth::user()->is_active == 0) {
                //just throw a 404 error
                abort(401, __('Your account is not active. Please contact the administrator.'));
            }
        }


        return $next($request);
    }
}
