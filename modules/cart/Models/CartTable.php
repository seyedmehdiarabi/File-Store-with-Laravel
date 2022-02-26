<?php


namespace Modules\cart\Models;

use Modules\cart\Repository\CartRepositoryInterface;
use Modules\priceVariation\Repository\PriceVariationRepositoryInterface;
use Session;
use DB;
use Auth;
use Log;
class CartTable
{
    protected $user;

    public function add_session_cart_to_table($user){
        $this->user=$user;
        $cart=Session::get('cart',array());
        foreach ($cart as $product_id=>$data){
            if(array_key_exists('product_data',$data)){
                foreach ($data['product_data'] as $key=>$value){
                    $params=explode('_',$key);
                    if(sizeof($params)==3){
                        $price_variation_id=intval($params[0]);
                        $param1=$params[1] ? $params[1] : null;
                        $param2=$params[2] ? $params[2] : null;
                        $this->add_product_to_cart_table($price_variation_id,$param1,$param2,$value);
                    }
                }
            }
        }
        Session::forget('cart');
    }

    public function add_product_to_cart_table($price_variation_id,$param1_id,$param2_id,$count){
        $repository=app(PriceVariationRepositoryInterface::class);
        $price_variation=$repository->first([
            'id'=>$price_variation_id,
            'param1_id'=>$param1_id,
            'param2_id'=>$param2_id
        ]);
        if($price_variation){
            $row=DB::table('cart')->where([
                'price_variation_id'=>$price_variation_id,
                'param1_id'=>$param1_id,
                'param2_id'=>$param2_id,
                'user_id'=>$this->user->id
            ])->first();
            if($row){
                $this->update_product($price_variation,$row,$count);
            }
            else{
                $this->add_product($price_variation,$count);
            }
            run_action('card_changed',[]);
        }
    }

    public function add_product($price_variation,$count){
        if($price_variation->product_number>0)
        {
            $count=($price_variation->product_number-$count>=0) ? $count : $price_variation->product_number;
            DB::table('cart')->insert([
                'product_id'=>$price_variation->product_id,
                'price_variation_id'=>$price_variation->id,
                'param1_id'=>$price_variation->param1_id,
                'param2_id'=>$price_variation->param2_id,
                'user_id'=>$this->user->id,
                'count'=>$count,
                'initial_amount'=>$price_variation->price2
            ]);
        }

    }

    public function update_product($price_variation,$tableRow,$count){
        $new_count=$tableRow->count+$count;
        if($price_variation->product_number>=$new_count && $price_variation->product_number_cart>=$new_count){
            DB::table('cart')->where([
                'product_id'=>$price_variation->id,
                'price_variation_id'=>$price_variation->id,
                'user_id'=>$this->user->id
            ])->update(['count'=>$new_count]);
        }
    }

    public function get_cart_data(){
        $user_id=Auth::user()->id;
        $data=[];
        $rows=DB::table('cart')->where(['user_id'=>$user_id])->get();
        foreach ($rows as $row){
            $k=$row->price_variation_id.'_'.$row->param1_id.'_'.$row->param2_id;
            $data[$row->product_id]['product_data'][$k]=$row->count;
            $data[$row->product_id]['type']=$row->type;
            if($row->final_amount!=null){
               $data[$row->product_id]['product_info'][$k]['initial_amount']=$row->initial_amount;
            }
        }
        return $data;
    }

    public function remove_product($product_id,$variation_id){
        $result='error';
        if(Auth::check()){
            $user_id=Auth::user()->id;
            $repository=app(CartRepositoryInterface::class);
            $repository->remove([
                'price_variation_id'=>$variation_id,
                'product_id'=>$product_id,
                'user_id'=>$user_id
            ]);
            return  'ok';
        }
        return $result;
    }

    public function add($priceVariation){
        $this->user=Auth::user();
        $this->add_product_to_cart_table($priceVariation->id,$priceVariation->param1_id,$priceVariation->param2_id,1);
    }

    public function update_product_count($priceVariation,$param1_id,$param2_id,$product_count){
        if(Auth::check()){
            $user_id=Auth::user()->id;
            $param1_id=$param1_id ? intval($param1_id) : null;
            $param2_id=$param2_id ? intval($param2_id) : null;
            if($priceVariation->product_number>=$product_count && $priceVariation->product_number_cart>=$product_count){
                DB::table('cart')->where([
                    'price_variation_id'=>$priceVariation->id,
                    'user_id'=>$user_id,
                    'param1_id'=>$param1_id,
                    'param2_id'=>$param2_id
                ])->update(['count'=>$product_count]);
            }
        }
    }

    public function update_product_final_count($priceVariation_id,$product_count){
        $repository=app(CartRepositoryInterface::class);
        $repository->update_product_final_count($priceVariation_id,$product_count);
    }
}
