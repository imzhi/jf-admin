<?php

return [
    'title' => 'inspinia-admin',
    'caption' => 'IN+',

    'pagination' => [
        'num' => 20,
    ],

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
                'model' => Imzhi\InspiniaAdmin\Models\AdminUser::class,
            ],
        ],
    ],
];
