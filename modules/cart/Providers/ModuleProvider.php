<?php

namespace Modules\cart\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\cart\Repository\CartRepositoryInterface;
use Modules\cart\Repository\EloquentCartRepository;


class ModuleProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(CartRepositoryInterface::class,EloquentCartRepository::class);
        require_once base_path('modules/cart/helpers.php');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

    }
}
