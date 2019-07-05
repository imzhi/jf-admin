<?php

namespace Imzhi\InspiniaAdmin;

use Illuminate\Support\ServiceProvider;

class InspiniaAdminServiceProvider extends ServiceProvider
{
    protected $commands = [
        Commands\InstallCommand::class,
    ];

    protected $routeMiddleware = [
        'inspinia_admin.myauth' => Middleware\MyAuth::class,
        'inspinia_admin.myauthpermission' => Middleware\MyAuthPermission::class,
    ];

    protected $middlewareGroups = [
        'inspinia_admin' => [
            'inspinia_admin.myauth',
            'inspinia_admin.myauthpermission',
        ],
    ];

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        \Log::debug(__CLASS__, ['aaa' => date('c')]);

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'admin');

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

        $this->routes();

        $this->commands($this->commands);
    }

    protected function routes()
    {
        $attributes = [
            'prefix' => 'admin',
            'namespace' => '\Imzhi\InspiniaAdmin\Controllers',
            'as' => 'admin::',
            'middleware' => 'web',
            // 'domain' => '',
        ];
        app('router')->group($attributes, function ($router) {
            $router->get('login', 'Auth\LoginController@showLoginForm')->name('show.login');
            $router->post('login', 'Auth\LoginController@login')->name('login');
            $router->get('logout', 'Auth\LoginController@logout')->name('logout');

            $router->middleware('inspinia_admin')->group(function ($router) {
                // 首页
                $router->get('', 'HomeController@showIndex')->name('show.index');

                // 修改密码
                $router->get('pwd', 'HomeController@showPwd')->name('show.pwd');
                $router->post('pwd', 'HomeController@pwd')->name('pwd');

                // 管理员管理-成员管理
                $router->get('manageuser/list', 'ManageUserController@showList')->name('show.manageuser.list');
                $router->get('manageuser/create/{id?}', 'ManageUserController@showCreate')->name('show.manageuser.create');
                $router->post('manageuser/create', 'ManageUserController@create')->name('manageuser.create');
                $router->post('manageuser/status', 'ManageUserController@status')->name('manageuser.status');
                $router->get('manageuser/distribute/{id}', 'ManageUserController@showDistribute')->name('show.manageuser.distribute');
                $router->post('manageuser/distribute', 'ManageUserController@distribute')->name('manageuser.distribute');

                // 管理员管理-角色管理
                $router->get('manageuser/roles', 'ManageUserController@showRoles')->name('show.manageuser.roles');
                $router->get('manageuser/roles/create/{id?}', 'ManageUserController@showRolesCreate')->name('show.manageuser.roles.create');
                $router->post('manageuser/roles/create', 'ManageUserController@rolesCreate')->name('manageuser.roles.create');
                $router->get('manageuser/roles/distribute/{id}', 'ManageUserController@showRolesDistribute')->name('show.manageuser.roles.distribute');
                $router->post('manageuser/roles/distribute', 'ManageUserController@rolesDistribute')->name('manageuser.roles.distribute');

                // 管理员管理-权限管理
                $router->get('manageuser/permissions', 'ManageUserController@showPermissions')->name('show.manageuser.permissions');
                $router->post('manageuser/permissions/detect', 'ManageUserController@permissionsDetect')->name('manageuser.permissions.detect');
                $router->post('manageuser/permissions/group', 'ManageUserController@permissionsGroup')->name('manageuser.permissions.group');

                // 系统设置
                $router->get('setting/log', 'SettingController@showLog')->name('show.setting.log');
            });
        });
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
