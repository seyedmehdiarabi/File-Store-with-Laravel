<?php

namespace Modules\setting\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\setting\Repository\EloquentSettingRepository;
use Modules\setting\Repository\SettingRepositoryInterface;

class ModuleProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(SettingRepositoryInterface::class,EloquentSettingRepository::class);
        $this->loadMigrationsFrom(base_path('modules/setting/database/migrations'));

        add_panel_menu([
            'label'=>'تنظیمات',
            'icon'=>'mdi-cogs',
            'access'=>'setting',
            'name'=>'setting'
        ],100);

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        add_panel_child_menu([
            'label'=>'فروشگاه',
            'access'=>'setting',
            'parent_menu'=>'setting',
            'url'=>url('setting/shop')
        ]);

    }
}
