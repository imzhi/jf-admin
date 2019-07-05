<?php

namespace Imzhi\InspiniaAdmin\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;

class MyAuth
{
    protected $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    public function handle($request, Closure $next)
    {
        if (!$this->auth->guard('admin_user')->check()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['err' => true, 'msg' => '未授权'], 401);
            } else {
                return redirect()->guest('admin/login')->withErrors('未授权');
            }
        }

        $user = $this->auth->guard('admin_user')->user();
        if (!$user->status) {
            $msg = '非常抱歉，您的账号已被禁用';
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['err' => true, 'msg' => $msg], 401);
            } else {
                $this->auth->guard('admin_user')->logout();
                return redirect()->guest('admin/login')->with('layer_msg', $msg);
            }
        }

        return $next($request);
    }
}
