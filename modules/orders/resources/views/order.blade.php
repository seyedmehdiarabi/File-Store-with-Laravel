@extends('front-theme::layouts.app')

@section('head')
    <title>سفارش : {{ $order->order_id }}</title>
@endsection

@section('content')

    <div class="bg-gray-50" style="min-height: 400px">
        <div class="custom-container pt-8 mb-16 pb-16">
            @php
            $columns=[
                ['label' => 'لینک دانلود', 'attr' => function ($model) {
                    $src = url('download').'?file='.$model['link'];
                    return '<a target="_blank" href="' . $src  . '">'.$model['title'].'</a>';
                }, 'html' => true],
                ['label'=>'هزینه','attr'=>function($model){
                    return replace_number(number_format($model['price'])).' تومان ';
                }],
            ];
            $table = site_table($columns,$order_data);
            @endphp
            <div class="col-12 p-top">
                <site-table :headers="{{ json_encode($table['headers']) }}" :items="{{json_encode($table['items'])}}" :cart="0"></site-table>
            </div>
        </div>
    </div>
@endsection
