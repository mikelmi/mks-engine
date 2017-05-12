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

    'scripts' => [
        'vendor/ckeditor/ckeditor.js',
        'admin/js/admin-app.js',
    ],

    'styles' => [
        'admin/css/admin.css'
    ],

    'appModules' => [
        'mks-components',
        'mks-link-select',
        'mks-menu-manager',
        'mks-dashboard',
        'mks-category-manager',
        'artisan'
    ],

    'form' => [
        'layout' => 'row',
        'fields' => [
            'changePassword' => \App\Form\Field\ChangePassword::class,
            'language' => \App\Form\Field\LanguageSelect::class,
            'checkedInput' => \App\Form\Field\CheckedInput::class,
            'editor' => \App\Form\Field\Editor::class,
            'meta' => \App\Form\Field\MetaTags::class,
            'rolesShow' => \App\Form\Field\ShowForRoles::class,
            'route' => \App\Form\Field\RouteLink::class,
            'pages' => \App\Form\Field\SelectPages::class,
            'routesShow' => \App\Form\Field\ShowForRoutes::class,
            'assoc' => \App\Form\Field\AssocArray::class,
            'image' => \App\Form\Field\ImagePicker::class,
            'images' => \App\Form\Field\ImagesPicker::class,
            'size' => \App\Form\Field\Size::class,
            'category' => \App\Form\Field\CategorySelect::class,
            'tags' => \App\Form\Field\TagsSelect::class,
            'icon' => \App\Form\Field\IconPicker::class,
            'button' => \App\Form\Field\Button::class,
        ]
    ],

    'datagrid' => [
        'columns' => [
            'language' => \App\DataGrid\Columns\ColumnLanguage::class,
            'thumbnail' => \App\DataGrid\Columns\ColumnThumbnail::class,
            'list' => \App\DataGrid\Columns\ColumnList::class
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
                ['title' => 'general.Settings', 'href' => '#/settings', 'hash' => 'settings', 'icon'=>'cog', 'nickname'=>'settings', 'can' => 'admin.settings.*'],
                ['title' => 'general.Languages', 'href' => '#/language', 'hash' => 'language', 'icon'=>'language', 'nickname'=>'language', 'can' => 'admin.lang.*'],
                ['title' => 'filemanager.page_title', 'href' => '#/file-manager', 'icon'=>'folder', 'hash' => 'file-manager', 'can' => 'files.view'],
                ['title' => 'general.Tags', 'href' => '#/tags', 'hash' => 'tags', 'icon'=>'tags', 'nickname'=>'tags', 'can' => 'admin.tags.*'],
            ]
        ],
        [
            'title' => 'general.Users', 'href' => '#/user', 'hash'=>'user', 'icon'=>'user',
            'children' => [
                ['title' => 'general.Users', 'href' => '#/user', 'hash' => 'user', 'can' => 'admin.users.*'],
                ['title' => 'general.Roles', 'href' => '#/role', 'hash' => 'role', 'can' => 'admin.roles.*'],
                ['title' => 'general.Permissions', 'href' => '#/permission', 'hash' => 'permission', 'can' => 'admin.permissions.*'],
            ]
        ],
        ['title' => 'general.Pages', 'href' => '#/page', 'hash'=>'page', 'icon'=>'file', 'can' => 'admin.pages.*'],
        ['title' => 'general.Menu', 'href' => '#/menuman', 'hash' => 'menuman', 'icon'=>'list-ul', 'nickname'=>'menu', 'can' => 'admin.menu.*'],
        ['title' => 'general.Widgets', 'href' => '#/widget', 'hash' => 'widget', 'icon'=>'th-large', 'nickname'=>'widget', 'can' => 'admin.widgets.*'],
        ['title' => 'general.Categories', 'href' => '#/category', 'hash' => 'category', 'icon'=>'indent', 'nickname'=>'categories', 'can' => 'admin.category.*'],
    ],
];
