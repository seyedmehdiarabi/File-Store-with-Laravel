<?php


namespace Modules\shop\Http\Controllers;


use App\Http\Controllers\Controller;
use Modules\products\Models\Product;

class SiteController extends Controller
{
    public function index()
    {
        return Cview(  $this->view.'index');
    }

    public function ShowProduct($title){
        $product=Product::where(['slug'=>$title,'status'=>1])->firstOrFail();
        $product->increment('view');
        return Cview('front-theme::views.show_product',['product'=>$product]);
    }

}
