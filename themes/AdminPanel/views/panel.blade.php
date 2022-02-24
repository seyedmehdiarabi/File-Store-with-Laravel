@extends('backend-theme::layout')
@section('content')
    <section class="desktop">
        <div class="row mb-3">
            <div class="col-12 col-lg-4 col-xl-3">
                <div class="row">
                    <div class="col-12">
                        <a href="{{ url('admin/orders') }}">
                            <div class="statistic_admin order_statistic">
                                <i class="fa fa-list-ul"></i>
                                <span> سفارشات رسیده :  {{ replace_number($order_count) }} مورد </span>
                                <span class="d-block text-center">{{get_price($order_total)}}</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-12 ">
                        <a href="{{ url('admin/custom_payment')  }}">
                            <div class="statistic_admin payment_statistic">
                                <i class="fa fa-dollar"></i>
                                <span> واریزی های دلخواه : {{replace_number($custom_payments_count)}} مورد  </span>
                                <span class="d-block text-center">{{get_price($custom_payments_total)}}</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-6 ">
                        <a href="{{ url('admin/users')  }}">
                            <div class="statistic_admin user_statistic">
                                <i class="fa fa-users"></i>
                                <span> کاربران کل  </span>
                                <span class="text-center d-block"> {{ replace_number($user_count) }}<span>نفر</span></span>
                            </div>
                        </a>
                    </div>
                    <div class="col-6 ">
                        <a href="{{ url('admin/contact_us')  }}">
                            <div class="statistic_admin message_statistic">
                                <i class="fa fa-envelope"></i>
                                <span> نامه ها   </span>
                                <span class="text-center d-block"> {{ replace_number($contactus_count) }} <span>مورد</span> </span>
                            </div>
                        </a>
                    </div>
                    <div class="col-12 ">
                        <a href="{{ url('admin/wallet/request_money')  }}">
                            <div class="statistic_admin money_statistic">
                                <i class="fa fa-money"></i>
                                <span> درخواست‌های برداشت وجه </span>
                                <span class="text-center d-block"> {{ get_price($request_money) }} <span>تومان</span> </span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-8 col-xl-9">

            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="panel">
                    <div class="panel_content submission_box">
                        <table class="table">
                            <tr>
                                <td>
                                    <img src="{{ asset('files/icons/step1.svg') }}" style="width: 60px">
                                    کل مرسوله ها
                                </td>
                                <td>
                                    {{ replace_number($submissions) }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <img src="{{ asset('files/icons/step1.svg') }}" style="width: 60px">
                                    مرسوله های تایید شده
                                </td>
                                <td>
                                    {{ replace_number($submissions_approved) }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <img src="{{ asset('files/icons/step2.svg') }}" style="width: 60px">
                                    مرسوله های ارسالی امروز
                                </td>
                                <td>
                                    {{ replace_number($items_today) }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <img src="{{ asset('files/icons/step3.svg') }}" style="width: 60px">
                                    مرسوله های آماده ارسال
                                </td>
                                <td>
                                    {{ replace_number($submissions_ready) }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <img src="{{ asset('files/icons/step4.svg') }}" style="width: 60px">
                                    مرسوله های ارسال شده به پست
                                </td>
                                <td>
                                    {{ replace_number($posting_send) }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <img src="{{ asset('files/icons/step5.svg') }}" style="width: 60px">
                                    مرسوله های آماده دریافت از پست
                                </td>
                                <td>
                                    {{ replace_number($posting_receive) }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <img src="{{ asset('files/icons/step6.svg') }}" style="width: 60px">
                                    مرسوله های تحویل داده شده
                                </td>
                                <td>
                                    {{ replace_number($delivered) }}
                                </td>
                            </tr>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

