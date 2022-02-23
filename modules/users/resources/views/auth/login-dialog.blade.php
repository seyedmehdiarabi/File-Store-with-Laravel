<login-box>
    <template v-slot:before-login-form>
        <a href="{{ url('') }}">
            <img  src="{{ asset(config('shop-info.shop_icon')) }}" class="shop_logo">
        </a>
    </template>
</login-box>
