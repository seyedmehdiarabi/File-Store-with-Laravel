@extends('backend-theme::layout')
<style>
    .erorr_box{
        width: 35%;
        margin: auto;
    }
    .col-12 h6{
        text-align: center;
        font-weight: 900;
        font-size: 24px;
        color: #bc08cd !important;
        line-height: 35px;
        display: block !important;
        width: 100%;
    }
</style>
@section('content')
     <div class="panel">
             @include('backend-theme::breadcrumb')
         <div class="container">
             <div class="row">
                 <div class="col-12">
                     <div class="erorr_box">
                         <img src="{{asset('files/images/403.svg')}}" alt="">
                     </div>
                 </div>
                 <div class="col-12">
                     <h6>شما دسترسی لازم به این صفحه را ندارید!</h6>
                 </div>
             </div>
         </div>
     </div>
@endsection
