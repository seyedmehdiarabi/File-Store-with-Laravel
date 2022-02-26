<?php


namespace Modules\cart\Http\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Session;
use Modules\products\Models\Product;


class CartController extends Controller
{
    public function add_cart(Request $request){
        if (request()->get('request-type')=='axios'){
            $product_id=$request['product_id'];
            $product=Product::findOrFail($product_id);
            $array=array();
            if (Session::has('cart')){
                $array=Session::get('cart');
                if (array_key_exists($product_id,$array)){
                    //$array[$product_id]++;
                    return ['message'=>'اين محصول در سبد خريد شما وجود دارد.'];
                }else{
                    $array[$product_id]=1;
                }
            }else{
                $array[$product_id]=1;
            }
            Session::put('cart',$array);
            //return redirect('/cart');
        }
    }
    public function cart(){
        return CView('cart::cart');
    }
    public function del_cart($product_id,Request $request){
        if ($request['request']=="axios"){
            if (Session::has('cart')){
                $array=Session::get('cart');
                if (array_key_exists($product_id,$array)){
                    unset($array[$product_id]);
                    if (empty($array)){
                        Session::forget('cart');
                    }else{
                        Session::put('cart',$array);
                    }
                }
            }
        }
    }
    public function get_count_cart(){
        $cart=Session::get('cart');
        $count_cart=count(!empty($cart) ? $cart :[]);
        $count_cart=$count_cart>0 ?$count_cart :'0';
        return replace_number($count_cart);
    }
}
