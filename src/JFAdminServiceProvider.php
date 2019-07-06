<?php

namespace Imzhi\JFAdmin;

use Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class JFAdminServiceProvider extends ServiceProvider
{
    protected $commands = [
        Console\InstallCommand::class,
        Console\UninstallCommand::class,
        Console\ResetPasswordCommand::class,
    ];

    protected $routeMiddleware = [
        'jf-admin.auth' => Middleware\Auth::class,
        'jf-admin.permission' => Middleware\Permission::class,
    ];

    protected $middlewareGroups = [
        'jf-admin' => [
            'jf-admin.auth',
            'jf-admin.permission',
        ],
    ];

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'jf-admin');

        if (file_exists($routes = config('jf-admin.directory') . '/routes.php')) {
            $this->loadRoutesFrom($routes);
        }

        View::composer('jf-admin::*', function ($view) {
            $view->with('admin_user', Auth::guard('admin_user')->user());
        });

        Gate::before(function ($user, $ability) {
            return $user->hasRole(config('jf-admin.super_role')) ? true : null;
        });

        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__ . '/../config' => config_path()], 'jf-admin-config');
            $this->publishes([__DIR__ . '/../database/migrations' => database_path('migrations')], 'jf-admin-migrations');
            $this->publishes([__DIR__ . '/../resources/assets' => public_path('vendor/jf-admin')], 'jf-admin-assets');
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
        config(array_dot(config('jf-admin.auth', []), 'auth.'));
    }
}
