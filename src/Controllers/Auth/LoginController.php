<?php

namespace Imzhi\JFAdmin\Controllers\Auth;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    protected $guard = 'admin_user';

    protected $request;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->middleware('guest:' . $this->guard)->except('logout');

        $this->request = $request;
    }

    /**
     * 后台登录页面
     */
    protected function showLoginForm()
    {
        return view('jf-admin::auth.login.login');
    }

    /**
     * 后台登录操作
     */
    protected function login()
    {
        $this->validateLogin($this->request);

        $img_captcha = $this->request->input('img_captcha');
        if (!captcha_check($img_captcha)) {
            return ['err' => true, 'msg' => '图形验证码不正确'];
        }

        if ($this->hasTooManyLoginAttempts($this->request)) {
            $this->fireLockoutEvent($this->request);

            return $this->sendLockoutResponse($this->request);
        }

        if ($this->attemptLogin($this->request)) {
            return $this->sendLoginResponse($this->request);
        }

        $this->incrementLoginAttempts($this->request);

        return ['err' => true, 'msg' => trans('auth.failed')];
    }

    /**
     * 后台注销登录操作
     */
    protected function logout()
    {
        $this->guard()->logout();

        $this->request->session()->invalidate();

        return redirect()->route('jf-admin::show.login');
    }

    protected function username()
    {
        return 'name';
    }

    protected function guard()
    {
        return Auth::guard($this->guard);
    }

    protected function validateLogin(Request $request)
    {
        $username_field = $this->username();
        $this->validate($request, [
            $username_field => 'required|string',
            'password' => 'required|string',
            'img_captcha' => 'required',
        ], [
            $username_field . '.required' => '用户名必填',
            $username_field . '.string' => '用户名不是填的字符串',
            'password.required' => '密码必填',
            'password.string' => '密码不是填的字符串',
            'img_captcha.required' => '图形验证码必填',
        ]);
    }

    protected function authenticated(Request $request, $user)
    {
        $redirect = $request->session()->pull('url.intended', route('jf-admin::show.index'));
        return [
            'err' => false,
            'msg' => '登录成功',
            'redirect' => $redirect,
        ];
    }
}
