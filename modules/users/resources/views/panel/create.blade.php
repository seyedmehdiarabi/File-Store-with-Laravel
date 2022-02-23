@extends('backend-theme::layout')

@section('content')

    <div>

        @include('backend-theme::breadcrumb',['data'=>[
            ['title'=>'مدیریت کاربران','url'=>url('users')],
            ['title'=>'افزودن کاربر جدید','url'=>url('users/create')]
        ]])

        <?php
            $args=[];
            $args['title']='افزودن کاربر جدید';
        ?>

        <x-panel-box :args="$args">

            @include('users::panel._form',['type'=>'create'])

        </x-panel-box>

    </div>

@endsection
