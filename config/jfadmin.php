<?php

return [
    // 站点标题
    'title' => 'jf-admin',
    // 站点标题缩写
    'caption' => 'JFA+',
    // 欢迎语句
    'welcome' => '欢迎来到 jf-admin 后台管理系统',
    // 登录页背景图
    'wallpaper' => 'http://upcdn.imzhi.me/jfadmin/5d2e7e7ccc59b26675.jpg',
    // 登录页标题文字 class
    'wallpaper_class' => 'text-white',

    // 安装目录
    'directory' => app_path('JFAdmin'),

    // 列表每页条目数量
    'page_num' => 20,

    // 超级管理员角色名称（支持数组）
    'super_role' => 'Super Admin',

    // 管理员用户配置
    'auth' => [
        'guards' => [
            'admin_user' => [
                'driver' => 'session',
                'provider' => 'admin_users',
            ],
        ],

        'providers' => [
            'admin_users' => [
                'driver' => 'eloquent',
                'model' => Imzhi\JFAdmin\Models\AdminUser::class,
            ],
        ],
    ],

    // 管理员权限模型类配置
    'permission' => [
        'models' => [
            'permission' => Imzhi\JFAdmin\Models\Permission::class,
            'role' => Imzhi\JFAdmin\Models\Role::class,
        ],
    ],

    // 路由配置
    'route' => [
        'prefix' => 'jfadmin',
        'namespace' => 'App\\JFAdmin\\Controllers',
        'as' => 'jfadmin::',
        'middleware' => ['web', 'jfadmin'],
        'domain' => env('JFA_ROUTE_DOMAIN'),
    ],
];
