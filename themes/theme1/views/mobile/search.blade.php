@extends('front-theme::layouts.mobile-app')

@section('content')

    <mobile-filter-list
        cat_id="{{ isset($category) ? $category->id : 0  }}"
        max_price="{{ $result['max_price'] }}"
        cat_name="{{ isset($category) ? $category->name : ''  }}"
    >

        <template v-slot:layout="{product}">
            <mobile-product-box :product="product" product_url="{{ shop_product_url_theme() }}">
                <template v-slot:begin-search-product-box>
                    @include('position_view',['name'=>'begin_search_product_box','args'=>['show'=>'vertical']])
                </template>
            </mobile-product-box>
        </template>

        <template v-slot:product-list>
            @foreach($result['products'] as $product)
                <div class="product_div">
                    @include('position_view',['name'=>'begin_search_product_box','args'=>['show'=>'vertical']])
                    <div class="product_info_div">
                        <div class="image_box">
                            @if($product['status']==1 && $product->firstProductPrice!=null)
                                @if($product->firstProductPrice['price1']!=$product->firstProductPrice['price2'])
                                    <?php
                                    $d = getDiscountValue($product->firstProductPrice['price1'], $product->firstProductPrice['price2']);
                                    ?>
                                    <div class="discount-badge">
                                        {{ '٪'.replace_number($d) }}
                                    </div>
                                @endif
                            @endif
                            <img class="{{ $product['status']==-1 ? 'stop-production-img' : '' }}"
                                 src="{{ url('files/thumbnails/'.$product->image_url) }}"/>
                        </div>
                        <div class="product-info">
                            <a class="router-link" href="{{ shop_product_url($product) }}">
                                <p class="product-title">{{ $product->title }}</p>
                            </a>

                            @if($product['status']==1 && $product->firstProductPrice!=null)
                                <div class="price">
                                    <div class="discount_div">
                                        @if($product->firstProductPrice['price1']!=$product->firstProductPrice['price2'])
                                            <del>
                                                {{ replace_number(number_format($product->firstProductPrice['price1'])) }}
                                            </del>

                                        @endif

                                    </div>

                                    <span>{{ replace_number(number_format($product->firstProductPrice['price2'])) }} تومان</span>
                                </div>
                            @else
                                <div class="product_status">
                                    <div>
                                        <p class="line"></p>
                                        @if($product['status']==0)
                                            <span>ناموجود</span>
                                        @elseif($product['status']==-1)
                                            <span>توقف تولید</span>
                                        @else
                                            <span>ناموجود</span>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </template>
    </mobile-filter-list>
@endsection
