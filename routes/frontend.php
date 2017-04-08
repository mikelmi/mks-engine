<?php

/** @var \Illuminate\Routing\Router $router */
$router = app('router');

$router->get('/', 'PageController@home')->name('home');

//Auth
\Illuminate\Support\Facades\Auth::routes();
$router->get('auth/activation/{token}', 'Auth\RegisterController@activate');

//Captcha
$router->get('captcha.png', 'PageController@captchaImage')->name('captcha.image');

//Page
$router->get('page/{id}', 'PageController@getById')->name('page.id')
    ->where('id', '\d+');

//User
$router->group(['prefix' => 'user', 'as' => 'user.'], function(\Illuminate\Routing\Router $router) {
    $router->get('profile', 'UserController@profile')->name('profile');
    $router->get('profile/{id}', 'UserController@info')->name('info')->middleware(['permission:user.profile']);
    $router->get('edit', 'UserController@edit')->name('edit');
    $router->post('save', 'UserController@save')->name('save');
});


//FileManager
$router->group(['prefix'=>'file-manager', 'middleware' => ['can:files.view']], function(\Illuminate\Routing\Router $router) {
    $router->get('/', 'FileManagerController@index')->name('filemanager');
    $router->post('upload', 'FileManagerController@upload')->name('filemanager.upload');
    $router->get('download', 'FileManagerController@download')->name('filemanager.download');
    $router->any('download/multi', 'FileManagerController@downloadMulti')->name('filemanager.downloadMulti');
    $router->any('handler', 'FileManagerController@handle')->name('filemanager.handler');
});

//Image thumbnail
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