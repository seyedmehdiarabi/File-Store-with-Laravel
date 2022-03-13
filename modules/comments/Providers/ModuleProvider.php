<?php

namespace Modules\comments\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\comments\Repository\CommentsRepositoryInterface;
use Modules\comments\Repository\EloquentCommentsRepository;
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
        $this->app->bind(CommentsRepositoryInterface::class,EloquentCommentsRepository::class);
        $this->loadMigrationsFrom(base_path('modules/comments/database/migrations'));
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        add_panel_menu([
            'name'=>'comments',
            'label'=>'مدیریت کامنت‌ها',
            'icon'=>'mdi-comment-edit-outline',
            'access'=>'comments',
            'child'=>[
                ['url'=>url('admin/comments'),'label'=>'کامنت‌ها','access'=>'comments'],
            ]
        ],10);
    }
}
