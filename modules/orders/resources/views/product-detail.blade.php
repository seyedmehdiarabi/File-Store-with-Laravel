<?php

\App\Lib\GridView::showTable([
    'dataProvider'=>$value->products,
    'columns'=>[
        [
            'label'=>'تصویر','attr'=>function($model){
            if($model->product){
                $src=url('files/thumbnails/'.$model->product->image_url);
                return '<img src="'.$src.'" width="50px">';
            }
        },'html'=>true
        ],
        [
            'label'=>'عنوان','attr'=>function($model){
            if($model->product){
                return $model->product->title;
            }
        },
            'style'=>'width:300px'
        ],
        [
            'label'=>'تعداد','attr'=>function($model){
            return replace_number($model->product_count);
        }
        ],
        [
            'label'=>'قیمت واحد','attr'=>function($model){
            return get_price($model->product_price2);
        }
        ],
        [
            'label'=>'قیمت کل','attr'=>function($model){
            return get_price(($model->product_count*$model->product_price1));
        }
        ],
        [
            'label'=>'تخفیف','attr'=>function($model){
            $discount=($model->product_count*$model->product_price1) - ($model->product_count*$model->product_price2);
            return get_price(($discount));
        },
            'style'=>'min-width:140px'
        ],
        [
            'label'=>'قیمت نهایی','attr'=>function($model){
            return get_price(($model->product_count*$model->product_price2));
        }
        ]
    ],
    'tableCssClass'=>'submission-products'
],true,true);

?>
