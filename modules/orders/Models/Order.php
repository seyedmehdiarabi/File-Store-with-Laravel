<?php

namespace Modules\orders\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\discount\Models\Discount;
use Modules\users\Models\User;

class Order extends Model
{
    use SoftDeletes;
    protected $table="orders";
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
        if(array_key_exists('order_id',$request) && !empty($request['order_id']))
        {
            $orders=$orders->where('order_id',$request['order_id']);
            $string=create_paginate_url($string,'order_id='.$request['order_id']);
        }
        $orders=$orders->paginate(20);
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
