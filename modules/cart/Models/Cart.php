<?php

namespace Modules\cart\Models;

use Auth;
use Modules\priceVariation\Repository\PriceVariationRepositoryInterface;
use Session;
use DB;
class Cart
{
    protected $product_id;
    protected $param1_id;
    protected $param1_type;
    protected $param2_id;
    protected $param2_type;

    public function add_cart($data){
         $this->setPriceVariationData($data);
         if(!Auth::check()){
             $this->addSessionCart();
         }
         else{
             $priceVariation=$this->getPriceVariation();
             $cartTable=new CartTable();
             $cartTable->add($priceVariation);
         }
    }

    public function setPriceVariationData($data){
        $this->product_id=array_key_exists('product_id',$data) ? intval($data['product_id']) : 0;
        $variation_params=array_key_exists('variation_params',$data) ? $data['variation_params'] : [];
        foreach ($variation_params as $key=>$value){
            if($this->param1_id==null){
                $type=str_replace("'",'',$key);
                $type=$this->getVariationType($type);
                $this->param1_id=$value ? intval($value) : $value;
                $this->param1_type=$type ? $type : null;
            }
            else{
                $type=str_replace("'",'',$key);
                $type=$this->getVariationType($type);
                $this->param2_id=$value ? intval($value) : $value;
                $this->param2_type=$type ? $type : null;
            }
        }
    }

    public function addSessionCart(){
        $cart=Session::get('cart',array());
        $priceVariation=$this->getPriceVariation();
        if($priceVariation && $priceVariation->product_number>=1){
            $params=$priceVariation->id.'_'.$this->param1_id.'_'.$this->param2_id;
            if(array_key_exists($this->product_id,$cart))
            {
                $product_data=$cart[$this->product_id]['product_data'];
                if(array_key_exists($params,$product_data))
                {
                    $count=$cart[$this->product_id]['product_data'][$params]+1;
                    if( $priceVariation->product_number>=$count){
                        $cart[$this->product_id]['product_data'][$params]++;
                    }
                }
                else{
                    $cart[$this->product_id]['product_data'][$params]=1;
                }
            }
            else
            {
                $cart[$this->product_id]=[
                    'product_data'=>[$params=>1]
                ];
            }
             Session::put('cart',$cart);
            run_action('card_changed',[]);
        }
    }

    public function getPriceVariation($where=[]){
        $repository=app(PriceVariationRepositoryInterface::class);
        $where=$where ? $where : [
            'product_id'=>$this->product_id,
            'param1_type'=>$this->param1_type,
            'param2_type'=>$this->param2_type,
            'param1_id'=>$this->param1_id,
            'param2_id'=>$this->param2_id,
        ];
        $priceVariation=$repository->first($where);
        return $priceVariation;
    }

    public function remove_product($request){
        $product_id=$request->get('product_id',0);
        $variation_id=$request->get('variation_id',0);
        $param1_id=str_replace('null','',$request->get('param1_id'));
        $param2_id=str_replace('null','',$request->get('param2_id'));
        $cart=Session::get('cart',array());
        if(sizeof($cart)>0){
            if(array_key_exists($product_id,$cart))
            {
                $product_data=$cart[$product_id]['product_data'];
                $childKey=$variation_id.'_'.$param1_id.'_'.$param2_id;
                if(array_key_exists($childKey,$product_data)){
                    unset($cart[$product_id]['product_data'][$childKey]);

                    if(empty($cart[$product_id]['product_data']))
                    {
                        unset($cart[$product_id]);
                    }

                    if(empty($cart))
                    {
                        Session::forget('cart');
                    }
                    else{
                        Session::put('cart',$cart);
                    }
                    return  'ok';
                }
                else{
                    return 'error';
                }
            }
            else{
                return 'error';
            }
        }
        else{
            $cartTable=new CartTable();
            $result=$cartTable->remove_product($product_id,$variation_id);
            return  $result;
        }
    }

    public function change_product_cart($request){

        $product_id=$request->get('product_id',0);
        $variation_id=$request->get('variation_id',0);
        $param1_id=str_replace('null','',$request->get('param1_id'));
        $param2_id=str_replace('null','',$request->get('param2_id'));
        $product_count=$request->get('product_count',1);
        if($product_count>0){
            settype($product_count,'integer');
            $cart=Session::get('cart',array());

            $priceVariation=$this->getPriceVariation([
                'id'=>$variation_id,
                'param1_id'=>$param1_id ? $param1_id :null,
                'param2_id'=>$param2_id ? $param2_id :null
            ]);
            if($priceVariation){

                if(sizeof($cart)>0){

                    if(array_key_exists($product_id,$cart))
                    {

                        $product_data=$cart[$product_id]['product_data'];
                        $childKey=$variation_id.'_'.$param1_id.'_'.$param2_id;
                        if(array_key_exists($childKey,$product_data))
                        {
                            if($priceVariation->product_number>=$product_count && $priceVariation->product_number_cart>=$product_count){
                                $cart[$product_id]['product_data'][$childKey]=$product_count;
                                Session::put('cart',$cart);
                            }
                        }
                    }
                }
                else{
                    $cartTable=new CartTable();
                    $cartTable->update_product_count($priceVariation,$param1_id,$param2_id,$product_count);
                }
            }
        }

    }

    public static function get_product_count()
    {
        $count=0;
        $cart=Session::get('cart',array());
        if(sizeof($cart)>0)
        {
            foreach ($cart as $key=>$value)
            {
                $count+=sizeof($value['product_data']);
            }

        }
        else{
            if(Auth::check()){
                $user_id=Auth::user()->id;
                $count=DB::table('cart')->where([
                        'user_id'=>$user_id,
                        'product_status'=>'available',
                        'type'=>1
                    ])->count();
            }
        }
        return $count;
    }

    public static function refresh_cart_table(){
        $user_id=Auth::user()->id;
        DB::table('cart')->where(['user_id'=>$user_id,'product_status'=>'unavailable'])->delete();
        $cart_table_data=DB::table('cart')->where('user_id',$user_id)->get();
        foreach ($cart_table_data as $key=>$value){
            $update_array=[];
            if(!empty($value->final_amount))
            {
                $update_array['initial_amount']=$value->final_amount;
                $update_array['final_amount']=null;
            }
            $product_status=$value->product_status;
            settype($product_status,'integer');
            if($product_status>0)
            {
                $update_array['count']=$product_status;
                $update_array['product_status']='available';
            }
            if(sizeof($update_array)){
                DB::table('cart')->where('id',$value->id)
                    ->update($update_array);
            }
        }
    }

    public static function empty_cart($user_id=null)
    {
        if(Auth::check() || $user_id)
        {
            $user_id=$user_id ? $user_id : Auth::user()->id;
            DB::table('cart')->where(['user_id'=>$user_id])->delete();
        }
    }

    public function getVariationType($type){
        $type=str_replace('\\param1','',$type);
        $type=str_replace('\\param2','',$type);
        return $type;
    }
}
