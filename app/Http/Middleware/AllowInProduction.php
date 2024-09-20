<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AllowInProduction
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the application is not in production
        if (!app()->environment('production')) {
            // Abort the request with a 403 Forbidden status
            abort(403, __('Access denied. This action is only allowed in production.'));
        }

        // Allow the request to proceed
        return $next($request);
    }
}
