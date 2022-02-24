<?php
$detect = new \App\Lib\Mobile_Detect();
?>
@if($detect->isMobile() || $detect->isTablet())
    <?php $layout = 'mobile-app'; ?>
@else
    <?php $layout = 'app'; ?>
@endif
@extends('front-theme::layouts.'.$layout)
@section('content')
    <section class="error_pages">
        <div class="error_content">
            <img class="" src="{{asset('files/images/404.svg')}}" alt="">
            <v-alert
                elevation="1"
                type="error"
                class="mt-6"
                border="left"
                color="pink darken-1"
            >
                متاسفانه صفحه‌ای که به دنبال آن بودید پیدا نشد!
            </v-alert>
        </div>
    </section>
@endsection
