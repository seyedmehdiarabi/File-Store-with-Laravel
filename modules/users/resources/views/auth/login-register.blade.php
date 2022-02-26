@extends("users::auth.layouts.auth")
@section('header')
    <title>ورود - ثبت نام</title>
@endsection
@section('content')


    <div class="containerr">
        <div class="form-container">
            <div class="signin-signup">

                <form method="POST" action=" {{ route('login') }} " id="login_form" class="sign-in-form">
                    @csrf
                    <h2 class="title">ورود به سايت</h2>
                    <div class="input-field">
                        <v-icon>mdi-account</v-icon>
                        <input type="text" name="mobile" value="{{old('mobile')}}" placeholder="شماره موبایل">
                        @if($errors->has('mobile'))
                            <span style="width: 380px; text-align: right">{{$errors->first('mobile')}}</span>
                        @endif
                    </div>
                    <div class="input-field">
                        <v-icon>mdi-account</v-icon>
                        <input type="password" name="password" placeholder="رمز عبور">
                        @if($errors->has('password'))
                            <span style="width: 380px; text-align: right">{{$errors->first('password')}}</span>
                        @endif
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" dir="rtl" for="remember">
                            {{ __('مرا به خاطر بسپار!') }}
                        </label>
                    </div>
                    <div class="g-recaptcha" data-sitekey="6LeL3WsdAAAAACRfr9ZRBKs-sPigjRx2FoiPD7ch" data-theme="light"></div>
                    @if($errors->has('g-recaptcha-response'))
                        <span style="width: 380px; text-align: right">{{$errors->first('g-recaptcha-response')}}</span>
                    @endif
                    <div class="text-center">
                        <v-btn
                            class="button"
                            rounded
                            color="#5995fd"
                            dark
                            large
                            onclick="btn_login()"
                        >
                            ورود
                        </v-btn>
                        <a class="forget_password" href="{{url('/password/reset')}}">
                            فراموشی رمز عبور
                        </a>
                        <p class="social-text">یا ورود با</p>
                        <div class="social-media">
                            <a href="" class="social-icon">
                                <v-icon>mdi-facebook</v-icon>
                            </a>
                            <a href="" class="social-icon">
                                <v-icon>mdi-twitter</v-icon>
                            </a>
                            <a href="" class="social-icon">
                                <v-icon>mdi-google</v-icon>
                            </a>
                            <a href="" class="social-icon">
                                <v-icon>mdi-linkedin</v-icon>
                            </a>
                        </div>
                    </div>
                </form>
                <form method="POST" action=" {{ route('register') }} " id="register_form" class="sign-up-form">
                    @csrf
                    <h2 class="title">ثبت نام در سايت</h2>
                    <div class="input-field">
                        <v-icon>mdi-account</v-icon>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="نام و نام خانوادگی">
                        @if($errors->has('name'))
                            <span style="width: 380px; text-align: right">{{$errors->first('name')}}</span>
                        @endif
                    </div>
                    <div class="input-field">
                        <v-icon>mdi-account</v-icon>
                        <input type="text" name="r_mobile" value="{{ old('r_mobile') }}" placeholder="شماره موبایل">
                        @if($errors->has('r_mobile'))
                            <span style="width: 380px; text-align: right">{{$errors->first('r_mobile')}}</span>
                        @endif
                    </div>
                    <div class="input-field">
                        <v-icon>mdi-email</v-icon>
                        <input type="text" name="r_email" value="{{ old('r_email') }}" placeholder="ایمیل">
                        @if($errors->has('r_email'))
                            <span style="width: 380px; text-align: right">{{$errors->first('r_email')}}</span>
                        @endif
                    </div>
                    <div class="input-field">
                        <v-icon>mdi-lock-open</v-icon>
                        <input type="password" name="r_password" placeholder="رمز عبور">
                        @if($errors->has('r_password'))
                            <span style="width: 380px; text-align: right">{{$errors->first('r_password')}}</span>
                        @endif
                    </div>
                    <div class="input-field">
                        <v-icon>mdi-lock-open</v-icon>
                        <input type="password" name="password_confirmation" placeholder="تکرار رمز عبور">
                        @if($errors->has('password_confirmation'))
                            <span
                                style="width: 380px; text-align: right">{{$errors->first('password_confirmation')}}</span>
                        @endif
                    </div>
                    <div class="g-recaptcha" data-sitekey="6LeL3WsdAAAAACRfr9ZRBKs-sPigjRx2FoiPD7ch" data-theme="light"></div>
                    @if($errors->has('g-recaptcha-response'))
                        <span style="width: 380px; text-align: right">{{$errors->first('g-recaptcha-response')}}</span>
                    @endif
                    <div class="text-center">
                        <v-btn
                            rounded
                            color="#5995fd"
                            dark
                            large
                            onclick="btn_register()"
                        >
                            ثبت نام
                        </v-btn>
                        <p class="social-text">یا ورود با</p>
                        <div class="social-media">
                            <a href="" class="social-icon">
                                <v-icon>mdi-facebook</v-icon>
                            </a>
                            <a href="" class="social-icon">
                                <v-icon>mdi-twitter</v-icon>
                            </a>
                            <a href="" class="social-icon">
                                <v-icon>mdi-google</v-icon>
                            </a>
                            <a href="" class="social-icon">
                                <v-icon>mdi-linkedin</v-icon>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="panels-container">
            <div class="panel left-panel">
                <div class="content">
                    <h3>هنوز ثبت نام نکرده اید؟</h3>
                    <p>به ما بپیوندید</p>
                    <button class="btn transparents" id="sign-up-btn">ثبت نام</button>
                </div>
                <img class="image" src="{{ asset('files/icons/Access_account.svg') }}" alt="">
            </div>

            <div class="panel right-panel">
                <div class="content">
                    <h3>ثبت نام کرده اید؟</h3>
                    <p>به صفحه ورود بروید</p>
                    <button class="btn transparents" id="sign-in-btn">ورود</button>
                </div>
                <img class="image" src="{{ asset('files/icons/Login.svg') }}" alt="">
            </div>

        </div>

    </div>
@endsection


@section('footer')
    <script>
        function btn_login() {
            document.getElementById("login_form").submit();
        }

        function btn_register() {
            document.getElementById("register_form").submit();
        }

        setTimeout(function () {
            const container = document.querySelector(".containerr");
            /*      container.classList.add("sign-up-mode");*/

            const sign_in_btn = document.querySelector("#sign-in-btn");
            const sign_up_btn = document.querySelector("#sign-up-btn");
            sign_up_btn.addEventListener('click', () => {
                container.classList.add("sign-up-mode");
            });
            sign_in_btn.addEventListener('click', () => {
                container.classList.remove("sign-up-mode");
            });
            @if($errors->has('name') || $errors->has('r_email') || $errors->has('r_password') || $errors->has('r_mobile'))
                container.classList.add("sign-up-mode");
            @endif
        }, 300);

    </script>

@endsection
