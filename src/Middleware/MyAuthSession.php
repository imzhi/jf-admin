<?php

namespace Imzhi\InspiniaAdmin\Middleware;

use Illuminate\Http\Request;

class MyAuthSession
{
    public function handle(Request $request, \Closure $next)
    {
        config(['session.path' => '/admin']);

        if ($domain = config('admin.route.domain')) {
            config(['session.domain' => $domain]);
        }

        return $next($request);
    }
}
