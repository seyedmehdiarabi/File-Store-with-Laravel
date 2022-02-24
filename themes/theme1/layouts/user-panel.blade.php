@extends('front-theme::layouts.app')

@section('content')

    <div class="row">

        <div class="col-3">
            @include('user_panel_menu')
        </div>

        <div class="col-9" style="padding-right: 0">

            <div class="profile_content">
                @yield('panel-content')
            </div>

        </div>

    </div>

@endsection
