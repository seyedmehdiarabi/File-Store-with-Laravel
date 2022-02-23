<?php

namespace Modules\visitStatistics\Providers;

use Illuminate\Support\ServiceProvider;

class ModuleProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //$this->app->bind(SliderRepositoryInterface::class,EloquentSliderRepository::class);
        $this->loadMigrationsFrom(base_path('modules/visitStatistics/database/migrations'));

   /*     add_panel_menu([
            'label'=>'اسلایدر ها',
            'icon'=>'mdi-play-box-outline',
            'access'=>'sliders',
            'child'=>[
                ['url'=>url('sliders'),'label'=>'مدیریت اسلایدر ها','access'=>'sliders'],
                ['url'=>url('sliders/create'),'label'=>'افزودن اسلایدر','access'=>'sliders'],
            ]
        ],2);*/
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
