@extends('backend-theme::layout')
@section('content')
    <div class="panel">
            @include('backend-theme::breadcrumb')
        <div class="alert alert-info">
            <p style="margin-top:40px;text-align:center;padding-bottom:20px;color:red">
                <i class="fa fa-flag"></i>
                <span>{{ Auth::user()->name }} عزیز </span>
                به پنل مدیریت
                {{ config('shop-info.brand') }}
                خوش آمدید
                <i class="fa fa-flag"></i>
            </p>
        </div>
    </div>
@endsection
