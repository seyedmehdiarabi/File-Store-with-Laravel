<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="app_url" content="{{ url(('/')) }}">
    <title>پنل مدیریت</title>
    {{ add_css_file('desktop') }}
    <link href="{{ asset('themes/AdminPanel/assets/style.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/fonts.css') }}" rel="stylesheet" />
    @yield('head')

</head>
<body>

<div id="app">

    <v-app class="app-style">

        <?php
           $sideBarMenu=Config::get('app.panel_menu');
           $access=isset($access) ? json_decode($access) : null;
           $sideBarMenu=getAccessMenu($sideBarMenu,$access);
           ksort($sideBarMenu);
        ?>
        <drawer :items="{{ json_encode($sideBarMenu) }}"></drawer>
        <v-main>
            <panel-app-bar></panel-app-bar>
            <div class="panel-content">
                <app-content >
                    @yield('content')
                </app-content>
            </div>
            <response-dialog></response-dialog>
        </v-main>
    </v-app>

</div>

<?php registerVueFile('','ssr',config('cms.develop')) ?>

</body>
</html>
