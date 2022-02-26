<?php


namespace Modules\products;


use Modules\products\Models\Product;

class Module
{
    public function set_user_access_list($access){
        $access['products']=[
            'label'=>'محصولات',
            'access'=>[
                'product_edit'=>['label'=>'ثبت و ویرایش محصولات','routes'=>[
                    'products.index','products.create','products.store','products.edit','products.update'
                ]],
                'remove_product'=>['label'=>'حذف محصولات','routes'=>['products.index','products.destroy']],
                'restore_product'=>['label'=>'بازیابی محصولات','routes'=>['products.index','products.restore']]
            ]
        ];

        return $access;
    }

    public function before_show_view(){
        $routeName = getRouteName();
        if ($routeName == 'home') {
            $products=Product::where('status',1)->orderBy('id','DESC')->get();
            view()->share('products', $products);
        }
    }
}
