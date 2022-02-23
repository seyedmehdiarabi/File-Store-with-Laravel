@extends("users::auth.layouts.$layout")

@section('content')

    <auth-login-box type="admin-from">
        <template v-slot:before-login-form>
            <a href="{{ url('') }}">
                <img  src="{{ asset(config('shop-info.shop_icon')) }}" class="shop_logo">
            </a>
        </template>
    </auth-login-box>

@endsection
