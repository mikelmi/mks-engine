<?php

return [
    'url' => env('ADMIN_URL', 'admin'),
    'site_url' => env('SITE_URL'),
    'username' => 'email',
    'reset_enable' => false,
    'locale' => 'en',
    'locales' => [
        'uk' => 'Українська',
        'en' => 'English'
    ],
    'search_form' => false,

    'scripts' => [
        'vendor/mikelmi/mks-smart-table/js/mks-smart-table.js'
    ],

    'appModules' => [
        'mks-smart-table'
    ],

    'menu_manager' => \App\Services\AdminMenu::class,

    'menu' => [
        ['title' => 'a.Home', 'href' => '#/home', 'hash' => 'home', 'icon'=>'home'],
        ['title' => 'a.Settings', 'href' => '#/settings', 'hash' => 'settings', 'icon'=>'cog', 'nickname'=>'settings'],
        [
            'title' => 'a.Users', 'href' => '#/users', 'hash'=>'user', 'icon'=>'users',
            'children' => [
                ['title' => 'a.Users', 'href' => '#/users', 'hash' => 'user'],
                ['title' => 'a.Roles', 'href' => '#/roles', 'hash' => 'role'],
                ['title' => 'a.Permissions', 'href' => '#/permissions', 'hash' => 'permission'],
            ]
        ],
    ],
];
