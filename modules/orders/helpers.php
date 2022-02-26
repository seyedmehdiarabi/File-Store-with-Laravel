<?php

function get_order_detail($order)
{
    $array = [];

    $array[] = [
        'label' => 'نام و نام خانوادگی :',
        'value' => $order->getUserInfo->name
    ];

    $status='';
    if ($order->status=='awaiting_payment'){
        $status='در انتظار پرداخت';
    }elseif ($order->status=='ok'){
        $status='پرداخت شده';
    }elseif ($order->status=='canceled'){
        $status='لغو شده';
    }else{
        $status='خطا در اتصال به درگاه';
    }
    $array[] = [
        'label' => 'وضعيت سفارش :',
        'value' => $status
    ];

    if (!empty($order->price)){
        $array[] = [
            'label' => 'مبلغ قابل پرداخت:',
            'value' => get_price($order->price)
        ];
    }

    if (!empty($order->total_price)){
        $array[] = [
            'label' => 'هزينه كل :',
            'value' => get_price($order->total_price)
        ];
    }
    if(!empty($order->RefId)){
        $array[] = [
            'label' => 'RefId :',
            'value' => replace_number($order->RefId)
        ];
    }
    if(!empty($order->saleReferenceId)){
        $array[] = [
            'label' => 'saleReferenceId :',
            'value' => replace_number($order->saleReferenceId)
        ];
    }
    if(!empty($order->discount_id)){
        $array[] = [
            'label' => 'درصد تخفيف - كد تخفيف استفاده شده :',
            'value' => replace_number($order->getDiscount->value).'%'.' - '.$order->getDiscount->name
        ];
        $array[] = [
            'label' => 'تخفیف :',
            'value' => get_price($order->total_price-$order->price)
        ];
    }

    return $array;
}

function orderChartData()
{

    $jdf = new \App\Lib\Jdf();
    $date = $jdf->tr_num($jdf->jdate('Y/n')) . '/1';
    $time = getTimestamp($date, 'first');

    $y = $jdf->tr_num($jdf->jdate('Y'));
    $m = $jdf->tr_num($jdf->jdate('n'));
    $t = $jdf->tr_num($jdf->jdate('t'));

    $date_list = array();
    $price_array = array();
    $count_array = array();

    for ($i = 0; $i < $t; $i++) {
        $d = $y . '-' . $m . '-' . $i;
        $date_list[$i] = $d;
    }
    $orders = \Modules\orders\Models\Orders::where(['pay_status' => 'ok'])->where('created_at', '>=', $time)
        ->get();
    foreach ($orders as $order) {
        if (array_key_exists($order->date, $price_array)) {
            $price_array[$order->date] = $price_array[$order->date] + $order->price;
            $count_array[$order->date] = $count_array[$order->date] + 1;
        } else {
            $price_array[$order->date] = $order->price;
            $count_array[$order->date] = 1;
        }
    }

    foreach ($date_list as $key => $value) {
        if (!array_key_exists($value, $price_array)) {
            $price_array[$key] = 0;
            $count_array[$key] = 0;
        } else {
            $price_array[$key] = $price_array[$value];
            $count_array[$key] = $count_array[$value];
            unset($price_array[$value]);
            unset($count_array[$value]);
        }
    }
    return [
        'price_array' => $price_array,
        'count_array' => $count_array,
        'date_list' => $date_list
    ];
}
