<?php


namespace Modules\users\Http\Controllers\Auth;


use App\Lib\Mobile_Detect;
use Illuminate\Http\Request;

class ResetPasswordController extends \App\Http\Controllers\Auth\ResetPasswordController
{
    protected $redirectTo = '/';

    public function __construct()
    {
        $this->middleware('guest');
        parent::__construct();
    }

    protected function rules()
    {
        return [
            'token' => 'required',
            'mobile' => 'required',
            'password' => 'required|confirmed|min:6',
        ];
    }

    protected function credentials(Request $request)
    {
        return $request->only(
            'mobile', 'password', 'password_confirmation', 'token'
        );
    }

    protected function sendResetFailedResponse(Request $request, $response)
    {
        return [
            'status' => 'error',
            'message' => 'اطلاعات ارسال شده صحیح نمی‌باشد!'
        ];
    }
}
