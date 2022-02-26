@extends('backend-theme::layout')

@section('content')

    <div>

        @include('backend-theme::breadcrumb',['data'=>[
               ['title'=>'مدیریت دسته ها','url'=>url('admin/category')],
              ['title'=>'افزودن دسته جدید','url'=>url('admin/category/create')]
        ]])

        <?php
        $args ['title'] = 'افزودن دسته جدید';
        ?>

        <x-panel-box :args="$args">
            @include('categories::_form',['type'=>'create'])
        </x-panel-box>

    </div>

@endsection
