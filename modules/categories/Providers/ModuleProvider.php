<?php

namespace Modules\categories\Providers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\ServiceProvider;
use Modules\categories\Models\Category;
use Modules\categories\Repository\CategoryRepositoryInterface;
use Modules\categories\Repository\EloquentCategoryRepository;
use Request;
use Config;
class ModuleProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(CategoryRepositoryInterface::class,EloquentCategoryRepository::class);
        $this->loadMigrationsFrom(base_path('modules/categories/database/migrations'));
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        add_panel_child_menu([
            'label'=>'مدیریت دسته ها',
            'access'=>'category',
            'parent_menu'=>'products',
            'url'=>url('admin/category')
        ]);
        Builder::macro('category', function () {
            return $this->getModel()->belongsTo(Category::class, 'cat_id', 'id')
                ->withDefault(['id' => 0, 'name' => '']);
        });
    }
}
