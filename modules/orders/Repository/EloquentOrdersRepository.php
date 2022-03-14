<?php


namespace Modules\orders\Repository;


use App\Lib\Jdf;
use App\Product;
use App\Repositories\EloquentBaseRepository;
use Modules\colors\Models\Color;
use Modules\colors\Models\ProductColor;
use DB;
use Modules\orders\Models\OrderProducts;
use Modules\orders\Models\Orders;
use Modules\orders\Models\OrdersSubmission;
use Session;

class EloquentOrdersRepository extends EloquentBaseRepository implements OrdersRepositoryInterface
{
    protected $model = 'Modules\orders\Models\Order';


    public function find($id)
    {
        $order = Order::with('getUserInfo','getDiscount')->where(['id' => $id])->firstOrFail();
        if ($order->order_read == 'no' && \Auth::user()->role == 'admin') {
            $order->order_read = 'ok';
            $order->update();
        }
        return $order;
    }

    public function create($request)
    {

    }

    public function trashCount()
    {
        return Order::onlyTrashed()->count();
    }

    public function getList($request)
    {
        return Order::getData($request->all());
    }

    public function update($id, $request)
    {
        // TODO: Implement update() method.
    }

    public function add_order($user_id, $send_order_data)
    {
        $jdf = new Jdf();
        $time = time();
        $order_code = substr($time, 1, 5) . $user_id . substr($time, 5, 10);
        $date = $jdf->tr_num($jdf->jdate('Y-n-j'));
        $total_price = $send_order_data['total_price'][1];

        DB::beginTransaction();
        try {
            $send_type = defined('send_type') ? send_type : Session::get('order_send_type');
            $price = $send_order_data['final_price'][1][$send_type];
            $orderData = [
                'user_id' => $user_id,
                'address_id' => address_id,
                'order_id' => $order_code,
                'order_read' => 'no',
                'pay_status' => 'awaiting_payment',
                'date' => $date,
                'send_type' => $send_type,
                'total_price' => $total_price,
                'price' => $price,
            ];
            $orderData = CompleteData('saving_order', $orderData);
            $order = new Orders($orderData);
            $order->save();
            $this->add_order_submissions($send_order_data, $user_id, $send_type, $order->id, $jdf);
            DB::commit();

            return [
                'status' => 'ok',
                'order_id' => $order->id,
                'price' => $order->price
            ];

        } catch (\Exception $exception) {
            DB::rollBack();
            return [
                'status' => 'error',
            ];
        }
    }


    public function userLastOrder($where)
    {
        return Orders::where($where)->orderBy('id', 'DESC')->limit(10)->get();
    }

    public function userOrder($where)
    {
        $relation = ['submissions.products.product'];
        $relation = CompleteData('order_detail_relation', $relation);
        $order = Orders::with($relation)->where($where)->firstOrFail();
        return $order;
    }

    public function globalOrderData($where)
    {
        $relation = [];
        $relation = CompleteData('order_detail_relation', $relation);
        return Orders::with($relation)->withCount('submissions')->where($where)->firstOrFail();
    }
}
