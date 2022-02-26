<?php

namespace Modules\products\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\products\Repository\EloquentProductRepository;
use Modules\products\Repository\ProductRepositoryInterface;

class ModuleProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ProductRepositoryInterface::class,EloquentProductRepository::class);
        $this->loadMigrationsFrom(base_path('modules/products/database/migrations'));

        require_once base_path('modules/products/helpers.php');

        add_panel_menu([
            'name'=>'products',
            'label'=>'محصولات',
            'icon'=>'mdi-basket',
            'access'=>'category',
            'child'=>[
                ['url'=>url('admin/products'),'label'=>'مدیریت محصولات','access'=>'products'],
                ['url'=>url('admin/products/create'),'label'=>'افزودن محصول','access'=>'products','accessValue'=>'product_edit']
            ]
        ],0);

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
