<?php
/**
 * Author: mike
 * Date: 03.04.17
 * Time: 13:19
 */

namespace App\Providers;


use App\Services\AdminMenu;
use App\ServiceTag;
use Illuminate\Support\ServiceProvider;
use Mikelmi\MksAdmin\Contracts\MenuManagerContract;

class AdminMenuServiceProvider extends ServiceProvider
{
    protected $defer = true;

    public function register()
    {
        $this->app->bind(MenuManagerContract::class, AdminMenu::class);

        $this->app->singleton(AdminMenu::class, function($app) {
            return new AdminMenu($app['config']->get('admin.menu', []), $app->tagged(ServiceTag::ADMIN_MENU));
        });
    }

    public function provides()
    {
        return [
            MenuManagerContract::class,
            AdminMenu::class
        ];
    }
}