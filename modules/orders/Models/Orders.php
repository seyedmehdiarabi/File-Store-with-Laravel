<?php


namespace Modules\orders\Models;


use App\CustomModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\discount\Models\Discount;
use Modules\users\Models\User;

class Orders extends CustomModel
{
    use SoftDeletes;

    protected $dateFormat = 'U';

    protected $table='orders';

    protected $guarded=[];

    public static function getData($request)
    {
        $string='?';
        $orders=self::with('getUserInfo','getDiscount')->orderBy('id','DESC');
        if(inTrashed($request))
        {
            $orders=$orders->onlyTrashed();
            $string=create_paginate_url($string,'trashed=true');
        }
        if(array_key_exists('user_id',$request) && !empty($request['user_id']))
        {
            $orders=$orders->where('user_id',$request['user_id']);
            $string=create_paginate_url($string,'user_id='.$request['user_id']);
        }
        if(array_key_exists('order_id',$request) && !empty($request['order_id']))
        {
            $order_id=replace_number2($request['order_id']);
            $orders=$orders->where('order_id',$order_id);
            $string=create_paginate_url($string,'order_id='.$request['order_id']);
        }
        if(array_key_exists('first_date',$request) && !empty($request['first_date']))
        {
            $first_date=getTimestamp($request['first_date'],'first');
            $orders=$orders->where('created_at','>=',$first_date);
            $string=create_paginate_url($string,'first_date='.$request['first_date']);
        }
        if(array_key_exists('end_date',$request) && !empty($request['end_date']))
        {
            $end_date=getTimestamp($request['end_date'],'end');
            $orders=$orders->where('created_at','<=',$end_date);
            $string=create_paginate_url($string,'end_date='.$request['end_date']);
        }
        $orders=$orders->paginate(10);
        $orders->withPath($string);
        return $orders;
    }
    public function getUserInfo()
    {
        return $this->hasOne(User::class,'id','user_id');
    }
    public function getDiscount()
    {
        return $this->hasOne(Discount::class,'id','discount_id');
    }


}
