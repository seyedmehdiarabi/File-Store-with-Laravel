<?php


namespace Modules\users\Http\Controllers;


use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function index()
    {
       // config()->set('view.search_components','add');
        return CView('users::admin.index');
    }
}
