@extends("users::auth.layouts.$layout")

@section('content')
<div id="auth_box">
    <div class="auth_box_title">
        <span>تغییر کلمه عبور</span>
    </div>
    <div style="margin:{{ $margin }}px">

        <form method="POST" action="{{ route('password.update') }}" id="updatePasswordForm">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
              <div class="form-group">
                  <div class="field_name">شماره موبایل</div>
                  <label class="input_label user_name">
                     <input type="text" class="form-control @if($errors->has('mobile')) validate_error_border @endif" name="mobile" id="mobile" value="{{ $mobile ?? old('mobile') }}" placeholder="شماره موبایل خود را وارد نمایید">

                      <label id="mobile_error_message" class="feedback-hint"  @if($errors->has('mobile')) style="display:block" @endif>
                          @if($errors->has('mobile'))
                              <span>{{ $errors->first('mobile') }}</span>
                          @endif
                      </label>
                  </label>
              </div>


              <div class="form-group">
                  <div class="field_name">کلمه عبور</div>
                  <label class="input_label user_pass">
                      <input type="password" class="form-control @if($errors->has('password')) validate_error_border @endif" name="password" id="password" placeholder="کلمه عبور خود را وارد نمایید">

                      <label id="password_error_message" class="feedback-hint"  @if($errors->has('password')) style="display:block" @endif>
                          @if($errors->has('password'))
                              <span>{{ $errors->first('password') }}</span>
                          @endif
                      </label>
                  </label>
              </div>

              <div class="form-group">
                <div class="field_name">تکرار کلمه عبور</div>
                <label class="input_label user_pass">
                    <input type="password" class="form-control @if($errors->has('password_confirmation')) validate_error_border @endif" name="password_confirmation" id="password_confirmation" placeholder="تکرار کلمه عبور خود را وارد نمایید">

                    <label id="password_confirmation_error_message" class="feedback-hint"  @if($errors->has('password_confirmation')) style="display:block" @endif>
                        @if($errors->has('password_confirmation'))
                            <span>{{ $errors->first('password_confirmation') }}</span>
                        @endif
                    </label>
                </label>
            </div>

              <div class="send_btn forget_password" id="update_password_btn">
                  <span class="line"></span>
                  <span class="title">تغییر کلمه عبور</span>
              </div>
          </form>
    </div>
</div>
@endsection
