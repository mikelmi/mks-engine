<?php

/** @var \Illuminate\Routing\Router $router */
$router = app('router');

$router->get('/', ['as' => 'home', 'uses' => 'PageController@home']);

\Illuminate\Support\Facades\Auth::routes();
$router->get('auth/activation/{token}', 'Auth\RegisterController@activate');

$router->get('captcha.png', 'PageController@captchaImage')->name('captcha.image');

$router->get('page/{id}', ['as' => 'page.id', 'uses' => 'PageController@getById'])->where('id', '\d+');

$router->get('user/profile', 'UserController@profile')->name('user.profile');
$router->get('user/profile/{id}', 'UserController@info')->name('user')->middleware(['permission:user.profile']);
$router->get('user/edit', 'UserController@edit')->name('user.edit');
$router->post('user/save', 'UserController@save')->name('user.save');

//filemanager
$router->group(['prefix'=>'file-manager', 'middleware' => ['can:files.view']], function(\Illuminate\Routing\Router $router) {
    $router->get('/', 'FileManagerController@index')->name('filemanager');
    $router->post('upload', 'FileManagerController@upload')->name('filemanager.upload');
    $router->get('download', 'FileManagerController@download')->name('filemanager.download');
    $router->any('download/multi', 'FileManagerController@downloadMulti')->name('filemanager.downloadMulti');
    $router->any('handler', 'FileManagerController@handle')->name('filemanager.handler');
});

//image thumbnail
$router->get('thumbnail/{path?}', 'FileManagerController@thumbnail')
    ->where('path', '.+')
    ->name('thumbnail');

$router->get('image/{path?}', 'FileManagerController@imageProxy')
    ->where('path', '.+')
    ->name('image.proxy');

//Language
$router->group(['prefix' => 'lang', 'as' => 'lang.'], function(\Illuminate\Routing\Router $router) {
    $router->get('icon/{iso?}', 'LanguageController@icon')->name('icon')
        ->where('iso', '[A-Za-z-_]+');
    $router->get('lang/{iso?}', 'LanguageController@change')->name('change')
        ->where('iso', '[A-Za-z-_]+');
});

//send feedback form
$router->post('contacts', 'ContactsController@send')->name('contacts.send');

//search
$router->get('search', 'SearchController@index')->name('search');