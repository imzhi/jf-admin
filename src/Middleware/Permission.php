<?php

namespace Imzhi\JFAdmin\Middleware;

use Route;
use Closure;

class Permission
{

    public function handle($request, Closure $next)
    {
        $user = app('auth')->guard('admin_user')->user();

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
