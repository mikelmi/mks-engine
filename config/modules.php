<?php

return [
    'paths' => [
        'modules' => base_path('modules'),
    ],

    'cache' => [
        'lifetime' => null,
        'key' => env('APP_DEBUG') ? null : 'app:modules',
    ]
];