<?php


namespace Modules\shop\Http\Controllers;


use App\Http\Controllers\Controller;

class SiteController extends Controller
{
    public function index()
    {
        return view(  $this->view.'index');
    }

}
