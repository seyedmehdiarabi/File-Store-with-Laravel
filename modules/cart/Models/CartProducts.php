<?php


namespace Modules\cart\Models;

use Auth;
use Modules\priceVariation\Repository\PriceVariationRepositoryInterface;
use Session;
class CartProducts
{
    protected $cart=[];

    protected $type=[
        1=>['title'=>'سبد خرید'],
        2=>['title'=>'سبد خرید بعدی']
    ];

    protected $priceVariations;

    protected $total_price=[];

    protected $final_price=[];

    protected $price=[];

    protected $cartTable=null;

    protected $product_count=[];

    public function __construct($cart_type=null)
    {
       if($cart_type){
           foreach ($this->type as $key=>$value){
               if($key!=$cart_type){
                   unset($this->type[$key]);
               }
           }
       }

    }

    public function products(){
        $this->setCartData();
        $products=$this->getProductsData();
        return [
            'products'=>$products,
            'cart_type'=>$this->type,
            'total_price'=>$this->total_price,
            'final_price'=>$this->final_price,
            'cart_price'=>$this->price,
            'product_count'=>$this->product_count,
        ];
    }

    protected function setCartData(){
        if(Auth::check()){
            $this->cartTable=new CartTable();
            $this->cart= $this->cartTable->get_cart_data();
        }
        else{
            $this->cart=Session::get('cart',array());
        }
    }

    protected function getProductsData(){

        $products=[];
        foreach ($this->type as $key=>$value){
            $products[$key]=[];
        }

        $variation_ids=[];
        foreach ($this->cart as $product_id=>$data){
            if(array_key_exists('product_data',$data)){
                foreach ($data['product_data'] as $key=>$value){
                   $params=explode('_',$key);
                   if(sizeof($params)==3){
                       $price_variation_id=intval($params[0]);
                       $variation_ids[]=$price_variation_id;
                   }
                }
            }
        }
        $this->priceVariations=$this->getPriceVariation($variation_ids);

        foreach ($this->cart as $product_id=>$data){
            $type=array_key_exists('type',$data) ? $data['type'] : 1;
            if(array_key_exists('product_data',$data)){
                foreach ($data['product_data'] as $key=>$value){
                    $params=explode('_',$key);
                    if(sizeof($params)==3 && array_key_exists($type,$this->type)){
                        $price_variation_id=intval($params[0]);
                        $param1_id=$params[1] ? intval($params[1]) : null;
                        $param2_id=$params[2] ? intval($params[2]) : null;
                        $priceVariation=$this->checkHasPriceVariation($price_variation_id,$param1_id,$param2_id);

                        if($priceVariation && $priceVariation->product){

                            $product_count=$priceVariation->product_number>=$value ? $value : $priceVariation->product_number;

                            $priceVariation->product_count=$product_count;
                            $priceVariation=CompleteData('set_cart_product_data',$priceVariation);
                            $size=sizeof($products[$type]);
                            $products[$type][$size]=$priceVariation;
                            $this->setCartPrice($priceVariation,$type);

                            if(array_key_exists('product_info',$data)){
                                $priceVariation->initial_amount=$data['product_info'][$key]['initial_amount'];
                            }
                            if($product_count!=$value){
                                $priceVariation->initial_product_count=$value;
                                if($this->cartTable){
                                    $this->cartTable->update_product_final_count($priceVariation->id,$product_count);
                                }
                            }
                        }
                    }
                }

            }
        }

        return $products;
    }

    public function getPriceVariation($variation_ids){
        $repository=app(PriceVariationRepositoryInterface::class);
        $relation=CompleteData('cart_product_data',['param1','param1','product']);
        $priceVariations=$repository->all($variation_ids,$relation);
        return $priceVariations;
    }

    public function checkHasPriceVariation($price_variation_id,$param1_id,$param2_id){
        foreach ($this->priceVariations as $variation){
            if($variation->id==$price_variation_id && $variation->param1_id==$param1_id && $variation->param2_id==$param2_id){
                return $variation;
            }
        }
    }

    public function setCartPrice($priceVariation,$type){
        if(array_key_exists($type,$this->total_price)){
            $this->total_price[$type]=$this->total_price[$type]+$priceVariation->price1*$priceVariation->product_count;
            $this->final_price[$type]=$this->final_price[$type]+$priceVariation->price2*$priceVariation->product_count;
            $this->price[$type]=$this->price[$type]+$priceVariation->price2*$priceVariation->product_count;
        }
        else{
            $this->total_price[$type]=$priceVariation->price1*$priceVariation->product_count;
            $this->final_price[$type]=$priceVariation->price2*$priceVariation->product_count;
            $this->price[$type]=$priceVariation->price2;
        }
        $this->product_count[$type]=$priceVariation->product_count;
    }
}
