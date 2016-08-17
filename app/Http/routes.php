<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

//Admin routes
Route::group(
    [
        'prefix' => config('admin.url', 'admin'),
        'namespace' => 'Admin',
        'as' => 'admin::'
    ],

    function (\Illuminate\Routing\Router $router) {
        //Users
        $router->get('users', ['as' => 'users', 'uses' => 'UserController@index']);
        $router->get('users/data.json', ['as' => 'users.data', 'uses' => 'UserController@data']);
        $router->post('user/delete/{id?}', ['as' => 'user.delete', 'uses' => 'UserController@delete']);
        $router->get('user/edit/{id?}', ['as' => 'user.edit', 'uses' => 'UserController@edit']);
        $router->post('user/save/{id?}', ['as' => 'user.save', 'uses' => 'UserController@save']);
        $router->get('user/roles/{id?}', ['as' => 'user.roles', 'uses' => 'UserController@roles']);
        $router->post('user/toggle/{id?}', ['as' => 'user.toggle', 'uses' => 'UserController@toggle']);
        $router->post('user/toggle-batch/{status}', ['as'=>'user.toggleBatch', 'uses'=>'UserController@toggleBatch']);

        //Roles
        $router->get('roles', ['as' => 'roles', 'uses' => 'RoleController@index']);
        $router->get('roles/data.json', ['as' => 'roles.data', 'uses' => 'RoleController@data']);
        $router->post('role/delete/{id?}', ['as' => 'role.delete', 'uses' => 'RoleController@delete']);
        $router->get('role/edit/{id?}', ['as' => 'role.edit', 'uses' => 'RoleController@edit']);
        $router->post('role/save/{id?}', ['as' => 'role.save', 'uses' => 'RoleController@save']);
        $router->get('role/permissions/{id?}', ['as' => 'role.permissions', 'uses' => 'RoleController@permissions']);

        //Permissions
        $router->get('permissions', ['as' => 'permissions', 'uses' => 'PermissionController@index']);
        $router->get('permissions/data.json', ['as' => 'permissions.data', 'uses' => 'PermissionController@data']);
        $router->post('permission/delete/{id?}', ['as' => 'permission.delete', 'uses' => 'PermissionController@delete']);
        $router->get('permission/edit/{id?}', ['as' => 'permission.edit', 'uses' => 'PermissionController@edit']);
        $router->post('permission/save/{id?}', ['as' => 'permission.save', 'uses' => 'PermissionController@save']);
        
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
    }
);


//Admin routes
Route::group(
    [
        'middleware' => 'frontend'
    ],

    function (\Illuminate\Routing\Router $router) {
        $router->get('/', function () {
            return view('welcome');
        });

        $router->get('/page/{id}', ['as' => 'page.id', 'uses' => 'PageController@getById'])->where('path', '\d+');
        $router->get('/{path?}', ['as' => 'page', 'uses' => 'PageController@getByPath'])->where('path', '[a-zA-Z0-9-_]+');
    }
);