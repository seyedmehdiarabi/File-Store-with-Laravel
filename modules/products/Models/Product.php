<?php

namespace Modules\products\Models;

use App\CustomModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;
use Mail;
use App\Mail\ProductStatus;
use Modules\categories\Models\Category;

class Product extends CustomModel
{
    use SoftDeletes;

    protected $table='products';

    protected $guarded=['tag_list','pic'];

    public static function ProductStatus()
    {
        $array=array();
        $array[0]='در حال ضبط';
        $array[1]='تکمیل ضبط';
        return $array;
    }

    public static function getData($request)
    {
        $string='?';
        $relation=CompleteData('product_list_relation',[]);
        $products=self::with($relation);
        if(inTrashed($request)){
            $products=$products->onlyTrashed();
            $string=create_paginate_url($string,'trashed=true');
        }
        else{
            $products=$products->whereNull('deleted_at');
        }

        if(array_key_exists('order_by',$request)){
            if($request['order_by']=='new'){
                $products=$products->orderBy('id','DESC');
            }
        }
        else{
            $products=$products->orderBy('id','DESC');
        }

        if(array_key_exists('string',$request) && !empty($request['string']))
        {
            $products=$products->where('title','like','%'.$request['string'].'%');
            $string=create_paginate_url($string,'string='.$request['string']);
        }
        if(array_key_exists('status',$request) && $request['status']!==null){
            $products=$products->where('status',$request['status']);
            $string=create_paginate_url($string,'status='.$request['status']);
        }

        $data=CompleteData('select_product_list',[$products,$string]);
        $products=$data[0];
        $string=$data[1];

        $products=$products->paginate(10);
        $products->withPath($string);
        return $products;
    }

    public function  getSeller()
    {
        return $this->hasOne(Seller::class,'id','seller_id')
            ->withDefault(['brand_name'=>config('shop-info.shop_name')]);
    }

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($product) {
            if ($product->isForceDeleting()) {
                remove_file($product->image_url,'products');
                remove_file($product->image_url,'thumbnails');
                DB::table('product_color')->where('product_id',$product->id)->delete();
                DB::table('item_value')->where('product_id',$product->id)->delete();
                DB::table('product_warranties')->where('product_id',$product->id)->delete();
                DB::table('review_product')->where('product_id',$product->id)->delete();
            }
        });
    }

    public function getCat()
    {
        return $this->hasOne(Category::class,'id','cat_id')->withDefault(['name'=>'']);
    }



    public static function setProductStatus($product,$request,$old_status)
    {
        $seller_info=null;
        if($product->seller_id>0)
        {
            $seller_info=Seller::where('id',$product->seller_id)->withTrashed()->first();
        }
        $reject_message=$request->get('reject_message');
        if(!empty($reject_message))
        {
            $user_id=$request->user()->id;
            $time=time();
            DB::table('reject_message')->insert([
                'product_id'=>$product->id,
                'time'=>$time,
                'user_id'=>$user_id,
                'tozihat'=>$reject_message
            ]);
            if($seller_info && !empty($seller_info->email))
            {
                Mail::to($seller_info->email)->queue(new ProductStatus($product->title,$product->status,$reject_message));
            }
        }
        else if($old_status!=$product->status)
        {
            if($seller_info && !empty($seller_info->email))
            {
                Mail::to($seller_info->email)->queue(new ProductStatus($product->title,$product->status,''));

            }
        }
    }

    public function sellerList()
    {
        return $this->hasMany(SellerProduct::class,'product_id','id');
    }

    public function DigiWarranty()
    {
        return $this->hasOne(ProductWarranty::class,'product_id','id')->select(['id','product_id'])->where('product_number','>',0);
    }

}
