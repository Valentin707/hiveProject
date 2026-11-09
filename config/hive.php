<?php

return [
    'admin' => [
        'name' => env('HIVE_ADMIN_NAME', 'Hive Administrator'),
        'email' => env('HIVE_ADMIN_EMAIL'),
        'password' => env('HIVE_ADMIN_PASSWORD'),
    ],

    'services' => [
        [
            'name' => 'HiveTasks',
            'url' => env('HIVE_TASKS_URL', 'https://tasks.hive.test'),
            'active' => env('HIVE_TASKS_ACTIVE', true),
            'accent' => '#ff4b5c',
        ],
    ],
];
