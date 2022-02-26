<?php


namespace Modules\cart\Repository;

use DB;
use Auth;

class EloquentCartRepository  implements CartRepositoryInterface
{

    public function remove($where)
    {
        DB::table('cart')->where($where)->delete();
    }

    public function change_cart_product_type($price_variation_id, $type, $where_type)
    {
        $result='ok';
        $user_id=Auth::user()->id;
        $query=DB::table('cart')->where('user_id',$user_id);
        if($where_type=='whereNotIn'){
            $query=$query->whereNotIn('price_variation_id',$price_variation_id);
        }
        else if($where_type=='whereIn'){
            $query=$query->whereIn('price_variation_id',$price_variation_id);
        }
        else{
            $price_variation_id=$price_variation_id[0];
            $query=$query->where('price_variation_id',$price_variation_id);
        }
        $query->update(['type'=>$type]);
        if(($where_type=='whereIn' && $type==1) || $where_type=='where'){
            $result='get_new_data';
        }
        return $result;
    }

    public function update_product_final_count($priceVariation_id, $product_count)
    {
       if(Auth::check()){
           $user_id=Auth::user()->id;
           DB::table('cart')->where([
               'price_variation_id'=>$priceVariation_id,
               'user_id'=>$user_id
           ])->update(['product_status'=>$product_count]);
       }
    }
}
