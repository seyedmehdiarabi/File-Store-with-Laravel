<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="app_url" content="{{ url(('/')) }}">
    <link href="{{ asset('css/vuetify-font.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@4.x/css/materialdesignicons.min.css" rel="stylesheet">
    <link href="{{ asset('css/vuetify.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/auth.css') }}" rel="stylesheet">
    @yield('footer')
    {{ add_css_file('') }}
    @yield('header')
</head>
<body>
<div id="app">
    <v-app class="app-style">
        <progress-box ref="progress_box"></progress-box>
        <v-main>
            <app-content class="">
                <div class="">
                    @yield('content')
                </div>
            </app-content>
        </v-main>
    </v-app>
</div>

<script src="<?= asset('js/vue-develop.js') ?>?" type="text/javascript"></script>
<script>
    setTimeout(function () {
        const app = new Vue({
            el: '#app',
            store: store,
            vuetify: new Vuetify({rtl: true})
        });
        window.vm = app;
    }, 5);
</script>
<script src='https://www.google.com/recaptcha/api.js?hl=fa' async defer></script>
</body>
</html>
