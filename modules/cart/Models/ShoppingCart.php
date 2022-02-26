<?php


namespace Modules\cart\Models;


class ShoppingCart
{
    protected $products;

    protected $types;

    protected $normal_sending_time=0;

    public $city_id=0;

    protected $sending_type=[
        'normal'=>'ارسال عادی',
        'fast'=>'ارسال سریع (مرسوله ها در سریع ترین زمان ممکن ارسال می شوند)'
    ];

    protected $productData;

    public function __construct($cart_type=null)
    {
        $this->products($cart_type);
    }

    public function products($cart_type){
        $cartProducts=new CartProducts($cart_type);
        $this->productData=$cartProducts->products();
        $this->products=$this->productData['products'];
        $this->types=$this->productData['cart_type'];
    }

    public function getData($only_product_data=false){
        $result=$this->set_product_sending_type();
        $this->set_final_price();
        $final_price=CompleteData('checkout_final_price',$this->productData['final_price']);
        $data=[
            'products'=>$this->products,
            'product_with_sending_type'=>$result,
            'sending_type'=>$this->sending_type,
            'cart_types'=>$this->types,
            'total_price'=>$this->productData['total_price'],
            'final_price'=>$final_price,
            'cart_price'=>$this->productData['cart_price'],
            'other_data'=>[],
            'city_id'=>$this->city_id,
            'product_count'=>$this->productData['product_count'],
        ];
        if(!$only_product_data){
            $data=CompleteData('shopping_cart_data',$data);
            run_action('after_complete_cart_data',[$data]);
        }
        $checkoutItems=run_action('checkout_items',[],true);
        $data['checkoutItems']=$checkoutItems;
        return $data;
    }

    public function set_product_sending_type(){
        $result=[];
        $normal_product_key=[];
        foreach ($this->types as $key=>$value){
            $result[$key]['normal']=[];
            $result[$key]['fast']=[];
        }
        foreach ($this->products as $type=>$array){
            foreach ($array as $key=>$value){

                if($value->send_time>$this->normal_sending_time){
                    $this->normal_sending_time=$value->send_time;
                }

                $normal_product_key[$type][$key]=$key;

                if(array_key_exists($value->send_time,$result[$type]['fast'])){
                    $result[$type]['fast'][$value->send_time]['product_key'][]=$key;
                }
                else{
                     $result[$type]['fast']= $result[$type]['fast']+[
                             $value->send_time=>[
                                 'product_key'=>[$key]
                             ]
                    ];
                }
            }
        }
        foreach ($this->types as $key=>$value){

           if(sizeof( $result[$key]['fast'])==1 || sizeof( $result[$key]['fast'])==0){
                unset( $result[$key]['fast']);
           }

           if(array_key_exists($key,$normal_product_key)){
               $result[$key]['normal']=[
                   $this->normal_sending_time=>[
                       'product_key'=>$normal_product_key[$key]
                   ]
               ];
           }
        }
        return $result;
    }

    public function set_final_price(){
        $final_price=$this->productData['final_price'];
        $newArray=[];
        foreach ($this->sending_type as $send_key=>$type){
            foreach ($final_price as $cart_type=>$v){
                $newArray[$cart_type][$send_key]=$v;
            }
        }
        unset($this->productData['final_price']);
        $this->productData['final_price']=$newArray;
    }
}
