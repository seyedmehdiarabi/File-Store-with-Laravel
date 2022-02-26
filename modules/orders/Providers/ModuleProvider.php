<?php

namespace Modules\orders\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\orders\Models\Orders;
use Modules\orders\Repository\EloquentOrdersRepository;
use Modules\orders\Repository\OrdersRepositoryInterface;

class ModuleProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(OrdersRepositoryInterface::class,EloquentOrdersRepository::class);
        $this->loadMigrationsFrom(base_path('modules/orders/database/migrations'));
        require_once base_path('modules/orders/helpers.php');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $child=[
            ['url'=>url('admin/orders'),'label'=>'مدیریت سفارشات','access'=>'orders'],
        ];

        add_panel_menu([
            'name'=>'orders',
            'label'=>'سفارشات',
            'icon'=>'mdi-cart',
            'access'=>'access',
            'child'=>$child
        ],5);

    }
}
