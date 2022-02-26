<?php

function  get_product_status(){
    $array=array();
    $array[-3]='رد شده';
    $array[-2]='در انتظار بررسی';
    $array[-1]='توقف تولید';
    $array[0]='ناموجود';
    $array[1]='منتشر شده';

    return $array;
}

function get_product_url($product){
    return url('product/'.$product->id.'/'.$product->product_url);
}
