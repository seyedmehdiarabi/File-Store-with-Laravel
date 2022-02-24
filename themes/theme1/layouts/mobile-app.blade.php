<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="app_url" content="{{ url(('/')) }}">
    <meta name="theme-color" content="{{config('shop-info.theme-color')}}"/>
    <title>{{ config('shop-info.shop_name') }}</title>
    @php add_css_file('desktop') @endphp
    @yield('seo')
    <link href="{{ asset('themes/theme1/assets/css/mobile.css?id='.time()) }}" rel="stylesheet"/>
    <link href="{{ asset('themes/theme1/assets/css/main.css?id='.time()) }}" rel="stylesheet"/>
    <link href="{{ asset('css/widgets.css?id='.config('cms.widgetId')) }}" rel="stylesheet">
    <link href="{{ asset('css/fonts.css') }}" rel="stylesheet">
    <meta name="theme-color" content="#B3E5FC"/>
</head>
<body>

<div id="app">

    <v-app class="app-style">
        @includeIf('front-theme::include.mobile-app-bar')
        <mobile-progress-box></mobile-progress-box>
        <v-main>
            <app-content>
                @yield('content')
            </app-content>
            @include('position_view',['name'=>'mobile_layout'])
        </v-main>
    </v-app>
</div>


<?php registerVueFile('', 'ssr', config('cms.develop')) ?>
</body>
</html>
