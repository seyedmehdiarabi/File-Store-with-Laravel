@extends('backend-theme::layout')

@section('head')
    <title>مدیریت سفارشات</title>
@endsection

@section('content')
<div class="">
    <div class="pishkhan">
        @include('backend-theme::breadcrumb',['data'=>[['title'=>'مدیریت سفارشات','url'=>url('admin/orders')],['title'=>'نمايش سفارش - '.replace_number($order->order_id),'url'=>url('admin/orders/'.$order->id)]]])
    </div>
    <?php
    $args=['title'=>''];

    ?>


    <x-panel-box :args="$args">
    <div class="">
        <v-chip  class="mt-4" color="primary" outlined large>
            <v-icon left>mdi-information-outline</v-icon>
            اطلاعات سفارش
        </v-chip>
        <table class="table table-bordered order_table_info">
            <tr>
                <td>نام و نام خانوادگی :</td>
                <td><span>{{$order->getUserInfo->name}}</span></td>
            </tr>
            <tr>
                <td>شماره سفارش :</td>
                <td><span>{{replace_number($order->order_id)}}</span></td>
            </tr>
            <tr>
                <td>وضعيت سفارش :</td>
                <td>
                    @if($order->status=='awaiting_payment')
                        <v-alert class="w-25" type="warning" style="margin:0;" border="left" text>در انتظار پرداخت</v-alert>
                    @elseif($order->status=='ok')
                        <v-alert class="w-25" type="success" style="margin:0;" border="left" text>پرداخت شده</v-alert>
                    @elseif($order->status=='canceled')
                        <v-alert class="w-25" type="error" style="margin:0;" border="left" text>لغو شده</v-alert>
                    @else
                        <v-alert class="w-25" type="error" style="margin:0;" border="left" text>خطا در اتصال به درگاه</v-alert>
                    @endif
                </td>
            </tr>
            @if(!empty($order->RefId))
                <tr>
                    <td>RefId :</td>
                    <td><span>{{$order->RefId}}</span></td>
                </tr>
            @endif
            @if(!empty($order->saleReferenceId))
                <tr>
                    <td>saleReferenceId :</td>
                    <td><span>{{$order->saleReferenceId}}</span></td>
                </tr>
            @endif
            @if(!empty($order->discount_id))
                <tr>
                    <td>كد تخفيف استفاده شده :</td>
                    <td><span>{{$order->getDiscount->name}}</span></td>
                </tr>
                <tr>
                    <td>درصد تخفيف :</td>
                    <td><span> % {{replace_number($order->getDiscount->value)}}</span></td>
                </tr>
            @endif
            <tr>
                <td>هزينه كل :</td>
                <td><span>{{replace_number(number_format($order->total_price))}} تومان </span></td>
            </tr>
            @if(!empty($order->discount_id))
                <tr>
                    <td style="color: crimson">تخفیف :</td>
                    <td style="color: crimson">{{replace_number(number_format($order->total_price-$order->price))}} تومان </td>
                </tr>
            @endif
            <tr>
                <td>هزینه قابل پرداخت :</td>
                <td><v-alert class="w-25" type="success" style="margin:0;" border="left" text>{{replace_number(number_format($order->price))}} تومان </v-alert></td>
            </tr>
        </table>
        <v-chip  class="mt-4" color="red darken-4" outlined large>
            <v-icon left>mdi-cart-outline</v-icon>
            محصولات خریداری شده
        </v-chip>
        @php
            $columns=[
                        ['label'=>'عنوان محصول','attr'=>function($model){
                            return '<a href="'.url('product').'/'.$model->slug.'" target="__blank" >'.e(($model['title'])).'</a>';
                        },'html'=>true],
                        ['label' => 'تصویر محصول', 'attr' => function ($model) {
                        $src = url('files/products/'. $model->pic);
                        return '<img src="' . $src . '" class="product_pic">';
                        }, 'html' => true],
                        ['label'=>'هزینه محصول','attr'=>function($model){
                            if ($model['price']>0){
                                $price=e(get_price($model['price']));
                            }else{
                                $price='رایگان';
                            }
                            return '<v-alert class="w-50 my-2" type="success" style="margin:0 auto;" border="left" text>'.$price.'</v-alert>';
                        },'html'=>true]
                    ];
            $table = table_index($columns,$product,url('admin/product'),'محصول',false,false,false);
        @endphp
        <panel-table class="mb-16" :headers="{{ json_encode($table['headers']) }}" :items="{{json_encode($table['items'])}}"></panel-table>
    </div>
    </x-panel-box>
</div>
@endsection
