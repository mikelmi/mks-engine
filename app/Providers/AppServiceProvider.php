<?php

namespace App\Providers;

use App\Events\PagePathChanged;
use App\Http\ViewComposers\BreadcrumbComposer;
use App\Models\Page;
use App\Repositories\Breadcrumbs;
use App\Repositories\LanguageRepository;
use App\Services\Settings;
use App\Services\TagService;
use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        require_once __DIR__.'/../helpers.php';

        \Blade::directive('widgets', function($expression) {
            $params = explode(',', $expression);
            $position = $params[0];
            $count = 0;
            
            if (count($params) > 1) {
                $count = (int) $params[1];
            }

            return "<?php echo app(\\App\\Services\\WidgetManager::class)->render('$position', $count); ?>";
        });

        \Blade::directive('widget', function($name) {
            return "<?php echo app(\\App\\Services\\WidgetManager::class)->renderOne('$name'); ?>";
        });

        Page::saved(function(Page $page) {
            $oldPath = $page->getOriginal('path');
            $newPath = $page->getAttribute('path');
            if ($oldPath != $newPath) {
                event(new PagePathChanged($oldPath, $newPath));
            }
        });

        \App\Http\Request::setLocales(locales());

        \View::composer('_partials.breadcrumbs', BreadcrumbComposer::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() === 'local') {
            $this->app->register(IdeHelperServiceProvider::class);
            if (config('app.debug')) {
                $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
            }
        }

        /** @var Request $request */
        $request = $this->app['request'];

        if ($this->app->runningInConsole()
            || ($request && starts_with($request->path(), config('admin.url', 'admin')))
        ) {
            $this->app->register(AdminOnlyServicesProvider::class);
        }

        $this->app->singleton(Settings::class, function($app) {
            return new Settings($app['files'], storage_path('app/settings.'.$app->environment().'.json'));
        });

        $this->app->alias(Settings::class, 'settings');
        
        $this->app->singleton(LanguageRepository::class, function($app) {
            return new LanguageRepository($app['settings'], resource_path('data/languages.json'));
        });

        $this->app->singleton(Breadcrumbs::class, function($app) {
            return new Breadcrumbs();
        });

        $this->app->singleton(TagService::class);
        $this->app->alias(TagService::class, \Cviebrock\EloquentTaggable\Services\TagService::class);
    }
}
