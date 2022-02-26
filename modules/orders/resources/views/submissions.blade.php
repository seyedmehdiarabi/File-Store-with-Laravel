@extends('backend-theme::layout')

@section('content')

    <div>

        @include('backend-theme::breadcrumb',['data'=>[
            ['title'=>'مدیریت سفارشات','url'=>url('admin/orders')],
            ['title'=>'مدیریت مرسوله ها','url'=>url('admin/orders/submissions')]
        ]])

        @includeIf('orders::submission.search-box')

        <?php
        $args=[];
        $args['title']=$label;
        $args['route']='admin/orders/'.$label_url;
        $args['trashCount']=$trash_submission_count;
        $args['routeParam']='مرسوله';
        $args['remove_new_record']=true;
        ?>

        <x-panel-box :args="$args">
            <?php
            $OrderStatus=\Modules\orders\Models\Orders::OrderStatus();
            define('OrderStatus',$OrderStatus);
            ?>
            <?php
            \App\Lib\GridView::showTable([
                'dataProvider'=>$submissions,
                'columns'=>[
                    [
                        'label'=>'کد مرسوله',
                        'attr'=>function($value){
                            return e(replace_number($value->id));
                        },
                    ],
                    [
                        'label'=>'تاریخ ثبت',
                        'attr'=>function($value){
                            $e=explode(' ',$value->created_at); $e2=explode('-',$e[0]);
                            $jdf=new \App\Lib\Jdf();
                            return e(replace_number($jdf->gregorian_to_jalali($e2[0],$e2[1],$e2[2],'-') ));
                        },
                    ],
                    [
                        'label'=>'تعداد کالا',
                        'attr'=>function($value){
                            return e(replace_number($value->product_sum_product_count));
                        },
                    ],
                    [
                        'label'=>'وضعیت مرسوله',
                        'attr'=>function($value){
                            if(array_key_exists($value->send_status,OrderStatus)){
                                return e(OrderStatus[$value->send_status]);
                            }
                            else{
                                return '';
                            }
                        },
                    ]
                ],
                'route_param'=>'orders/submissions',
                'tableLabel'=>'مرسوله',
                'actions'=>[
                    function($value){
                        $url=url('admin/orders/submissions/'.$value->id);
                        return '<a href="'.$url.'" class="router-link"><v-icon>mdi-eye-outline</v-icon></a> ';
                    }
                ]
            ],true);
            ?>

            {{ $submissions->links() }}

        </x-panel-box>

    </div>
@endsection
