@extends("users::auth.layouts.$layout")

@section('content')

    <auth-register-box shom_name="{{config('shop-info.shop_name')}}">
        <template v-slot:before-register-form>
            <a href="{{ url('') }}">
                <img  src="{{ asset(config('shop-info.shop_icon')) }}" class="shop_logo">
            </a>
        </template>
    </auth-register-box>
@endsection
