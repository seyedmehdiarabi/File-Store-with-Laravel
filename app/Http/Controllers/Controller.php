<?php

namespace App\Http\Controllers;

use App\Lib\Mobile_Detect;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    protected $view = '';

    public function __construct()
    {
        $detect = new Mobile_Detect();
        if ($detect->isMobile() || $detect->isTablet()) {
            $this->view = 'mobile.';
        }
        $request = request();
        if ($request->route()) {
            $routeName = $request->route()->getName();
            $routeName = str_replace('.', '_', $routeName);
            if ($routeName) {
                run_action($routeName . '_action', [$request]);
            }
        }
        $value = str_replace('.', '', $this->view);
        define('view_type', $value);

        run_action('run_controller',[$request]);
    }
}
