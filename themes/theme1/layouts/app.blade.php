<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="app_url" content="{{ url(('/')) }}">
    <link rel="canonical" href="{{url('/')}}"/>
    @yield('seo')
    <title>{{ config('shop-info.shop_name') }}</title>
    @php add_css_file('desktop') @endphp
    @yield('head')
    <link href="{{ asset('css/fonts.css') }}" rel="stylesheet">
    <link href="{{asset('css/desktop.css?id='.time())}}" rel="stylesheet">
</head>
<body>
<div id="app">
    <v-app class="app-style">
        <progress-box ref="progress_box"></progress-box>
        <header-search :login="{{json_encode(Auth::user())}}"></header-search>
        <app-content>
            @yield('content')
        </app-content>

        @include('position_view',['name'=>'desktop_layout'])
        <div class="footer">
            <div class="content">
                <div class="custom-container my-10">
                    <div class="d-flex justify-space-between">
                        <div class="">
                            <span class="brand white--text">فروش فايل</span>
                        </div>
                        <div>
                            <v-btn class="ma-1" outlined icon fab small color="#dfdfdf"><v-icon>mdi-youtube</v-icon></v-btn>
                            <v-btn class="ma-1" outlined icon fab small color="#dfdfdf"><v-icon>mdi-facebook</v-icon></v-btn>
                            <v-btn class="ma-1" outlined icon fab small color="#dfdfdf"><v-icon>mdi-twitter</v-icon></v-btn>
                            <v-btn class="ma-1" outlined icon fab small color="#dfdfdf"><v-icon>mdi-instagram</v-icon></v-btn>
                            <v-btn class="ma-1" outlined icon fab small color="#dfdfdf"><v-icon>mdi-telegram</v-icon></v-btn>
                        </div>
                    </div>
                    <v-row class="mt-4">
                        <div class="col-lg-4 align-justify description">
                            <h3 class="mb-3">درباره فروش فايل</h3>
                            <span>فروش فايل یکی از پرتلاش‌ترین و بروزترین وبسایت های آموزشی در سطح ایران است که همیشه تلاش کرده تا بتواند جدیدترین و بروزترین مقالات و دوره‌های آموزشی را در اختیار علاقه‌مندان ایرانی قرار دهد. تبدیل کردن برنامه نویسان ایرانی به بهترین برنامه نویسان جهان هدف ماست.</span>
                        </div>
                        <div class="col-lg-4 description">
                            <div class="mx-15">
                                <h3 class="mb-3">بخش های سايت</h3>
                                <ul>
                                    <li class="mb-3 mr-8">قوانین و مفررات</li>
                                    <li class="mb-3 mr-8">مدرسان</li>
                                    <li class="mb-3 mr-8">درباره ما</li>
                                    <li class="mb-3 mr-8">ارتباط با ما</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-4 description">
                            <div class="">
                                <h3 class="mb-3">ارتباط با من</h3>
                                <div class="d-flex justify-space-between mb-3">
                                    <span>
                                        <v-icon>mdi-email-outline</v-icon>
                                        ایمیل :
                                    </span>
                                    <a href="mailto:moeinrahmani951@gmail.com">moeinrahmani951@gmail.com</a>
                                </div>
                                <div class="d-flex justify-space-between mb-3">
                                    <span>
                                        <v-icon>mdi-telegram</v-icon>
                                        آیدی تلگرام :
                                    </span>
                                    <a dir="ltr" href="https://t.me/m0einrahmani">@m0einrahmani</a>
                                </div>
                                <div class="d-flex justify-space-between mb-3">
                                    <span>
                                        <v-icon>mdi-phone-outline</v-icon>
                                        شماره تماس :
                                    </span>
                                    <a href="tel:+989158363915">{{replace_number('09158363915')}}</a>
                                </div>
                            </div>
                        </div>
                    </v-row>
                </div>
            </div>
        </div>
    </v-app>
</div>
<?php registerVueFile('', 'ssr', config('cms.develop')) ?>
</body>
</html>
