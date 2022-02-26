<?php


namespace Modules\users\Http\Controllers\Auth;


use Illuminate\Http\Request;
use URL;
use Lang;

class LoginController extends \App\Http\Controllers\Auth\LoginController
{
    protected $view = '';

    public function __construct()
    {
        parent::__construct();

    }

    public function showLoginForm()
    {
        return redirect('/login-register');
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
            'g-recaptcha-response' => 'required|captcha',
        ], [], [
            'mobile' => 'شماره موبایل',
            'username' => 'نام کاربری',
            'password' => 'کلمه عبور',
            'g-recaptcha-response' => 'کپچا'
        ]);
    }

    protected function credentials(Request $request)
    {
        $credentials = $request->only($this->username(), 'password');
        $credentials['account_status'] = 'active';
        $url = URL::previous();
        if ($url == url('login-register')) {
            $credentials['role'] = 'user';
        } else {
            $credentials['role'] = 'admin';
        }
        return $credentials;
    }

    public function username()
    {
        $url = URL::previous();
        if ($url == url('admin_login')) {
            return 'username';
        } else {
            return 'mobile';
        }
    }

    protected function authenticated(Request $request, $user)
    {
        run_action('authenticated', [$user]);
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            if ($request->hasSession()) {
                $request->session()->put('auth.password_confirmed_at', time());
            }

            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    public function admin_login_form()
    {
        $layout = $this->view == 'mobile.' ? 'mobile' : 'desktop';
        return view('users::auth.admin_login_form', ['layout' => $layout]);
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return [
            'reload' => url('/')
        ];
    }
}
