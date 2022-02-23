<?php

namespace Modules\users\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\users\Http\Middleware\User;
use Modules\users\Repository\AdditionalInfoRepositoryInterface;
use Modules\users\Repository\EloquentAdditionalInfoRepository;
use Modules\users\Repository\EloquentUserRoleRepository;
use Modules\users\Repository\EloquentUsersRepository;
use Modules\users\Repository\UserRoleRepositoryInterface;
use Modules\users\Repository\UsersRepositoryInterface;

class ModuleProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(UserRoleRepositoryInterface::class,EloquentUserRoleRepository::class);
        $this->app->bind(UsersRepositoryInterface::class,EloquentUsersRepository::class);
        $this->app->bind(AdditionalInfoRepositoryInterface::class,EloquentAdditionalInfoRepository::class);
        $this->loadMigrationsFrom(base_path('modules/users/database/migrations'));
        require_once base_path('modules/users/helpers.php');

        $this->app['router']->aliasMiddleware('user', User::class);

        add_panel_menu([
            'label'=>'کاربران',
            'icon'=>'mdi-account-group-outline',
            'access'=>'users',
            'child'=>[
                ['url'=>url('users'),'label'=>'مدیریت کاربران','access'=>'users'],
                ['url'=>url('userRole'),'label'=>'نقش های کاربری','access'=>'users','accessValue'=>'user_access'],
                ['url'=>url('user/sms/channel'),'label'=>'تنظیمات ارسال پیامک','access'=>'users','accessValue'=>'user_access'],
            ]
        ],15);

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
