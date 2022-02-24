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

        @include('front-theme::include.app-header')

        <app-content>
            @yield('content')
        </app-content>

        @include('position_view',['name'=>'desktop_layout'])

    </v-app>
</div>
<?php registerVueFile('', 'ssr', config('cms.develop')) ?>
</body>
</html>
