<?php

namespace Modules\discount\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\discount\Repository\DiscountRepositoryInterface;
use Modules\discount\Repository\EloquentDiscountRepository;


class ModuleProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(DiscountRepositoryInterface::class,EloquentDiscountRepository::class);
        $this->loadMigrationsFrom(base_path('modules/discount/database/migrations'));

        add_panel_menu([
            'label'=>'تخفیف ها',
            'icon'=>'mdi-sale',
            'access'=>'discount',
            'name'=>'discount',
            'child'=>[
                ['url'=>url('admin/discount'),'label'=>'مدیریت کد های تخفیف','access'=>'discount'],
                ['url'=>url('admin/discount/create'),'label'=>'افزودن کد تخفیف','access'=>'discount'],
            ]
        ]);
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
