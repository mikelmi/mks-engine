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

    private $aliases = [
        'widget.text' => TextWidget::class,
        'widget.html' => HtmlWidget::class,
        'widget.menu' => MenuWidget::class,
        'widget.languages' => LanguagesWidget::class,
        'widget.category' => CategoryWidget::class,
        'widget.search' => SearchWidget::class,
        'widget.contacts' => ContactsWidget::class
    ];

    public function register()
    {
        $this->app->singleton(WidgetManager::class, function($app) {
            return new WidgetManager($app->tagged('widgets'));
        });

        foreach ($this->aliases as $alias => $abstract) {
            $this->app->alias($abstract, $alias);
        }

        $this->app->tag(array_keys($this->aliases), 'widgets');
    }

    public function provides()
    {
        return array_merge(
            array_keys($this->aliases),
            [WidgetManager::class]
        );
    }
}