<?php
$has_cart = false;
if (file_exists(base_path('modules/cart/Models/Cart.php'))) {
    $has_cart = true;
    $productCart = \Modules\cart\Models\Cart::get_product_count();
}
?>
<mobile-app-bar shop_name="{{config('shop-info.shop_name')}}">
    <template v-slot:cat_list>
        @include('front-theme::views.mobile.catList')
    </template>

    <template v-slot:items>
        @if($has_cart)
            <a href="{{url('Cart')}}" class="router-link">
                <v-badge
                content="{{$productCart > 0 ? replace_number($productCart) : ''}}"
                color="red"
                overlap
                >
                    <v-icon>mdi-cart-outline</v-icon>
                </v-badge>
            </a>
        @endif
            <a href="{{url('/user/profile')}}" class="router-link">
                <v-icon>mdi-account</v-icon>
            </a>
    </template>
</mobile-app-bar>
