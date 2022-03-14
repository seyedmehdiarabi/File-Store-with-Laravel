@extends('front-theme::layouts.app')

@section('content')
    <section class="bg-gray-50">
        <div class="custom-container py-1 align-center" style="min-height: 400px">
            <div class="custom-container py-1 align-center">
                <div class="row mt-4">
                    <div class="col-12 col-sm-4 col-lg-3">
                        <list-item-profile :user="{{ json_encode($user) }}"></list-item-profile>
                    </div>
                    <div class="col-12 col-sm-8 col-lg-9">
                        <details-profile :user="{{json_encode($user)}}"></details-profile>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

