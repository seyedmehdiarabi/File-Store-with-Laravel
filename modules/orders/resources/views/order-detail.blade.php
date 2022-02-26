<?php
  $args=[];
  $args['title']='جزییات سفارش - '.replace_number($order->order_id);
?>

<x-panel-box :args="$args">

    @if($order->status=='ok')
        <a style="display: block" target="_blank" href="{{ url('admin/order/'.$order->id.'/factor') }}">
           <v-btn color="error">
               فاکتور سفارش
           </v-btn>
        </a>
    @endif

        <v-chip class="mt-6" color="primary" outlined large>
            <v-icon left>mdi-information-outline</v-icon>
            اطلاعات سفارش
        </v-chip>
    <?php
      $get_order_detail=get_order_detail($order);
      $j=0;
    ?>
        <div>
            <table class="table table-bordered order_table_info">


                @for($i=0;$i<ceil(sizeof($get_order_detail)/2);$i++)
                    <tr>
                        <td @if(!array_key_exists(($j+1),$get_order_detail)) colspan="2" style="text-align: center" @endif>
                            {{ $get_order_detail[$j]['label'] }}
                            <span> {{ $get_order_detail[$j]['value'] }}</span>
                        </td>
                        <?php $j++ ?>
                        @if(array_key_exists($j,$get_order_detail))
                            <td>
                                {{ $get_order_detail[$j]['label'] }}
                                <span> {{ $get_order_detail[$j]['value'] }}</span>
                            </td>
                            <?php $j++ ?>
                        @endif

                    </tr>
                @endfor

            </table>
        </div>
        <div class="">
        <v-chip  class="my-4" color="red darken-4" outlined large>
            <v-icon left>mdi-cart-outline</v-icon>
            محصولات خریداری شده
        </v-chip>
        <?php
        $products_id=explode(',',$order->product_id);
        $product=[];
        foreach ($products_id as $key=>$value){
            if (!empty($value)){
                $repository = app(\Modules\products\Repository\ProductRepositoryInterface::class);
                $product[$key]=$repository->find($value);
            }
        }
        \App\Lib\GridView::showTable([
            'dataProvider'=>$product,
            'columns'=>[
                ['label'=>'عنوان محصول', 'attr'=>function($model){
                    return '<a href="'.url('product').'/'.$model->slug.'" target="__blank" >'.e(($model['title'])).'</a>';
                },'html'=>true],
                ['label'=>'تصویر محصول', 'attr'=>function($model){
                    $src = url('files/products/'. $model->pic);
                    return '<img src="' . $src . '" class="product_pic">';
                },'html'=>true],
                ['label'=>'قیمت محصول', 'attr'=>function($model){
                    if ($model['price']>0){
                        $price=e(get_price($model['price']));
                    }else{
                        $price='رایگان';
                    }
                    return '<v-alert width="50%" class="my-2" type="success" style="margin:0 auto;" border="left" text>'.$price.'</v-alert>';
                },'html'=>true],
            ],
        ],true,true);
        ?>
    </div>

</x-panel-box>
