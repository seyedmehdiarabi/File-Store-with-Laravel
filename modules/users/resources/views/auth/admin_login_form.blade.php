@extends("users::auth.layouts.auth")
<title>ورود به پنل مدیریت</title>
@section('content')
    <div class="containerr">
        <div class="form-container">
            <div class="signin-signup">

                <form method="POST" action=" {{ route('login') }} " id="login_form" class="sign-in-form">
                    @csrf
                    <h2 class="title">ورود به بخش مدیریت</h2>
                    <div class="input-field">
                        <v-icon>mdi-account</v-icon>
                        <input type="text" name="username" value="{{old('username')}}" placeholder="نام کاربری">
                        @if($errors->has('username'))
                            <span style="width: 380px; text-align: right">{{$errors->first('username')}}</span>
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
                        <input class="form-check-input" type="checkbox" name="remember"
                               id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" dir="rtl" for="remember">
                            {{ __('مرا به خاطر بسپار!') }}
                        </label>
                    </div>
                    <div class="g-recaptcha" data-sitekey="6LeL3WsdAAAAACRfr9ZRBKs-sPigjRx2FoiPD7ch"
                         data-theme="light"></div>
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
                <img class="image" src="{{ asset('files/icons/Access_account.svg') }}" alt="">
            </div>
        </div>

    </div>
@endsection
@section('footer')
    <script>
        function btn_login() {
            document.getElementById("login_form").submit();
        }
    </script>
@endsection

