<?php

namespace Imzhi\JFAdmin;

use Illuminate\Support\ServiceProvider;
use Doctrine\Common\Annotations\AnnotationRegistry;

class JFAdminServiceProvider extends ServiceProvider
{
    protected $commands = [
        Console\InstallCommand::class,
        Console\UninstallCommand::class,
        Console\ResetPasswordCommand::class,
    ];

    protected $routeMiddleware = [
        'jfadmin.auth' => Middleware\Auth::class,
        'jfadmin.guest' => Middleware\Guest::class,
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

        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'jfadmin');

        if (file_exists($routes = config('jfadmin.directory') . '/routes.php')) {
            $this->loadRoutesFrom($routes);
        }

        $this->app['view']->composer('jfadmin::*', function ($view) {
            $view->with('admin_user', $this->app['auth']->guard('admin_user')->user());
        });

        $this->app['Illuminate\Contracts\Auth\Access\Gate']->before(function ($user, $ability) {
            return $user->hasRole(config('jfadmin.super_role')) ? true : null;
        });

        $this->app['Illuminate\Contracts\Routing\ResponseFactory']->macro('suc', function ($value) {
            JFAdmin::logActivity();

            $value['err'] = false;

            return response()->json($value);
        });

        $this->app['Illuminate\Contracts\Routing\ResponseFactory']->macro('fai', function ($value) {
            $value['err'] = true;

            return response()->json($value);
        });

        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__ . '/../config' => config_path()], 'jfadmin-config');
            $this->publishes([__DIR__ . '/../database/migrations' => database_path('migrations')], 'jfadmin-migrations');
            $this->publishes([__DIR__ . '/../resources/assets' => public_path('vendor/jfadmin')], 'jfadmin-assets');
            $this->publishes([__DIR__ . '/../resources/lang' => resource_path('lang/vendor/jfadmin')], 'jfadmin-lang');
            $this->publishes([__DIR__ . '/../resources/views/layouts' => resource_path('views/vendor/jfadmin/layouts')], 'jfadmin-views');
            $this->publishes([__DIR__ . '/../resources/views/home' => resource_path('views/vendor/jfadmin/home')], 'jfadmin-views');
        }

        AnnotationRegistry::registerFile(__DIR__ . '/Annotations/PermissionAnnotation.php');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->loadAdminAuthConfig();

        $this->loadAdminPermissionConfig();

        $this->registerRouteMiddleware();

        $this->commands($this->commands);
    }

    protected function registerRouteMiddleware()
    {
        foreach ($this->routeMiddleware as $key => $middleware) {
            $this->app['router']->aliasMiddleware($key, $middleware);
        }

        foreach ($this->middlewareGroups as $key => $middleware) {
            $this->app['router']->middlewareGroup($key, $middleware);
        }
    }

    protected function loadAdminAuthConfig()
    {
        config(array_dot(config('jfadmin.auth', []), 'auth.'));
    }

    protected function loadAdminPermissionConfig()
    {
        config(array_dot(config('jfadmin.permission', []), 'permission.'));
    }
}
