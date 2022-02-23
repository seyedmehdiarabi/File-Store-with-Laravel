@extends('backend-theme::layout')
@section('content')
    <div>
        @include('backend-theme::breadcrumb',['data'=>[
                    ['title'=>'مدیریت کاربران','url'=>url('users')],
                    ['title'=>'تکمیل اطلاعات کاربری','url'=>url('users/'.$id.'/additional_info')]
                    ]])

        @include('position_view',['name'=>'user_panel_index','type'=>'content'])
    </div>
@endsection
