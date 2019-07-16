<?php

return [
    'title' => 'jf-admin',
    'caption' => 'JFA+',
    'welcome' => '欢迎来到 jf-admin 后台管理系统',

    'directory' => app_path('JFAdmin'),

    'page_num' => 20,

    'super_role' => 'Super Admin',

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

    'route' => [
        'prefix' => 'jfadmin',
        'namespace' => 'App\\JFAdmin\\Controllers',
        'as' => 'jfadmin::',
        'middleware' => ['web', 'jfadmin'],
        'domain' => env('JFA_ROUTE_DOMAIN'),
    ],
];
