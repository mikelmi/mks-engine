<?php
/**
 * Author: mike
 * Date: 24.03.17
 * Time: 17:33
 */

namespace App\Providers;


use App\Services\WidgetManager;
use App\ServiceTag;
use App\Widgets\CategoryWidget;
use App\Widgets\ContactsWidget;
use App\Widgets\HtmlWidget;
use App\Widgets\LanguagesWidget;
use App\Widgets\MenuWidget;
use App\Widgets\PhotoGalleryWidget;
use App\Widgets\SearchWidget;
use App\Widgets\SliderWidget;
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
        ContactsWidget::class,
        PhotoGalleryWidget::class,
        SliderWidget::class
    ];

    public function register()
    {
        $this->app->singleton(WidgetManager::class, function($app) {
            return new WidgetManager($app->tagged(ServiceTag::WIDGETS));
        });

        $this->app->tag($this->presenters, ServiceTag::WIDGETS);
    }

    public function provides()
    {
        //TODO: check if $this->presenters needed here
        return [WidgetManager::class];
    }
}