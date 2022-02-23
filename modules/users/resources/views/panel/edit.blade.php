@extends('backend-theme::layout')

@section('content')

    <div>

        @include('backend-theme::breadcrumb',['data'=>[
            ['title'=>'مدیریت کاربران','url'=>url('users')],
            ['title'=>'ویرایش اطلاعات کاربر','url'=>url('users/'.$user->id.'/edit')]
        ]])

        <?php
        $args=[];
        $args['title']='ویرایش اطلاعات کاربر - '.replace_number(e($user->mobile));
        ?>


        <x-panel-box :args="$args">

            @include('users::panel._form',['type'=>'edit'])

        </x-panel-box>


    </div>

@endsection
