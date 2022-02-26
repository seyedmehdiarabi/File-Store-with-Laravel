@extends('backend-theme::layout')
@section('content')
    <div>
            @include('backend-theme::breadcrumb',['data'=>[
         ['title'=>'مدیریت کد های تخفیف','url'=>url('admin/discount')],
         ['title'=>'افزودن کد تخفیف','url'=>url('admin/discount/create')]
    ]])
        <?php
        $args=[];
        $args['title']='افزودن کد تخفیف';
        $args['route']='admin/discount';
        ?>
        <x-panel-box :args="$args">
            @include('discount::_form',['type'=>'create'])
        </x-panel-box>
    </div>

@endsection


