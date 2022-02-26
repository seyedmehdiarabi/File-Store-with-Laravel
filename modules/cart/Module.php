<?php


namespace Modules\cart;

use Modules\cart\Models\Cart;
use Modules\cart\Models\CartTable;
use DB;
use URL;
use Session;
class Module
{

    public function registerComponent(){
        if(!request()->is('admin/*')){
            return vue_component_detail('cart');
        }
    }
}
