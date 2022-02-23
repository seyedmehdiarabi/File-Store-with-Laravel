@extends('backend-theme::layout')

@section('content')

    <div>
        @include('backend-theme::breadcrumb',['data'=>[
             ['title'=>'مدیریت نقش های کاربری','url'=>url('userRole')],
             ['title'=>'افزودن نقش کاربری جدید','url'=>url('userRole/create')]
        ]])

        <?php $args=['title'=>'افزودن نقش کاربری جدید']; ?>

        <x-panel-box :args="$args">

            @include('users::roles._form',['type'=>'create'])

        </x-panel-box>

    </div>

@endsection
