@extends('backend-theme::layout')

@section('content')

    @include('backend-theme::breadcrumb',['data'=>[
         ['title'=>'تنظیمات ارسال پیامک به کاربران','url'=>url('user/sms/channel')],
    ]])

    <?php
       $args=[];
       $args['title']='تنظیمات ارسال پیامک به کاربران';
    ?>

    <x-panel-box :args="$args">

        <?php
            $option=['url' => 'user/sms/channel'];
            $form=new \App\Lib\FormBuilder($option);
        ?>

        <?php

        $form->select(
            $channelList,
            'users-channel',
            'انتخاب وب سرویس : ',
            ['class'=>'selectpicker auto_width'],
            false,
            $data['users-channel']
        );

        ?>

        <?php $form->textInput('users-api-key','ای پی ای : ',['class'=>'form-control'],$data['users-api-key']); ?>

        <?php $form->textInput('users-line_number','شماره خط : ',['class'=>'form-control'],$data['users-line_number']); ?>


        <?php $form->textInput('users-active_template','قالب ارسال کد فعال سازی : ',['class'=>'form-control'],$data['users-active_template']); ?>

        <?php $form->btn('ثبت اطلاعات', 'create'); ?>

        <?php $form->close(); ?>

    </x-panel-box>

@endsection
