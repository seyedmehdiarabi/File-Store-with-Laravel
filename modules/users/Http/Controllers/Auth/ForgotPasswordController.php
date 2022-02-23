<?php


namespace Modules\users\Http\Controllers\Auth;


use Illuminate\Http\Request;
use Modules\users\Models\User;
use Session;

class ForgotPasswordController extends \App\Http\Controllers\Auth\ForgotPasswordController
{
    public function __construct()
    {
        $this->middleware('guest');
        parent::__construct();
    }

    public function check_confirm_code(Request $request)
    {
        $mobile = $request->get('mobile');
        $token = Session::get('forget_password_token');
        $forget_password_code = $request->get('forget_password_code');
        $user = User::where(['forget_password_code' => $forget_password_code, 'mobile' => $mobile, 'account_status' => 'active'])->first();
        if ($user) {
            $user->forget_password_code = null;
            $user->update();
            Session::forget('token');
            return [
                'status' => 'ok',
                'token' => $token
            ];
        } else {
            return [
                'status' => 'error',
                'message' => 'کد وارد شده اشتباه میباشد'
            ];
        }
    }

    public function showLinkRequestForm()
    {
        $layout = $this->view == 'mobile.' ? 'mobile-auth' : 'desktop';
        return CView('users::auth.passwords.email', ['layout' => $layout]);
    }

    public
    function sendResetLinkEmail(Request $request)
    {
        $this->validateEmail($request);

        $user = $this->broker()->getUser($this->credentials($request));

        if ($user && $user->account_status == 'active') {
            $token = $this->broker()->createToken($user);
            $forget_password_code = rand(99999, 1000000);
            $user->forget_password_code = $forget_password_code;
            Session::put('forget_password_token', $token);
            $user->update();
            send_auth_sms($user, $user->forget_password_code);
            return [
                'status' => 'ok'
            ];
        } else {
            return [
                'status' => 'error',
                'message' => 'شماره موبایل وارد شده اشتباه می باشد'
            ];
        }
    }

    protected
    function validateEmail(Request $request)
    {
        $request->validate(['mobile' => 'required']);
    }

    protected
    function credentials(Request $request)
    {
        return $request->only('mobile');
    }
}
