@extends('backend-theme::layout')

@section('content')

   <div>

       @include('backend-theme::breadcrumb',['data'=>[
            ['title'=>'مدیریت سفارشات','url'=>url('admin/orders')],
            ['title'=>'جزییات سفارش','url'=>url('admin/orders/'.$order->id)],
       ]])

       @includeIf('orders::order-detail')

   </div>

@endsection


