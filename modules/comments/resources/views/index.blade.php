@extends('backend-theme::layout')

@section('header')
    <title>مدیریت کامنت‌ها</title>
@endsection

@section('content')
    <div>
        <div class="pishkhan">
            @include('backend-theme::breadcrumb',['data'=>[['title'=>'مدیریت کامنت‌ها','url'=>url('admin/comments')]]])
        </div>
        <comment-page :auth="{{json_encode(Auth::user())}}"></comment-page>
    </div>
@endsection
