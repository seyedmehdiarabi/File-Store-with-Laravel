@extends('front-theme::layouts.app')
@section('content')
    <div>
        <section class="bg-gray-600 white--text">

            <div class="banner custom-container row py-12 align-center">
                <div class="r-banner col-12 col-sm-6">
                    <a href="/skills-chart" class="gray-color rounded-xl px-4 py-2">
                        شروع یادگیری برنامه‌نویسی
                        <v-icon>mdi-arrow-left</v-icon>
                    </a>
                    <h1 class="mt-6 mb-3">آموزش برنامه‌نویسی</h1>
                    <p class="text-justify text-lg">ما باور داریم که کاربران ایرانی لایق بهترین‌ها هستند، از این رو ما
                        تمام تلاش خود را می‌کنیم تا بتوانیم فیلم‌ها و مقالات آموزشی بروز و کاربردی را در اختیارتان قرار
                        دهیم تا با استفاده از آنها بتوانید جزء بهترین‌ها در صنعت طراحی و برنامه‌نویسی وب شوید. ما ادعا
                        نمی‌کنیم که بهترین هستیم ولی همیشه تمام تلاش خود را می‌کنیم بهترین عملکرد را به شما ارائه
                        دهیم.</p>
                </div>
                <div class="l-banner col-12 col-sm-6 justify-end">
                    <div class="text-center">
                        <img src="{{asset('files/icons/laravel_and_vue.svg')}}" alt="">
                    </div>
                </div>
            </div>
        </section>
        <section class="bg-gray-200">
            @include('front-theme::views.horizontal_product_list_view')
        </section>
    </div>
@endsection
@section('seo')
    <meta name="description" content="{{ config('shop-info.description') }}"/>
    <meta name="keywords" content="{{ config('shop-info.keywords') }}"/>
    <meta property="og:site_name" content="{{ config('shop-info.shop_name') }}"/>
    <meta property="og:description" content="{{ config('shop-info.description') }}"/>
    <meta property="og:title" content="{{ config('shop-info.shop_name') }}"/>
    <meta property="og:locale" content="fa_IR"/>
@endsection

