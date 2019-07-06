<?php

namespace Imzhi\InspiniaAdmin;

use Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class InspiniaAdminServiceProvider extends ServiceProvider
{
    protected $commands = [
        Commands\InstallCommand::class,
        Commands\ResetPasswordCommand::class,
    ];

    protected $routeMiddleware = [
        'inspinia_admin.myauth' => Middleware\MyAuth::class,
        'inspinia_admin.myauthpermission' => Middleware\MyAuthPermission::class,
        'inspinia_admin.myauthsession' => Middleware\MyAuthSession::class,
    ];

    protected $middlewareGroups = [
        'inspinia_admin' => [
            'inspinia_admin.myauth',
            'inspinia_admin.myauthpermission',
            'inspinia_admin.myauthsession',
        ],
    ];

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'admin');

        if (file_exists($routes = app_path('Admin') . '/routes.php')) {
            $this->loadRoutesFrom($routes);
        }

        View::composer('admin::*', function ($view) {
            $view->with('user', Auth::guard('admin_user')->user());
        });

        Gate::before(function ($user, $ability) {
            return $user->hasRole('超级管理员') ? true : null;
        });

        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__ . '/../config' => config_path()], 'inspinia-admin-config');
            $this->publishes([__DIR__ . '/../database/migrations' => database_path('migrations')], 'inspinia-admin-migrations');
            $this->publishes([__DIR__ . '/../resources/assets' => public_path('vendor/inspinia-admin')], 'inspinia-admin-assets');
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->loadAdminAuthConfig();

        $this->registerRouteMiddleware();

        $this->commands($this->commands);
    }

    protected function registerRouteMiddleware()
    {
        foreach ($this->routeMiddleware as $key => $middleware) {
            app('router')->aliasMiddleware($key, $middleware);
        }

        foreach ($this->middlewareGroups as $key => $middleware) {
            app('router')->middlewareGroup($key, $middleware);
        }
    }

    protected function loadAdminAuthConfig()
    {
        config(array_dot(config('admin.auth', []), 'auth.'));
    }
}
