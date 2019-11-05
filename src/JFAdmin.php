<?php

namespace Imzhi\JFAdmin;

/**
 * Class JFAdmin.
 */
class JFAdmin
{
    const VERSION = '1.0.0';

    public function routes()
    {
        $attributes = [
            'middleware' => 'web',
            'namespace' => '\Imzhi\JFAdmin\Controllers',
            'prefix' => config('jfadmin.route.prefix'),
            'as' => config('jfadmin.route.as'),
            'domain' => config('jfadmin.route.domain'),
        ];
        app('router')->group($attributes, function ($router) {
            $router->get('login', 'AuthController@showLoginForm')->name('show.login');
            $router->post('login', 'AuthController@login')->name('login');
            $router->get('logout', 'AuthController@logout')->name('logout');

            $router->middleware(config('jfadmin.route.middleware'))->group(function ($router) {
                // 修改密码
                $router->get('profile/pwd', 'ProfileController@showPwd')->name('show.profile.pwd');
                $router->post('profile/pwd', 'ProfileController@pwd')->name('profile.pwd');

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

    public static function logActivity()
    {
        $request = request();

        $properties = [
            '_route_' => $request->route() ? $request->route()->getName() : null,
            '_url_' => $request->url(),
            '_path_' => $request->path(),
            '_method_' => $request->method(),
            '_params_' => $request->input(),
            '_ip_' => $request->ip(),
            '_ua_' => $request->userAgent(),
            '_ajax_' => $request->ajax(),
        ];

        activity()->causedBy($request->user('admin_user'))
            ->withProperties($properties)
            ->log($properties['_route_']);
    }
}
