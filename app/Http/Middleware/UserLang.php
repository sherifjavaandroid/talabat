<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserLang
{
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle($request, Closure $next)
  {

    if ($request->header('lang')) {
      \App::setLocale($request->header('lang'));
    } elseif ($request->has('lan')) {
      if (Auth::user()) {
        Auth::user()->language = $request->input('lan');
        Auth::user()->save(); // this will do database UPDATE only when language was changed
      }
      \App::setLocale($request->input('lan'));
    } else if (Auth::user()) {
      \App::setLocale(Auth::user()->language);
    } else {
      $locale = session('lan', 'en');
      app()->setLocale($locale);
    }
    
    session(['lan' => app()->getLocale()]);
    return $next($request);
  }
}
