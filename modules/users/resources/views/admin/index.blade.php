@extends('backend-theme::layout')

@section('content')

    <div>

        @include('backend-theme::breadcrumb')

            @include('position_view',['name'=>'admin_panel_index'])


    </div>

@endsection


