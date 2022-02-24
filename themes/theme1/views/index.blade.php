@extends('front-theme::layouts.app')
@section('content')
    <div class="test"></div>
@endsection
@section('seo')
    <meta name="description" content="{{ config('shop-info.description') }}"/>
    <meta name="keywords" content="{{ config('shop-info.keywords') }}"/>
    <meta property="og:site_name" content="{{ config('shop-info.shop_name') }}"/>
    <meta property="og:description" content="{{ config('shop-info.description') }}"/>
    <meta property="og:title" content="{{ config('shop-info.shop_name') }}"/>
    <meta property="og:locale" content="fa_IR"/>
@endsection

