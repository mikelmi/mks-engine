<?php

return [
    'url' => env('ADMIN_URL', 'admin-panel'),
    'site_url' => env('SITE_URL'),
    'materialized' => false,
    'username' => 'email',
    'reset_enable' => true,
    'locale' => env('APP_LOCALE', 'en'),
    'locales' => [
        'uk' => 'Українська',
        'ru' => 'Русский',
        'en' => 'English'
    ],
    'search_form' => false,

    'scripts' => [
        'vendor/ckeditor/ckeditor.js',
        'admin/js/admin.js',
    ],

    'styles' => [
        'admin/css/admin.css'
    ],

    'appModules' => [
        'mks-admin-ext',
        'mks-menu-manager',
        'mks-dashboard',
        'mks-category-manager',
        'artisan'
    ],

    'form' => [
        'layout' => 'row',
        'fields' => [
            'changePassword' => \App\Form\Field\ChangePassword::class
        ]
    ],

    'datagrid' => [
        'columns' => [
            'language' => \App\DataGrid\Columns\ColumnLanguage::class
        ],
        'actions' => [
            'toggleOne' => \App\DataGrid\Actions\ToggleOne::class
        ]
    ],

    'home_button' => '<a class="lc-hide inline title" href="#/home"><i class="fa fa-wrench"></i> Admin</a>',

    'menu_manager' => \App\Services\AdminMenu::class,

    'menu' => [
        [
            'title' => 'general.System', 'icon'=>'cogs',
            'children' => [
                ['title' => 'general.Settings', 'href' => '#/settings', 'hash' => 'settings', 'icon'=>'cog', 'nickname'=>'settings'],
                ['title' => 'general.Languages', 'href' => '#/language', 'hash' => 'language', 'icon'=>'language', 'nickname'=>'language'],
                ['title' => 'filemanager.page_title', 'href' => '#/file-manager', 'icon'=>'folder', 'hash' => 'file-manager'],
            ]
        ],
        [
            'title' => 'general.Users', 'hash'=>'user', 'icon'=>'user',
            'children' => [
                ['title' => 'general.Users', 'href' => '#/user', 'hash' => 'user'],
                ['title' => 'general.Roles', 'href' => '#/role', 'hash' => 'role'],
                ['title' => 'general.Permissions', 'href' => '#/permission', 'hash' => 'permission'],
            ]
        ],
        [
            'title' => 'general.Pages', 'href' => '#/page', 'hash'=>'page', 'icon'=>'file',
            'children' => [
                ['title' => 'general.Pages', 'href' => '#/page', 'hash' => 'page'],
                ['title' => 'admin::messages.Add', 'href' => '#/page/edit', 'hash' => 'page/edit'],
                ['title' => 'admin::messages.Trash', 'href' => '#/page/trash', 'hash' => 'page/trash']
            ]
        ],
        ['title' => 'general.Menu', 'href' => '#/menuman', 'hash' => 'menuman', 'icon'=>'list-ul', 'nickname'=>'menu'],
        ['title' => 'general.Widgets', 'href' => '#/widget', 'hash' => 'widget', 'icon'=>'th-large', 'nickname'=>'widget'],
        ['title' => 'general.Categories', 'href' => '#/category', 'hash' => 'category', 'icon'=>'indent', 'nickname'=>'categories'],
    ],
];
