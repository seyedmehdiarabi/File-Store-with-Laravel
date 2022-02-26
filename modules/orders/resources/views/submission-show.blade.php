@extends('backend-theme::layout')

@section('content')

   <div>

       @include('backend-theme::breadcrumb',['data'=>[
          ['title'=>'مدیریت مرسوله ها','url'=>url('admin/orders/submissions')],
          ['title'=>'جزییات مرسوله','url'=>url('admin/orders/submissions/'.$submission[0]->id)]
       ]])

       @includeIf('orders::order-submission')

   </div>

@endsection


