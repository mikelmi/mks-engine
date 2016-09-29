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

//should be at the end
$router->get('{path?}', ['as' => 'page', 'uses' => 'PageController@getByPath'])->where('path', '[A-Za-z0-0-_]+');