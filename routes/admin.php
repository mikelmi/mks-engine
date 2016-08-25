<?php

/** @var \Illuminate\Routing\Router $router */
$router = app('router');

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
    $router->get('link-selector.html', function() {
        return view('admin._partial.link-selector');
    });
});