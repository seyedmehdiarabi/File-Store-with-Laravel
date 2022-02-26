@extends('front-theme::layouts.app')

@section('header')
    <title>{{$product->title}}</title>
@endsection
@section('content')
    <section class="bg-gray-50">
        <div class="custom-container py-1 align-center">
            <div class="my-7">
                <h1 class="title_p">{{$product->title}}</h1>
            </div>
            <div class="row">
                <div class="col-12 col-sm-8 col-lg-9 padding-0 ">
                    <div class="box_shadow_product h-fit-content rounded-lg bg-white border-p">
                        <div class="px-4 pt-2 pb-4 border-bottom-01">
                            <div class="pt-4 pr-3 bold">
                                <span class="px-2 pb-3 border-bottom-yellow">توضیحات</span>
                                <span class="px-2 mr-5 pb-3 cursor-pointer"
                                      id="go_to_comments_body">دیدگاه و پرسش</span>
                            </div>
                        </div>
                        <div class="pa-5 align-justify" style="line-height: 2">
                            {!! $product->text !!}
                        </div>
                        <v-divider></v-divider>
                        <div class="d-flex justify-between pa-5">
                            <div class="d-flex">
                                <v-icon>mdi-arrow-left</v-icon>
                                <v-icon>mdi-arrow-left</v-icon>
                                <v-icon>mdi-arrow-left</v-icon>
                            </div>
                            <div class="d-flex">
                                <v-icon>mdi-arrow-left</v-icon>
                                <v-icon>mdi-arrow-left</v-icon>
                            </div>
                        </div>
                    </div>
                    <comment-product :product_id="{{$product->id}}"
                                     :auth="{{json_encode(Auth::user())}}"></comment-product>
                </div>
                <div class="col-12 col-sm-4 col-lg-3 pr-4 padding-0">
                    <div class="gradient_dark_blue rounded-lg">
                        <div class="pattern_1 pa-4 white--text">
                            <div class="d-flex justify-between">
                                <span>قیمت دوره</span>
                                <span>
                                    @if($product->price>0)
                                        {{ replace_number(number_format($product->price)) }} تومان
                                    @else
                                        رایگان
                                    @endif
                                </span>
                            </div>
                            <div class="d-flex justify-between mt-5">
                                <span>وضعیت دوره</span>
                                <span class="text-gray-700 bg-yellow-400 rounded px-1 d-flex align-center">
                                    @if($product->status==0)
                                        در حال ضبط
                                    @elseif($product->status==1)
                                        تکمیل ضبط
                                    @endif
                                </span>
                            </div>
                            <div class="d-flex justify-between mt-5">
                                <span>زمان کل دوره</span>
                                <span>{{ replace_number($product->download_times) }}</span>
                            </div>
                            <div class="d-flex justify-between mt-5">
                                <span>تعداد قسمت‌ها</span>
                                <span>{{ replace_number($product->download_number) }}</span>
                            </div>
                            <div class="d-flex justify-between mt-5">
                                <span>تعداد بازدید</span>
                                <span>{{ replace_number($product->view) }}</span>
                            </div>
                            <v-divider class="my-3 white"></v-divider>
                            <input type="hidden" id="value_input" value="{{$product->id}}">
                            <button id="add_cart" value="{{$product->id}}"
                                    class="bg-white text-gray-700 rounded-lg pa-4 text-center bold width-100">
                                @if($product->price>0)
                                    خریداری این دوره
                                @else
                                    این دوره رایگان است
                                @endif
                            </button>
                            <div class="text-gray-700 bg-yellow-400 rounded-lg mt-2 pa-2 font-size-14 align-justify">
                                @if($product->price>0)
                                    این دوره رایگان  نیست ، تنها به شکل <strong> نقدی </strong> می‌توانید این دوره را
                                    تهیه کنید.
                                @else

                                    تا زمانی که این دوره <strong> رایگان </strong> باشد، شما بصورت کامل به این دوره
                                    دسترسی دارید.
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="rounded-lg border-p mt-4 box_shadow_product">
                        <div class="bg-gray-200 pattern_1 pa-4 d-flex align-center">
                            <img class="w-20 h-20 ml-5 rounded-circle border_img bg-gray-300"
                                 src="{{asset('files/icons/default_user.png')}}"
                                 alt="عکس نمایه شخصی">
                            <div>
                                <span class="bold">مدرس دوره</span>
                                <span class="d-block mt-1"></span>
                            </div>
                        </div>
                        <div class="pa-4 align-justify font-size-14">
                            اول داستان، طراح گرافیک بودم و ۲ سالی به عنوان طراح مشغول بودم، بعد به برنامه‌نویسی علاقمند
                            شدم و الان بیشتر از ۱۰ ساله که عاشق کدزنی و چالش‌های پروژه‌های مختلفم. به تدریس علاقه خاصی
                            دارم و دوست دارم دانشی که در این راه بدست آوردم را در اختیار دیگران هم قرار بدم.
                        </div>
                        <a class="d-flex justify-between bg-gray-600 rounded-b-lg white--text pa-4" href="#">
                            مشاهده پروفایل
                            <v-icon class="white--text">mdi-arrow-left</v-icon>
                        </a>
                    </div>
                    @if(count(\Modules\products\Models\Product::all())>1)
                        <div class="rounded-lg border-p mt-4 box_shadow_product py-6">
                            <div class="pb-4 mx-4 border-bottom-01">
                                <span class="bold">دوره‌های پیشنهادی</span>
                            </div>
                            <div class="px-4">
                                @php
                                    $random_product=\Modules\products\Models\Product::all()->random()->get()->except($product->id);
                                @endphp
                                @foreach($random_product as $key=>$value)
                                    <div class="my-4 d-flex justify-between align-justify">
                                        <div class="d-flex align-center">
                                            <img class="w-20 h-20 rounded-circle border_img bg-gray-300"
                                                 src="{{ asset('files/products/'.$value->pic) }}" alt="عکس دوره">
                                        </div>
                                        <div>
                                            <h4><a class="router-link tag_a_color bold font-size-14"
                                                   href="{{ url('product/'.$value->slug) }}">{{$value->title}}</a></h4>
                                            <div class="mt-4 mb-3 font-size-12 px-2">
                                                @if(strlen($value->text)>150)
                                                    {!! mb_substr($value->text,0,150).' ... ' !!}
                                                @else
                                                    {!! $value->text !!}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @if($key!==(sizeof($random_product)-1))
                                        <v-divider></v-divider>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection

