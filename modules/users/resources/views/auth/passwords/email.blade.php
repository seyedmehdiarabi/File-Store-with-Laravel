@extends("users::auth.layouts.$layout")

@section('content')
    <forget-password>
        <template v-slot:before-forget-form>
            <a href="{{ url('/') }}">
                <img  src="{{ asset(config('shop-info.shop_icon')) }}" class="shop_logo">
            </a>
        </template>
    </forget-password>
@endsection
