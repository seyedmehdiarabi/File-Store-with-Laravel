@extends('front-theme::layouts.app')

@section('content')

    <section class="bg-gray-50">
        <div class="custom-container py-1 align-center" style="min-height: 400px">
            <div class="custom-container py-1 align-center">
                <div class="row mt-4">
                    <div class="col-12 col-sm-4 col-lg-3">
                        <list-item-profile :user="{{ json_encode($user) }}"></list-item-profile>
                    </div>
                    <div class="col-12 col-sm-8 col-lg-9">
                        <div>
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
                                                return '<v-alert type="warning" style="margin:6px 0;" border="left" text>در انتظار پرداخت</v-alert>';
                                            }
                                            else if($value['status']=='ok'){
                                                return '<v-alert type="success" style="margin:6px 0;" border="left" text>پرداخت شده</v-alert>';
                                            }
                                            else if($value['status']=='canceled'){
                                                return '<v-alert type="error" style="margin:6px 0;" border="left" text>لغو شده</v-alert>';
                                            }
                                            else{
                                                return '<v-alert type="error" style="margin:6px 0;" border="left" text>خطا در اتصال به درگاه</v-alert>';
                                            }
                                        },
                                        'html'=>true
                                    ],
                                ];
                                $table = table_index($columns,$order,url('order'),'سفارش',
                                [
                                    function($value){
                                        $url=url('profile/order/'.$value->order_id);
                                        return '<a href="'.$url.'" class="router-link"><v-icon>mdi-eye-outline</v-icon></a> ';
                                    }
                                ],false,false);
                            @endphp
                            <site-table :headers="{{ json_encode($table['headers']) }}" :items="{{json_encode($table['items'])}}"
                                        :cart="0"></site-table>
                            {{ $order->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
