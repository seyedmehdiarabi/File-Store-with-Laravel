<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        View::addNameSpace('backend-theme', base_path('themes/AdminPanel'));
        View::addNameSpace('front-theme', base_path('themes/theme1'));
        Config::set('cms.front-theme', 'themes/theme1');
        Config::set('cms.backend-theme', 'themes/AdminPanel');
        Config::set('cms.front-theme-name', 'theme1');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();
    }
}
