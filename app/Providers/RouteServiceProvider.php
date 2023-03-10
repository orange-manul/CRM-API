<?php

declare(strict_types=1);

namespace App\Providers;

use RuntimeException;
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
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     *
     */
    public function boot():void
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            foreach ($this->centralDomains() as $domain){
                Route::prefix('api')
                    ->domain($domain)
                    ->middleware('api')
                    ->namespace($this->namespace)
                    ->group(base_path('routes/api.php'));

                Route::middleware('web')
                    ->domain($domain)
                    ->namespace($this->namespace)
                    ->group(base_path('routes/web.php'));
            }
        });
    }

    /**
     * @return array
     */
    protected function centralDomains(): array
    {
        $domains = config('tenancy.central_domains');

        if (! is_array($domains)){
            throw new RuntimeException(
                message: "Tenanct Central Domains should be an array",
            );
        }
        return (array) $domains;
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
