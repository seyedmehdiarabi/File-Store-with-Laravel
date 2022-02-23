@extends('backend-theme::layout')

@section('content')

    <div>

        @include('backend-theme::breadcrumb',['data'=>[
             ['title'=>'مدیریت کاربران','url'=>url('users')],
             ['title'=>'جزییات اطلاعات کاربر','url'=>url('users/'.$user->id)],
        ]])

        @includeIf('users::panel.user-detail')

        @includeIf('land::user-land')

    </div>

@endsection


