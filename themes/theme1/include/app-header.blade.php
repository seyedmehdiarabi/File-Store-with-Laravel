<header>
    <div class="header_top">
        <div class="container">
            <div class="header_right float-right">
                <a href="tel:{{config('shop-info.phone')}}">
                    <i class="fa fa-phone"></i>
                    <span>شماره تماس : {{replace_number(config('shop-info.phone'))}}</span>
                </a>
            </div>
            <div class="header_left float-left">
                @if(Auth::check())
                    <span id="index_auth_div">
                            {{ Auth::user()->name }}
                        </span>
                @else
                    <a href="{{url('login')}}">ورود / ثبت‌نام</a>
                @endif
                <div id="user-btn"></div>
            </div>
        </div>
    </div>

    <v-container>

        <div class="header_middle">

            <div class="header-index">
                <div class="">
                    <a href="{{url('/')}}">
                        <div class="brand">
                            <img src="{{config('shop-info.shop_icon')}}" alt="" class="shop-logo-index">
                            <span>{{config('shop-info.shop_name')}}</span>
                        </div>
                    </a>
                </div>
                <div class="">
                    <header-search></header-search>
                </div>
                <div class="">
                    <div class="header_action">
                        <auth-menu
                            login="{{Auth::check() ? 'yes' : 'no'}}"
                            role_id="{{Auth::check() ? Auth::user()->role_id : 0}}"
                            role="{{Auth::check() ? Auth::user()->role : 0}}"
                            shop_name="{{config('shop-info.shop_name')}}"
                        >

                        </auth-menu>
                    </div>
                </div>
            </div>

        </div>
        <div class="header_bottom">

{{--
            @include('front-theme::views.CategoryList',['catList'=>$catList])
--}}

        </div>

    </v-container>


</header>
