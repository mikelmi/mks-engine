<?php

/** @var \Illuminate\Routing\Router $router */
$router = app('router');

$router->get('/', ['as' => 'home', 'uses' => 'PageController@home']);

\Illuminate\Support\Facades\Auth::routes();
$router->get('auth/activation/{token}', 'Auth\RegisterController@activate');

$router->get('captcha.png', 'PageController@captchaImage')->name('captcha.image');

$router->get('page/{id}', ['as' => 'page.id', 'uses' => 'PageController@getById'])->where('path', '\d+');

$router->get('user/profile', 'UserController@profile')->name('user.profile');
$router->get('user/profile/{id}', 'UserController@info')->name('user')->middleware(['permission:user.profile']);
$router->get('user/edit', 'UserController@edit')->name('user.edit');
$router->post('user/save', 'UserController@save')->name('user.save');

//filemanager
$router->group(['prefix'=>'file-manager', 'middleware' => ['can:upload']], function(\Illuminate\Routing\Router $router) {
    $router->get('/', 'FileManagerController@index')->name('filemanager');
    $router->post('upload', 'FileManagerController@upload')->name('filemanager.upload');
    $router->get('download', 'FileManagerController@download')->name('filemanager.download');
    $router->any('download/multi', 'FileManagerController@downloadMulti')->name('filemanager.downloadMulti');
    $router->any('handler', 'FileManagerController@handle')->name('filemanager.handler');
});

$router->get('thumbnail/{path}', 'FileManagerController@thumbnail')
    ->where('path', '.+')
    ->name('thumbnail');

//should be at the end
$router->get('{path?}', ['as' => 'page', 'uses' => 'PageController@getByPath'])->where('path', '[A-Za-z0-0-_]+');