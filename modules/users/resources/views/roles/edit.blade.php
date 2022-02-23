@extends('backend-theme::layout')

@section('content')


    <div>
        @include('backend-theme::breadcrumb',['data'=>[
           ['title'=>'مدیریت نقش های کاربری','url'=>url('userRole')],
           ['title'=>'ویرایش نقش کاربری','url'=>url('userRole/'.$userRole->id.'/edit')]
      ]])

        <?php $args = ['title' => 'ویرایش نقش کاربری -' . $userRole->name]; ?>

        <x-panel-box :args="$args">

            @include('users::roles._form',['type'=>'edit'])

        </x-panel-box>

    </div>

@endsection
