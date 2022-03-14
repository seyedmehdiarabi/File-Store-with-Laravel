@extends('backend-theme::layout')

@section('head')
    <title>مدیریت سفارشات</title>
@endsection

@section('content')
<div class="">
    <div class="pishkhan">
        @include('backend-theme::breadcrumb',['data'=>[['title'=>'مدیریت سفارشات','url'=>url('admin/orders')]]])
    </div>
    <?php
    $args=['title'=>'مدیریت سفارشات'];
    ?>


    <x-panel-box :args="$args">
    @php
        $columns=[
            [
                'label'=>'شماره سفارش',
                'attr'=>function($value){
                    $style=($value->order_read==0) ? 'color: red' : '';
                    return '<span style="'.$style.'">'.e(replace_number($value->order_id)).'</span>';
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
                    $jdf=new \App\Lib\Jdf();
                    $time=$jdf->jdate('H:i:s',$value->created_at->timestamp).' / '.$jdf->jdate('Y-n-j',$value->created_at->timestamp);
                    return e($time);
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
            ],
        ];
        $table = table_index($columns,$order,url('admin/orders'),'سفارش',
        [
            function($value){
                $url=url('admin/orders/'.$value->id);
                return '<a href="'.$url.'" class="router-link"><v-icon>mdi-eye-outline</v-icon></a> ';
            }
        ],false);
    @endphp
    <div class="">
        {!! Form::open(['url' => 'admin/orders','method' => 'get','id'=>'form_search'])  !!}
        <div class="d-flex mt-4">
            <v-col cols="12" md="3">
                <v-text-field name="order_id" label="شماره سفارش" outlined></v-text-field>
            </v-col>
            <div class="my-4">
                <v-btn large color="primary" onclick="submit_form()" dark>جست و جو</v-btn>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
    <panel-table :headers="{{ json_encode($table['headers']) }}" :items="{{json_encode($table['items'])}}"></panel-table>
    {{ $order->links() }}
    </x-panel-box>

</div>
@endsection
@section('footer')
    <script>
        function submit_form() {
            document.getElementById("form_search").submit();
        }
    </script>
@endsection
