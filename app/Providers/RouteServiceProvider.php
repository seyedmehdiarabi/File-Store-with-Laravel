<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Config;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    public const HOME = '/';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {

        parent::boot();


        /*        $this->configureRateLimiting();

                $this->routes(function () {
                    Route::prefix('api')
                        ->middleware('api')
                        ->group(base_path('routes/api.php'));

                    Route::middleware('web')
                        ->group(base_path('routes/web.php'));
                });*/
    }


    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        //
    }

    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));

        Route::middleware('web')
            ->namespace($this->namespace.'\\Admin')
            ->group(base_path('routes/admin.php'));

        $modules=Config::get('app.modules');
        foreach ($modules as $module){
            $file=base_path('modules/'.$module.'/routes/web.php');
            if(file_exists($file)){
                $namespace="Modules\\".$module.'\Http\Controllers';
                Route::middleware('web')
                    ->namespace($namespace)
                    ->group($file);
            }
        }
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware(['api','apiResponse'])
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));

        $modules=Config::get('app.modules');

        foreach ($modules as $module){
            $file=base_path('modules/'.$module.'/routes/api.php');
            if(file_exists($file)){
                $namespace="Modules\\".$module.'\Http\Controllers';

                Route::prefix('api')
                    ->middleware(['api','apiResponse'])
                    ->namespace($namespace)
                    ->group($file);
            }
        }
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
