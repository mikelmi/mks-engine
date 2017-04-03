<?php
/**
 * Author: mike
 * Date: 03.04.17
 * Time: 12:47
 */

namespace App\Providers;


use App\Services\CategoryManager;
use App\ServiceTag;
use Illuminate\Support\ServiceProvider;

class CategoryManagerServiceProvider extends ServiceProvider
{
    protected $defer = true;

    public function register()
    {
        $this->app->singleton(CategoryManager::class, function($app) {
            return new CategoryManager($app->tagged(ServiceTag::CATEGORIES));
        });
    }

    public function provides()
    {
        return [
            CategoryManager::class
        ];
    }
}