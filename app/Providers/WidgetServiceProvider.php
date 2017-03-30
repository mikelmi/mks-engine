<?php
/**
 * Author: mike
 * Date: 24.03.17
 * Time: 17:33
 */

namespace App\Providers;


use App\Services\WidgetManager;
use App\Widgets\CategoryWidget;
use App\Widgets\ContactsWidget;
use App\Widgets\HtmlWidget;
use App\Widgets\LanguagesWidget;
use App\Widgets\MenuWidget;
use App\Widgets\SearchWidget;
use App\Widgets\TextWidget;
use Illuminate\Support\ServiceProvider;

class WidgetServiceProvider extends ServiceProvider
{
    protected $defer = true;

    private $presenters = [
        TextWidget::class,
        HtmlWidget::class,
        MenuWidget::class,
        LanguagesWidget::class,
        CategoryWidget::class,
        SearchWidget::class,
        ContactsWidget::class
    ];

    public function register()
    {
        $this->app->singleton(WidgetManager::class, function($app) {
            return new WidgetManager($app->tagged('widgets'));
        });

        $this->app->tag($this->presenters, 'widgets');
    }

    public function provides()
    {
        return array_merge(
            $this->presenters,
            [WidgetManager::class]
        );
    }
}