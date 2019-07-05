<?php

namespace Imzhi\InspiniaAdmin\Middleware;

use Route;
use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;

class MyAuthPermission
{
    protected $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    public function handle($request, Closure $next)
    {
        $user = $this->auth->guard('admin_user')->user();

        $route_name = Route::currentRouteName();
        // if (!$user->hasPermissionTo($route_name)) {
        if ($user->cant($route_name)) {
            if ($request->expectsJson()) {
                return response()->json(['err' => true, 'msg' => "未授权操作（路由别名：{$route_name}），如需授权请联系管理员"], 401);
            }
            return response("未授权操作（路由别名：{$route_name}），如需授权请联系管理员", 401);
        }

        return $next($request);
    }
}
