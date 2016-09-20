<?php

/** @var \Illuminate\Routing\Router $router */
$router = app('router');

$router->get('/', ['as' => 'home', 'uses' => 'PageController@home']);

\Illuminate\Support\Facades\Auth::routes();

$router->get('auth/activation/{token}', 'Auth\RegisterController@activate');

$router->get('page/{id}', ['as' => 'page.id', 'uses' => 'PageController@getById'])->where('path', '\d+');

$router->get('{path?}', ['as' => 'page', 'uses' => 'PageController@getByPath'])->where('path', '[A-Za-z0-0-_]+');