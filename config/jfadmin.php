<?php

return [
    'title' => 'jf-admin',
    'caption' => 'JFA+',

    'directory' => app_path('JFAdmin'),

    'pagination' => [
        'num' => 20,
    ],

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

    'view' => [
        'directory' => resource_path('views/jfadmin'),
        'namespace' => 'jfadmin',
    ],
];
