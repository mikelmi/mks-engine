<?php

/** @var \Illuminate\Routing\Router $router */
$router = app('router');

//Dashboard
//$router->get('home', 'DashboardController@home');

$router->group(['prefix' => 'dashboard', 'as' => 'dashboard.'],
    function(\Illuminate\Routing\Router $router) {
        $router->get('notifications.json', 'DashboardController@notifications')->name('notifications');
        $router->get('notification-details/{uid}', 'DashboardController@notificationDetails')->name('notification.details');
        $router->post('notification-delete/{uid}', 'DashboardController@notificationDelete')->name('notification.delete');
        $router->post('notifications-delete/{all?}', 'DashboardController@notificationsDelete')->name('notifications.delete')->where('all', 'all');
        $router->get('statistics', 'DashboardController@statistics')->name('statistics');
    }
);

//Users
\Mikelmi\MksAdmin\Services\AdminRoute::group('UserController', 'user', null, [
    'toggle' => true,
], function($router) {
    $router->get('/roles/{userId?}', 'UserController@roles')->name('roles');
});


//Roles
\Mikelmi\MksAdmin\Services\AdminRoute::group('RoleController', 'role', null, [], function($router) {
    $router->get('permissions/{id?}', 'RoleController@permissions')->name('permissions');
    $router->get('for-model/{model?}/{id?}', 'RoleController@listForModel')->name('forModel');
});

//Permissions
\Mikelmi\MksAdmin\Services\AdminRoute::group('PermissionController', 'permission');

//Settings
$router->group(['prefix' => 'settings', 'middleware' => 'permission:admin.settings.*'], function($router) {
    $router->get('/{scope?}', 'SettingsController@index')
        ->name('settings')
        ->where('scope', '[a-z]+');
    $router->post('/{scope?}', 'SettingsController@save')
        ->name('settings.save')
        ->where('scope', '[a-z]+')
        ->middleware('permission:admin.settings.edit');
});

//Page
\Mikelmi\MksAdmin\Services\AdminRoute::group('PageController', 'page', null, ['trash' => true]);

//Routes
$router->group(['prefix'=>'route'], function(\Illuminate\Routing\Router $router) {
    $router->get('/', ['as' => 'routes', 'uses' => 'RouteController@all']);
    $router->get('/params/{name?}', ['as' => 'route.params', 'uses' => 'RouteController@params']);
});

//Angular Templates
$router->get('templates/{path}.html', 'HelperController@template');

//Menu
$router->group(['prefix'=>'menuman', 'as' => 'menu.', 'middleware' => ['permission:admin.menu*']],
    function(\Illuminate\Routing\Router $router) {
        $router->get('list', 'MenuController@all')->name('list');
        $router->post('save', 'MenuController@save')->name('save')->middleware('permission:admin.menu.edit');
        $router->post('delete', 'MenuController@delete')->name('delete')->middleware('permission:admin.menu.delete');

        $router->get('items/{scope}', 'MenuController@items')->where('scope', '\d+')->name('items');
        $router->post('items/{scope}/move/{id}', 'MenuController@moveItem')->name('move')->middleware('permission:admin.menu.edit')
            ->where('scope', '\d+')
            ->where('id', '\d+');

        $router->post('items/delete/{id}', 'MenuController@deleteItem')->name('items.delete')->middleware('permission:admin.menu.delete')
            ->where('id', '\d+');

        $router->get('items/{scope}/edit/{id?}', 'MenuController@editItem')->name('items.edit')->middleware('permission:admin.menu.edit')
            ->where('scope', '\d+')
            ->where('id', '\d+');

        $router->post('items/{scope}/save/{id?}', 'MenuController@saveItem')->name('items.save')->middleware('permission:admin.menu.edit')
            ->where('scope', '\d+')
            ->where('id', '\d+');

        $router->get('/tree/options/{scope}/{id?}', 'MenuController@treeOptions')->name('tree.options')
            ->where('scope', '\d+')
            ->where('id', '\d+');

        $router->get('/{scope?}', 'MenuController@index')->name('index')->where('scope', '\d+');
    }
);

//Widgets
\Mikelmi\MksAdmin\Services\AdminRoute::group('WidgetController', 'widget', null, [
    'toggle' => true,
    'move' => true,
], function($router) {
    $router->get('routes/{id?}', 'WidgetController@routes')->name('routes');
    $router->get('create/{class}', 'WidgetController@create')->name('create')
        ->middleware('permission:admin.widgets.create')
        ->where('class', '.+');
});

//File Manager
$router->get('file-manager', 'HelperController@fileManager');

//Languages
\Mikelmi\MksAdmin\Services\AdminRoute::group('LanguageController', 'language', null, [
    'middleware' => ['permission:admin.lang.*'],
    'toggle' => true
], function($router) {
    $router->get('data.json', 'LanguageController@data')->name('data');
    $router->get('all.json', 'LanguageController@all')->name('all');
    $router->post('set-default/{iso?}', 'LanguageController@setDefault')->name('setDefault');
    $router->get('select/{iso?}', 'LanguageController@getSelectList')->name('select');
});

//Category
$router->group(['prefix' => 'category', 'as' => 'category.'], function(\Illuminate\Routing\Router $router) {
    $router->get('sections', 'CategoryController@sections')->name('sections');
    $router->post('save-section', 'CategoryController@saveSection')->name('save.section');
    $router->post('delete-section', 'CategoryController@deleteSection')->name('delete.section');

    $router->get('categories/{scope}', 'CategoryController@categories')->name('categories')
        ->where('scope', '\d+');

    $router->post('move/{scope}/{id}', 'CategoryController@move')->name('move')->middleware('permission:admin.category.edit')
        ->where('scope', '\d+')
        ->where('id', '\d+');

    $router->post('delete/{id}', 'CategoryController@delete')->name('delete')->middleware('permission:admin.category.delete')
        ->where('id', '\d+');

    $router->get('edit/{scope}/{id?}', 'CategoryController@edit')->name('edit')->middleware('permission:admin.category.edit')
        ->where('scope', '\d+')
        ->where('id', '\d+');

    $router->post('save/{scope}/{id?}', 'CategoryController@save')->name('save')->middleware('permission:admin.category.save')
        ->where('scope', '\d+')
        ->where('id', '\d+');

    $router->get('/tree/options/{scope}/{id?}', 'CategoryController@treeOptions')->name('tree.options')
        ->where('scope', '\d+')
        ->where('id', '\d+');

    $router->get('select/{type?}', 'CategoryController@select')->name('select')
        ->where('type', '.+');

    $router->get('/{scope?}', 'CategoryController@index')->name('index')
        ->where('scope', '\d+');
});

//Tags
\Mikelmi\MksAdmin\Services\AdminRoute::group('TagController', 'tags', null, [
    'middleware' => ['permission:admin.tags.*'],
], function($router) {
    $router->get('all/{type}', 'TagController@all')->name('all')->where('type', '.+');
});

//Icons
$router->get('icons.json', 'HelperController@icons')->name('icons');

$router->get('artisan', 'ArtisanController@index')->name('artisan');
$router->get('artisan/commands', 'ArtisanController@commands')->name('artisan.commands');
$router->post('artisan', 'ArtisanController@run')->name('artisan.run');