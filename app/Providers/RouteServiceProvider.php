<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    // protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));
            //
            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/general_web.php'));
            //
            Route::middleware('web')
                ->prefix(env('BACKEND_ROUTE_PREFIX'))
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));

            // for welcome website
            if (!empty(env('BACKEND_ROUTE_PREFIX'))) {
                Route::namespace($this->namespace)
                    ->group(base_path('routes/welcome_web.php'));
            }
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
        //new rate limiter for otp requests
        RateLimiter::for('otp', function (Request $request) {
            //limit to 2 requests per 2hours to prevent spamming
            //by ip address, user agent and route
            return Limit::perHour(5, 2)
                ->by($request->ip() . '|' . $request->userAgent() . '|' . $request->route()->getName())
                ->response(function () {
                    logger(
                        "API OTP Limit passed",
                        [
                            "ip" => request()->ip(),
                            "user_agent" => request()->userAgent(),
                            "route" => request()->route()->getName(),
                        ]
                    );
                    return response()->json([
                        'message' => __('You have exceeded the OTP request limit. You can retry again after 2 hours'),
                    ], 429);
                });
        });
    }
}