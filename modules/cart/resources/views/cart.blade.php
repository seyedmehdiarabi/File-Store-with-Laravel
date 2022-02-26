@extends('front-theme::layouts.app')
@section('header')
    <title>سبد خرید</title>
@endsection
@section('content')
    <div class="bg-gray-50">
        <div class="custom-container pt-8 mb-16 pb-16">
            @if(\Illuminate\Support\Facades\Session::has('cart'))
                @php
                    $cart_price=0;
                    $total_price=0;
                @endphp
                @foreach(\Illuminate\Support\Facades\Session::get('cart') as $key=>$value)
                    @php
                        $products[]=\App\Models\Product::findOrFail($key);
                        $single_product=\App\Models\Product::findOrFail($key);
                        $price=$single_product->price;
                        $total_price+=$price;
                        $cart_price+=$price;
                        \Illuminate\Support\Facades\Session::put('total_price',$total_price);
                    @endphp
                @endforeach
                @php
                    $discount=\Illuminate\Support\Facades\Session::get('discount');
                    if ($discount){
                        $cart_price=$cart_price-(($discount*$cart_price)/100);
                        \Illuminate\Support\Facades\Session::put('cart_price',$cart_price);
                    }else{
                        \Illuminate\Support\Facades\Session::put('cart_price',$cart_price);
                    }
                    $columns=[
                        ['label' => 'تصویر محصول', 'attr' => function ($model) {
                            $src = url('files/products/'. $model->pic);
                            return '<a class="router-link" href="' . url('product/'.$model->slug)  . '"><img src="' . $src . '" class="product_pic"></a>';
                        }, 'html' => true],
                        ['label'=>'نام محصول','attr'=>'title'],
                        ['label'=>'هزینه','attr'=>function($model){
                            return replace_number(number_format($model->price)).' تومان ';
                        }],
                    ];
                    $table = site_table($columns,$products,true);
                @endphp
                <div class="row ">
                    <div class="col-12 col-sm-8 col-lg-9 p-top">
                        <site-table  :headers="{{ json_encode($table['headers']) }}" :items="{{json_encode($table['items'])}}" :cart="1"></site-table>
                    </div>
                    <div class="col-12 col-sm-4 col-lg-3 pa-4 padding-0 box_shadow_product border-p bg-white rounded-lg h-fit-content">
                        <div class="rounded-lg">
                            <div class="d-flex justify-between">
                                <span>هزينه كل</span>
                                <span>{{ replace_number(number_format($total_price)) }} تومان </span>
                            </div>
                            @if($discount)
                                <div class="d-flex justify-between mt-5 red--text">
                                    <span>تخفیف</span>
                                    <span>{{ replace_number(number_format($total_price-$cart_price)) }} تومان </span>
                                </div>
                                <v-divider class="mt-5"></v-divider>
                            @endif
                            <div class="d-flex justify-between mt-5">
                                <span>مبلغ قابل پرداخت</span>
                                <span>{{ replace_number(number_format($cart_price)) }} تومان </span>
                            </div>
                            <v-divider class="my-5"></v-divider>
                        @if(\Illuminate\Support\Facades\Auth::check())
                                <a href="{{url('/add_order')}}">
                                    <v-btn class="light-green darken-1 white--text" x-large block>خرید</v-btn>
                                </a>
                            @else
                                <span>برای ادامه روند ثبت سفارش لطفا <a href="{{url('/login-register')}}">وارد</a> سایت شوید!</span>
                            @endif
                        </div>
                    </div>
                </div>

            @else
                <div class="">
                    <span>سبد خريد شما خاليست!</span>
                </div>
            @endif
        </div>
    </div>
@endsection
