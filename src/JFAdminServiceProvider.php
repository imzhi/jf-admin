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
        'jfadmin.auth' => Middleware\Auth::class,
        'jfadmin.permission' => Middleware\Permission::class,
    ];

    protected $middlewareGroups = [
        'jfadmin' => [
            'jfadmin.auth',
            'jfadmin.permission',
        ],
    ];

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'jfadmin');

        if (file_exists($routes = config('jfadmin.directory') . '/routes.php')) {
            $this->loadRoutesFrom($routes);
        }

        View::composer('jfadmin::*', function ($view) {
            $view->with('admin_user', Auth::guard('admin_user')->user());
        });

        Gate::before(function ($user, $ability) {
            return $user->hasRole(config('jfadmin.super_role')) ? true : null;
        });

        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__ . '/../config' => config_path()], 'jfadmin-config');
            $this->publishes([__DIR__ . '/../database/migrations' => database_path('migrations')], 'jfadmin-migrations');
            $this->publishes([__DIR__ . '/../resources/assets' => public_path('vendor/jfadmin')], 'jfadmin-assets');
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
        config(array_dot(config('jfadmin.auth', []), 'auth.'));
    }
}
