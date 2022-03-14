<?php

namespace Modules\orders\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Lib\Jdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Modules\orders\Models\Order;
use Modules\products\Models\Product;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function send_discount(Request $request)
    {
        if ($request->has('request-type') && $request->get('request-type') == 'axios') {
            $discount = Discount::where(['name' => $request->discount])->first();
            if (!Session::has('discount')) {
                if ($discount) {
                    Session::put('discount', $discount->value);
                    Session::put('discount_id', $discount->id);
                    $message = 'کد تخفیف با موفقيت اعمال شد.';
                    $status = 'success';
                } else {
                    $message = 'کد تخفیف وارد شده اشتباه می باشد!';
                    $status = 'error';
                }
            } else {
                $message = 'کد تخفیف قبلا اعمال شده است!';
                $status = 'error';
            }
            return [
                'message' => $message,
                'status' => $status
            ];
        }
    }

    public function add_order()
    {
        $order = new Order();
        $product_id = '';
        foreach (Session::get('cart') as $key => $value) {
            $product_id .= $key . ',';
        }
        $Jdf = new Jdf();
        $order->order_id = substr(time(), 2, 5) . rand(1000, 9999);
        $order->product_id = $product_id;
        $order->total_price = Session::get('total_price');
        $order->price = Session::get('cart_price');
        $order->discount_id = Session::get('discount_id');
        $order->user_id = Auth::user()->id;
        $order->time = time();
        $order->date = $Jdf->jdate('Y-n-d');
        if ($order->save()) {
            Session::forget('cart');
            Session::forget('total_price');
            Session::forget('cart_price');
            Session::forget('discount_id');
            Session::forget('discount');
        }
        return redirect('/profile/order/' . $order->order_id);
    }

    public function order()
    {
        $user = Auth::user();
        $orders = Order::where(['user_id' => $user->id])->orderBy('id', 'DESC')->paginate(20);
        return CView('orders::profile.order', ['order' => $orders, 'user' => $user]);
    }

    public function show_order($order_id)
    {
        /*      $RefId=$request->get('RefId');
                $ResCode=$request->get('ResCode');
                $SaleOrderId=$request->get('SaleOrderId');
                $SaleReferenceId=$request->get('SaleReferenceId');

                require_once '../app/lib/nusoap.php';
                $mellat_bank=new Mellat_Bank();

                if($ResCode==0)
                {
                    if($mellat_bank->Verify($SaleOrderId,$SaleReferenceId))
                    {*/
        $order_data = array();
        $Order = Order::where('order_id', $order_id)->firstOrFail();
        $Order->status = 'ok';
//              $Order->saleReferenceId=$SaleReferenceId;
        $Order->update();
        $id = array();
        $n = array();
        $product_id = explode(',', $Order->product_id);
        if (is_array($product_id)) {
            foreach ($product_id as $key => $value) {
                if (!empty($value)) {
                    $id[$key] = $value;
                }
            }
        }
        $time = time() + 7 * 24 * 60 * 60;
        $i = 0;
        foreach ($id as $key2 => $value2) {
            $product = Product::find($value2);
            if ($product) {
                $order_data[$i]['title'] = $product->title;
                $order_data[$i]['price'] = $product->price;
                $line = '';
                $links = $product->links;
                $l = explode(',', $links);
                if (is_array($l)) {
                    $j = 1;
                    foreach ($l as $k => $v) {
                        $name = md5($v . time() . '/@#%%' . $value2);
                        DB::table('download')->insert([
                            'name' => $name,
                            'file' => $v,
                            'time' => $time
                        ]);
                        $url = url('download') . '?file=' . $name;
                        $line .= 'part ' . $j . ' : ' . $url . "\r\n";
                        $j++;
                    }

                    $text_name = md5('$%#E' . $product->id . time() . '#$%') . '.txt';
                    $t_name = md5('$%E' . $product->id . time() . '#$%');
                    DB::table('download')->insert([
                        'name' => $t_name,
                        'file' => 'text_file/' . $text_name,
                        'time' => $time,
                        'product_id' => $product->id,
                        'order_id' => $Order->id
                    ]);

                    $order_data[$i]['link'] = $t_name;

                    $fopen = fopen('text_file/' . $text_name, 'w');
                    fputs($fopen, $line);
                    fclose($fopen);
                }
            }
            $i++;
        }
        return View('orders::order', ['order' => $Order, 'order_data' => $order_data]);
        /*            }
                    else
                    {
                        return View('site.error_payment');
                    }
                }
                else
                {
                    return View('site.error_payment');
                }*/

    }

    public function download(Request $request)
    {
        $file = $request->get('file');
        $row = DB::table('download')->where('name', $file)->first();
        if ($row) {
            $time = time();
            if ($row->time - $time > 0) {
                $name = 'file';
                $file_name = explode('.', $row->file);
                $name .= '.' . $file_name[sizeof($file_name) - 1];
                if ($file_name[sizeof($file_name) - 1] == 'txt') {
                    echo file_get_contents($row->file);
                } else {
                    if (file_exists($row->file)) {
                        return response()->download($row->file, $name);
                    }
                }
            } else {
                echo 'مدت زمان اعتبار لینک دانلود به پایان رسیده';
            }
        } else {
            echo 'درخواست نامعتبر';
        }
    }

}
