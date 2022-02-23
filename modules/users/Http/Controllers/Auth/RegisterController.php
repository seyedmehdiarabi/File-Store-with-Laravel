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
        $layout = $this->view == 'mobile.' ? 'mobile-auth' : 'desktop';
        $margin = $this->view == 'mobile.' ? '10' : '25';
        return view('users::auth.register', ['layout' => $layout, 'margin' => $margin]);
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'mobile' => ['required', new ValidateMobileNumber()],
            'password' => ['required', 'string', 'min:6'],
        ], [], ['mobile' => 'شماره موبایل', 'password' => 'کلمه عبور']);
    }

    protected function create(array $data)
    {
        $active_code = rand(99999, 1000000);
        return User::create([
            'mobile' => $data['mobile'],
            'password' => Hash::make($data['password']),
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
        return [
            'status' => 'ok',
        ];
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
