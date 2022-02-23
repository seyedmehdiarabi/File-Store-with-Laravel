<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;

class CMSProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    protected $modules = [];

    public function register()
    {
        $path = base_path('modules');
        if (is_dir($path)) {
            $directory = scandir($path);
            $directory = array_diff($directory, array(".", ".."));
            foreach ($directory as $dir) {
                if (is_dir($path . '/' . $dir)) {
                    $this->modules[] = $dir;
                    $view_path = $path . '/' . $dir . '/resources/views';
                    View::addNamespace($dir, $view_path);
                }
            }
            $this->addProvider();
            $this->addModulesMainClass();
            Config::set('app.modules', $this->modules);
        }
        $this->add_default_admin_menu();
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
    }

    public function addProvider()
    {
        foreach ($this->modules as $module) {
            $dir = base_path('modules/' . $module . '/Providers');
            if (is_dir($dir)) {
                $files = scandir($dir);
                $files = array_diff($files, array(".", ".."));
                foreach ($files as $file) {
                    $class = "\Modules\\" . $module . '\\Providers\\' . str_replace('.php', '', $file);
                    if (class_exists($class)) {
                        $this->app->register($class);
                    }
                }
            }
        }
    }

    public function addModulesMainClass()
    {
        $modulesMainClass = [];
        foreach ($this->modules as $module) {
            $file_path = base_path('modules/' . $module . '/Module.php');
            if (file_exists($file_path)){
                $className = "Modules\\{$module}\\Module";
                if (class_exists($className)){
                    require_once ($file_path);
                    $modulesMainClass[$module] = new $className;
                }
            }
        }
        if (sizeof($modulesMainClass) > 0) {
            Config::set('app.modulesMainClass',$modulesMainClass);
        }
    }

    protected function add_default_admin_menu(){
        add_panel_menu([
            'label' => 'میزکار',
            'icon' => 'mdi-desktop-classic',
            'access' => 'public',
            'url' => url('/')
        ], 0);
        add_panel_menu([
            'name' => 'finance',
            'label' => 'امور مالی',
            'icon' => 'mdi-currency-usd',
            'access' => 'finance|wallet',
        ], 1);
        add_panel_menu([
            'label'=>'پیام ها',
            'icon'=>'mdi-android-messages',
            'access'=>'messages',
            'name' => 'messages'
        ],2);
    }
    protected function addThemeHelpers($theme){
        $file_path=base_path($theme.'/helpers.php');
        $file_path=str_replace('\\','/',$file_path);
        if (file_exists($file_path)){
            require_once $file_path;
        }
    }
}
