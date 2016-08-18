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
        'vendor/mikelmi/mks-smart-table/js/mks-smart-table.js',
        'js/mks-admin-ext.js',
    ],

    'appModules' => [
        'mks-smart-table',
        'mks-admin-ext'
    ],

    'menu_manager' => \App\Services\AdminMenu::class,

    'menu' => [
        ['title' => 'a.Home', 'href' => '#/home', 'hash' => 'home', 'icon'=>'home'],
        ['title' => 'a.Settings', 'href' => '#/settings', 'hash' => 'settings', 'icon'=>'cog', 'nickname'=>'settings'],
        [
            'title' => 'a.Users', 'href' => '#/user', 'hash'=>'user', 'icon'=>'users',
            'children' => [
                ['title' => 'a.Users', 'href' => '#/user', 'hash' => 'user'],
                ['title' => 'a.Roles', 'href' => '#/role', 'hash' => 'role'],
                ['title' => 'a.Permissions', 'href' => '#/permission', 'hash' => 'permission'],
            ]
        ],
        [
            'title' => 'a.Pages', 'href' => '#/page', 'hash'=>'page', 'icon'=>'file',
            'children' => [
                ['title' => 'a.Pages', 'href' => '#/page', 'hash' => 'page'],
                ['title' => 'a.Add', 'href' => '#/page/edit', 'hash' => 'page/edit'],
                ['title' => 'a.Trash', 'href' => '#/page/trash', 'hash' => 'page/trash']
            ]
        ],
    ],
];
