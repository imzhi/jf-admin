<?php

namespace Imzhi\JFAdmin\Middleware;

use Closure;

class Guest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (app('auth')->guard('admin_user')->check()) {
            return redirect(route('jfadmin::show.index'));
        }

        return $next($request);
    }
}
