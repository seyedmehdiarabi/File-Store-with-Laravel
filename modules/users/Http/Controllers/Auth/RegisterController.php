<?php


namespace Modules\users\Http\Controllers\Auth;


use App\Lib\Mobile_Detect;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\users\Models\User;
use Illuminate\Support\Facades\Hash;
use Modules\users\Rules\ValidateMobileNumber;
use Session;
use Auth;

class RegisterController extends \App\Http\Controllers\Auth\RegisterController
{
    protected $redirectTo = '/admin';

    public function __construct()
    {
        parent::__construct();
    }

    public function showRegistrationForm()
    {
        return redirect('/login-register');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'min:6'],
            'r_mobile' => ['required','unique:users,mobile', new ValidateMobileNumber()],
            'r_email' => ['required', 'string', 'email', 'max:255','unique:users,email'],
            'r_password' => ['required', 'string', 'min:6','same:password_confirmation'],
            'g-recaptcha-response'=>'required|captcha',
        ], [], [
            'name' => 'نام و نام خانوادگی',
            'r_mobile' => 'شماره موبایل',
            'r_email'=>'ایمیل',
            'r_password' => 'کلمه عبور',
            'g-recaptcha-response'=>'کپچا'
        ]);
    }

    protected function create(array $data)
    {
        $active_code = rand(99999, 1000000);
        return User::create([
            'name' => $data['name'],
            'mobile' => $data['r_mobile'],
            'email' => $data['r_email'],
            'password' => Hash::make($data['r_password']),
            'account_status' => 'InActive',
            'active_code' => $active_code,
            'role' => 'user'
        ]);
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }

    protected function registered(Request $request, $user)
    {
        run_action('registered', [$user]);
        return $this->get_active_account($user->mobile);
        //return redirect('register/active_account');
    }

    public function get_active_account($mobile){
        $layout=$this->view=='mobile.' ? 'mobile' : 'desktop';
        return CView('users::auth.active_account',['layout'=>$layout,'mobile'=>$mobile]);
    }

    public function active_account(Request $request)
    {
        $mobile = $request->get('mobile');
        $active_code = $request->get('active_code');
        $user = User::where([
            'mobile' => $mobile,
            'active_code' => $active_code,
            'account_status' => 'InActive'
        ])->first();
        if ($user) {
            $user->account_status = 'active';
            $user->active_code = null;
            $user->update();
            Auth::guard()->login($user);
            return 'ok';
        } else {
            return ['error' => 'کد وارد شده صحیح نمی‌باشد!'];
        }
    }
}
