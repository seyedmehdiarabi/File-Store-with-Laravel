@if(!empty($products) && sizeof($products)>0)
    <div class="product_box custom-container pt-8 pb-16">
        <div class="header-box">
            <a href="#" class="text-2xl font-bold text-gray-600 block">جدیدترین دوره‌ها</a>
            <a href="{{url('/category')}}" class="font-medium hover:no-underline underline router-link">مشاهده همه دوره‌ها</a>
        </div>
        <v-slide-group
            multiple
            show-arrows
        >
            @foreach($products as $key=>$product)
                <v-slide-item class="bg-white mt-6 mx-2 rounded-lg" :key="{{ $key }}">
                    <v-card height="450" width="288" class="router-link product-list"
                            {{--                            data-component="product-page-skeleton"--}}
                            href="{{ url('product/'.$product->slug) }}">
                        <div class="ma-3 align-justify">
                            <div class="product_img_div">
                                <img class="rounded-lg" src="{{ asset('files/products/'.$product->pic) }}">
                            </div>
                            <div class="px-2">
                                <h3 class="my-3">
                                    @if(strlen(strip_tags($product->title,60))>60)
                                        {!! mb_substr(strip_tags($product->title),0,60).'...' !!}
                                    @else
                                        {!! $product->title !!}
                                    @endif
                                </h3>
                                <div class="mt-4 mb-3">
                                    @if(strlen(strip_tags($product->text,200))>200)
                                        {!! mb_substr(strip_tags($product->text),0,200).'...' !!}
                                    @else
                                        {!! $product->text !!}
                                    @endif
                                </div>
                            </div>
                            <div class="bg-gray-50 text-gray-600 rounded-lg py-4 px-2 " style="position:absolute; bottom: 6px; width: 265px">
                                <div class="d-flex  justify-between align-center">
                                    <div class="d-flex flex-row-reverse">
                                        <div class="d-flex align-center">
                                            <v-icon>mdi-clock-outline</v-icon>
                                            <span class="mr-2 mt-1">{{replace_number($product->download_times)}}</span>
                                        </div>
                                    </div>
                                    <span class=" text-green-600 font-bold text-tiny">
                                            @if($product->price>0)
                                            {{replace_number(number_format($product->price))}} تومان
                                            @elseif($product->price==0)
                                            <span style="margin-right: 45px">رایگان</span>
                                            @endif
                                        </span>
                                </div>
                            </div>
                        </div>
                    </v-card>
                </v-slide-item>
            @endforeach
        </v-slide-group>

    </div>
@endif




