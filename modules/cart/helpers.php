<?php

function get_send_order_amount($send_type,$send_order_data){
    if(array_key_exists('final_sending_price',$send_order_data))
    {
        $otherPrice=false;
        $price=$send_order_data['final_sending_price'][1][$send_type]['price'];
        if(array_key_exists('after_price',$send_order_data['final_sending_price'][1][$send_type])){
            $otherPrice=$send_order_data['final_sending_price'][1][$send_type]['after_price'];
        }


        $result=get_price($price);

        if($otherPrice){
            if($price==0){
                $result=" پس کرایه ";
            }
            else{
                $result=$result." + پس کرایه ";
            }
        }
        else{
            if($price==0){
                $result="رایگان";
            }
        }

        return $result;
    }
    else{
        return  "رایگان";
    }
}

function get_submission_count($send_order_data,$send_type){
    $i=0;
    foreach($send_order_data['product_with_sending_type'][1][$send_type] as $key=>$value){
        foreach($value as $key2=>$value2){
            if($key2=='product_key'){
                $i++;
            }
            else{
                foreach($value2 as $key3=>$value3){
                    if($key3=='product_key'){
                        $i++;
                    }
                }
            }
        }
    }
    return $i;
}
