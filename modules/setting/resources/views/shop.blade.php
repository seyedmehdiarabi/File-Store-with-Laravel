@extends('backend-theme::layout')

@section('content')

    <div>

        @include('backend-theme::breadcrumb',['data'=>[['title'=>'تنظیمات فروشگاه','url'=>url('setting/shop')],]])

        <?php
        $args = ['title' => 'تنظیمات فروشگاه'];
        ?>

        <x-panel-box :args="$args">

            <?php
            $option = ['url' => 'setting/shop'];
            $form = new \App\Lib\FormBuilder($option);
            ?>

            <?php $form->textInput('shop_name', 'عنوان فروشگاه', [], config('shop-info.shop_name')); ?>

            <?php $form->fileInput('shop_icon', 'آیکون فروشگاه', []); ?>

            <?php $form->textInput('login_url', 'آدرس ورود به پنل مدیریت', [], config('shop-info.login_url')); ?>

            <p class="message_text">برچسب ها با استفاده از (،) از هم جدا شود</p>

            <div style="padding-bottom: 15px">
                <?php $form->tagBox('keywords', 'برچسب ها', []); ?>
            </div>

            <?php $form->textarea(
                'description',
                'توضیحات مختصر در مورد فروشگاه (حداکثر 150 کاراکتر)',
                ['class' => 'total-width'],
                config('shop-info.description')
            ); ?>

            <?php $form->btn('ثبت اطلاعات', 'create'); ?>

            <?php $form->close(); ?>
        </x-panel-box>


    </div>
@endsection
