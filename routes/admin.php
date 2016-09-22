<?php

/** @var \Illuminate\Routing\Router $router */
$router = app('router');

//Dashboard
$router->get('home', 'DashboardController@home');
$router->group(['prefix'=>'dashboard', 'middleware' => ['permission:admin.dashboard*']], function(\Illuminate\Routing\Router $router) {
    $router->get('notifications.json', ['as' => 'dashboard.notifications', 'uses' => 'DashboardController@notifications']);
    $router->get('notification-details/{uid}', ['as' => 'dashboard.notification.details', 'uses' => 'DashboardController@notificationDetails']);
    $router->post('notification-delete/{uid}', ['as' => 'dashboard.notification.delete', 'uses' => 'DashboardController@notificationDelete']);
    $router->post('notifications-delete/{all?}', ['as' => 'dashboard.notifications.delete', 'uses' => 'DashboardController@notificationsDelete'])->where('all', 'all');
    $router->get('statistics', ['as' => 'dashboard.statistics', 'uses' => 'DashboardController@statistics']);
});

$router->get('test', function() {
    $users = \App\User::where('active', true)->get();

    foreach ($users as $user) {
        event(new \App\Events\UserRegistered($user));
    }

    return ['count' => $users->count()];
});

//Users
$router->group(['prefix'=>'user', 'middleware' => ['permission:admin.user*']], function(\Illuminate\Routing\Router $router) {
    $router->get('/', ['as' => 'users', 'uses' => 'UserController@index']);
    $router->get('data.json', ['as' => 'users.data', 'uses' => 'UserController@data']);
    $router->post('delete/{id?}', ['as' => 'user.delete', 'uses' => 'UserController@delete']);
    $router->get('edit/{id?}', ['as' => 'user.edit', 'uses' => 'UserController@edit']);
    $router->post('save/{id?}', ['as' => 'user.save', 'uses' => 'UserController@save']);
    $router->get('roles/{id?}', ['as' => 'user.roles', 'uses' => 'UserController@roles']);
    $router->post('toggle/{id?}', ['as' => 'user.toggle', 'uses' => 'UserController@toggle']);
    $router->post('toggle-batch/{status}', ['as'=>'user.toggleBatch', 'uses'=>'UserController@toggleBatch']);
});

//Roles
$router->group(['prefix'=>'role', 'middleware' => ['permission:admin.role*']], function(\Illuminate\Routing\Router $router) {
    $router->get('/', ['as' => 'roles', 'uses' => 'RoleController@index']);
    $router->get('data.json', ['as' => 'roles.data', 'uses' => 'RoleController@data']);
    $router->post('delete/{id?}', ['as' => 'role.delete', 'uses' => 'RoleController@delete']);
    $router->get('edit/{id?}', ['as' => 'role.edit', 'uses' => 'RoleController@edit']);
    $router->post('save/{id?}', ['as' => 'role.save', 'uses' => 'RoleController@save']);
    $router->get('permissions/{id?}', ['as' => 'role.permissions', 'uses' => 'RoleController@permissions']);
    $router->get('for-model/{model?}/{id?}', ['as' => 'roles.forModel', 'uses' => 'RoleController@listForModel']);
});

//Permissions
$router->group(['prefix'=>'permission', 'middleware' => ['permission:admin.permission*']], function(\Illuminate\Routing\Router $router) {
    $router->get('/', ['as' => 'permissions', 'uses' => 'PermissionController@index']);
    $router->get('data.json', ['as' => 'permissions.data', 'uses' => 'PermissionController@data']);
    $router->post('delete/{id?}', ['as' => 'permission.delete', 'uses' => 'PermissionController@delete']);
    $router->get('edit/{id?}', ['as' => 'permission.edit', 'uses' => 'PermissionController@edit']);
    $router->post('save/{id?}', ['as' => 'permission.save', 'uses' => 'PermissionController@save']);
});

//Settings
$router->get('settings/{scope?}', ['as' => 'settings', 'uses' => 'SettingsController@index'])->where('scope', '[a-z]+');
$router->post('settings/{scope?}', ['as' => 'settings.save', 'uses' => 'SettingsController@save'])->where('scope', '[a-z]+');

//Page
$router->group(['prefix'=>'page', 'middleware' => ['permission:admin.page*']], function(\Illuminate\Routing\Router $router) {
    $router->get('/{scope?}', ['as' => 'pages', 'uses' => 'PageController@index'])->where('scope', 'trash');
    $router->get('data/{scope?}', ['as' => 'pages.data', 'uses' => 'PageController@data']);
    $router->post('delete/{id?}', ['as' => 'page.delete', 'uses' => 'PageController@delete']);
    $router->get('edit/{id?}', ['as' => 'page.edit', 'uses' => 'PageController@edit']);
    $router->post('save/{id?}', ['as' => 'page.save', 'uses' => 'PageController@save']);
    $router->post('trash/{id?}', ['as' => 'page.toTrash', 'uses' => 'PageController@toTrash']);
    $router->post('restore/{id?}', ['as' => 'page.restore', 'uses' => 'PageController@restore']);
});

//Routes
$router->group(['prefix'=>'route'], function(\Illuminate\Routing\Router $router) {
    $router->get('/', ['as' => 'routes', 'uses' => 'RouteController@all']);
    $router->get('/params/{name?}', ['as' => 'route.params', 'uses' => 'RouteController@params']);
});

//Angular Templates
$router->group(['prefix'=>'templates', 'middleware' => ['admin', 'admin.locale']], function(\Illuminate\Routing\Router $router) {
    $router->get('/{path}.html', function($path) {
        return view('admin._partial.'.$path);
    });
});

//Menu
$router->group(['prefix'=>'menuman', 'middleware' => ['permission:admin.menu*']], function(\Illuminate\Routing\Router $router) {
    $router->get('list', ['as' => 'menu.list', 'uses' => 'MenuController@all']);
    $router->post('save', ['as' => 'menu.save', 'uses' => 'MenuController@save']);
    $router->post('delete', ['as' => 'menu.delete', 'uses' => 'MenuController@delete']);

    $router->get('items/{scope}', ['as' => 'menu.items', 'uses' => 'MenuController@items'])->where('scope', '\d+');
    $router->post('items/{scope}/move/{id}', ['as' => 'menu.move', 'uses' => 'MenuController@moveItem'])
        ->where('scope', '\d+')
        ->where('id', '\d+');

    $router->post('items/delete/{id}', ['as' => 'menu.items.delete', 'uses' => 'MenuController@deleteItem'])->where('id', '\d+');

    $router->get('items/{scope}/edit/{id?}', ['as' => 'menu.items.edit', 'uses' => 'MenuController@editItem'])
        ->where('scope', '\d+')
        ->where('id', '\d+');

    $router->post('items/{scope}/save/{id?}', ['as' => 'menu.items.save', 'uses' => 'MenuController@saveItem'])
        ->where('scope', '\d+')
        ->where('id', '\d+');

    $router->get('/tree/options/{scope}/{id?}', ['as' => 'menu.tree.options', 'uses' => 'MenuController@treeOptions'])
        ->where('scope', '\d+')
        ->where('id', '\d+');

    $router->get('/{scope?}', ['as' => 'menu', 'uses' => 'MenuController@index'])->where('scope', '\d+');
});

//Widgets
$router->group(['prefix'=>'widget', 'middleware' => ['permission:admin.widget*']], function(\Illuminate\Routing\Router $router) {
    $router->get('/', ['as' => 'widgets', 'uses' => 'WidgetController@index']);
    $router->get('data.json', ['as' => 'widgets.data', 'uses' => 'WidgetController@data']);
    $router->post('delete/{id?}', ['as' => 'widget.delete', 'uses' => 'WidgetController@delete']);
    $router->get('add/{class}', ['as' => 'widgets.add', 'uses' => 'WidgetController@add'])->where('class', '.+');
    $router->get('edit/{id}', ['as' => 'widget.edit', 'uses' => 'WidgetController@edit']);
    $router->post('save/{id?}', ['as' => 'widget.save', 'uses' => 'WidgetController@save']);
    $router->post('toggle/{id?}', ['as' => 'widget.toggle', 'uses' => 'WidgetController@toggle']);
    $router->post('toggle-batch/{status}', ['as'=>'widget.toggleBatch', 'uses'=>'WidgetController@toggleBatch']);
    $router->post('move/{id?}/{down?}', ['as' => 'widget.move', 'uses' => 'WidgetController@move']);
    $router->get('routes/{id?}', ['as' => 'widgets.routes', 'uses' => 'WidgetController@routes']);
});