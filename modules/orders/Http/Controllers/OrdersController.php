<?php


namespace Modules\orders\Http\Controllers;


use App\Http\Controllers\Admin\CustomController;
use Illuminate\Http\Request;
use Modules\address\Repository\AddressRepositoryInterface;
use Modules\orders\Models\Order;
use Modules\orders\Repository\OrdersRepositoryInterface;
use Modules\orders\Repository\SubmissionRepositoryInterface;
use Modules\products\Models\Product;
use Modules\users\Repository\AdditionalInfoRepositoryInterface;

class OrdersController extends CustomController
{
    protected $title='سفارشات';

    protected $route_params='orders';

    protected $repository;


    public function index(Request $request){
        $order=Order::getData($request->all());
        return CView('orders::panel.index',['order'=>$order]);
    }
    public function destroy($id){
        $order=Order::findOrFail($id);
        if(empty($comment->deleted_at)){
            $message="سفارش انتخاب شده به سطل زباله انتقال یافت";
            $order->delete();
        }
        else{
            $message="سفارش انتخاب شده حذف شد";
            $order->forceDelete();
        }
        return [
            'message'=>$message,
            'redirect_url'=>url('admin/orders'),
        ];
    }
    public function show($id){
        $order=Order::findOrFail($id);
        $order->order_read=1;
        $order->update();
        $products_id=explode(',',$order->product_id);
        $product=[];
        foreach ($products_id as $key=>$value){
            if (!empty($value)){
                $product[$key]=Product::find($value);
            }
        }
        return CView('orders::panel.show',['order'=>$order,'product'=>$product]);
    }
    }
