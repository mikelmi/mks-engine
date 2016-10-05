<?php

return [
    'url' => env('ADMIN_URL', 'admin'),
    'site_url' => env('SITE_URL'),
    'materialized' => true,
    'username' => 'email',
    'reset_enable' => false,
    'locale' => 'en',
    'locales' => [
        'uk' => 'Українська',
        'ru' => 'Русский',
        'en' => 'English'
    ],
    'search_form' => false,

    'scripts' => [
        'vendor/ckeditor/ckeditor.js',
        'vendor/mikelmi/mks-smart-table/js/mks-smart-table.js',
        'admin/js/admin.js',
    ],

    'styles' => [
        'admin/css/admin.css'
    ],

    'appModules' => [
        'mks-smart-table',
        'mks-admin-ext',
        'mks-menu-manager',
        'mks-dashboard'
    ],

    'home_button' => '<a class="lc-hide inline title" href="#/home"><i class="fa fa-wrench"></i> Admin</a>',

    'menu_manager' => \App\Services\AdminMenu::class,

    'menu' => [
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
                ['title' => 'admin::messages.Add', 'href' => '#/page/edit', 'hash' => 'page/edit'],
                ['title' => 'admin::messages.Trash', 'href' => '#/page/trash', 'hash' => 'page/trash']
            ]
        ],
        ['title' => 'a.Menu', 'href' => '#/menuman', 'hash' => 'menuman', 'icon'=>'list-ul', 'nickname'=>'menu'],
        ['title' => 'a.Widgets', 'href' => '#/widget', 'hash' => 'widget', 'icon'=>'th-large', 'nickname'=>'widget'],
        ['title' => 'filemanager.page_title', 'href' => '#/file-manager', 'icon'=>'folder'],
    ],
];
