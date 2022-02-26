@extends('backend-theme::layout')

@section('content')
    <div>
        @include('backend-theme::breadcrumb',['data'=>[['title'=>'مدیریت سفارشات','url'=>url('admin/orders')]]])
        @include('discount::_search_form')
        <?php
        $args=[];
        $args['title']=isset($title) ? $title : 'مدیریت سفارشات';
        $args['route']='admin/orders';
        if(isset($trash_orders_count)){
            $args['trashCount']=$trash_orders_count;
        }
        $args['routeParam']='سفارش';
        $args['remove_new_record']=true;
        ?>

        <x-panel-box :args="$args">

            <?php
            \App\Lib\GridView::showTable([
                'dataProvider'=>$orders,
                'columns'=>[
                    [
                        'label'=>'شماره سفارش',
                        'attr'=>function($value){
                            $style=($value->order_read=='no') ? 'color: red' : '';
                            return '<span style="'.$style.'">'.e(replace_number($value->order_id )).'</span>';
                        },
                        'html'=>true
                    ],
                    [
                        'label'=>'نام سفارش دهنده',
                        'attr'=>function($value){
                            return e($value->getUserInfo->name);
                        },
                    ],
                    [
                        'label'=>'زمان ثبت',
                        'attr'=>function($value){
                            return get_calendar($value->created_at,true);
                        },
                        'html'=>true
                    ],
                    [
                        'label'=>'مبلغ سفارش',
                        'attr'=>function($value){
                            return '<span class="alert alert-primary" style="padding: 5px 10px;border-radius:0px;">'.e(get_price($value['price'])).'</span>';
                        },
                        'html'=>true
                    ],
                    [
                        'label'=>'وضعیت سفارش',
                        'attr'=>function($value){
                            if($value['status']=='awaiting_payment'){
                                return '<v-alert type="warning" style="margin:0;" border="left" text>در انتظار پرداخت</v-alert>';
                            }
                            else if($value['status']=='ok'){
                                return '<v-alert type="success" style="margin:0;" border="left" text>پرداخت شده</v-alert>';
                            }
                            else if($value['status']=='canceled'){
                                return '<v-alert type="error" style="margin:0;" border="left" text>لغو شده</v-alert>';
                            }
                            else{
                                return '<v-alert type="error" style="margin:0;" border="left" text>خطا در اتصال به درگاه</v-alert>';
                            }
                        },
                        'html'=>true
                    ]
                ],
                'route_param'=>'orders',
                'tableLabel'=>'سفارش',
                'actions'=>[
                    function($value){
                        $url=url('admin/orders/'.$value->id);
                        return '<a href="'.$url.'" class="router-link"><v-icon>mdi-eye-outline</v-icon></a> ';
                    }
                ]
            ],true);
            ?>

            @if(!isset($removePaginate))
                {{ $orders->links() }}
            @endif


        </x-panel-box>
    </div>


@endsection
