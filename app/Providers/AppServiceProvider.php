<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
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
        validator::extend('captcha',function (){
            $secretKey  = "6LeL3WsdAAAAANTba6hkJTggFxntPRd9jf0pZQXE";
            $statusMsg = '';
            if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response']))
            {
                $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secretKey.'&response='.$_POST['g-recaptcha-response']);
                $responseData = json_decode($verifyResponse);
                if($responseData->success)
                {
                    return true;
                } else
                {
                    return false;
                }
            }else{
                return false;
            }
        });
    }
}
