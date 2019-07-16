<?php

namespace Imzhi\JFAdmin\Middleware;

use Closure;

class Auth
{
    public function handle($request, Closure $next)
    {
        if (!app('auth')->guard('admin_user')->check()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['err' => true, 'msg' => '未授权'], 401);
            } else {
                return redirect()->guest(route('jfadmin::show.login'))->withErrors('未授权');
            }
        }

        $user = app('auth')->guard('admin_user')->user();
        if (!$user->status) {
            $msg = '非常抱歉，您的账号已被禁用';
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['err' => true, 'msg' => $msg], 401);
            } else {
                app('auth')->guard('admin_user')->logout();
                return redirect()->guest(route('jfadmin::show.login'))->with('layer_msg', $msg);
            }
        }

        return $next($request);
    }
}
