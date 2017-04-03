<?php
/**
 * Author: mike
 * Date: 02.04.17
 * Time: 22:51
 */

namespace App\Services;


use App\Contracts\RouteCollector;

class GeneralRouteCollector implements RouteCollector
{

    /**
     * @return array
     */
    public function map(): array
    {
        $users = __('general.Users');

        return [
            'home' => [
                'text' => __('general.Home'),
                'priority' => 100
            ],
            'page' => [
                'text' => __('general.Page'),
                'extended' => true,
                'priority' => 99
            ],

            'login' => [
                'text' => __('user.Auth'),
                'group' => $users,
                'priority' => -97
            ],
            'register' => [
                'text' => __('user.Registration'),
                'group' => $users,
                'priority' => -97
            ],
            'user.profile' => [
                'text' => __('user.My Profile'),
                'group' => $users,
                'priority' => -97
            ],
            'user' => [
                'text' => __('user.Profile'),
                'group' => $users,
                'priority' => -97,
                'extended' => true,
            ],
            'password.request' => [
                'text' => __('auth.Reset Password'),
                'group' => $users,
                'priority' => -97
            ],

            'search' => [
                'text' => __('general.Search'),
                'priority' => -98
            ],

            'language.change' => [
                'text' => __('general.Language'),
                'priority' => -99,
                'extended' => 'select',
            ],

            'filemanager' => [
                'text' => __('filemanager.page_title'),
                'priority' => -100
            ],
        ];
    }
}