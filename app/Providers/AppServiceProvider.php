<?php

namespace App\Providers;

use App\Events\PagePathChanged;
use App\Models\Page;
use App\Services\LanguageManager;
use App\Services\Settings;
use App\Services\WidgetManager;
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

        \Blade::directive('widgets', function($position) {
            return "<?php echo app(\App\Services\WidgetManager::class)->render('$position'); ?>";
        });

        \Blade::directive('widget', function($name) {
            return "<?php echo app(\App\Services\WidgetManager::class)->renderOne('$name'); ?>";
        });

        Page::saved(function(Page $page) {
            $oldPath = $page->getOriginal('path');
            $newPath = $page->getAttribute('path');
            if ($oldPath != $newPath) {
                event(new PagePathChanged($oldPath, $newPath));
            }
        });

        //set theme
        if ($theme = $this->app[Settings::class]->get('site.theme')) {
            $this->app['theme']->set($theme);
        }
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
            $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
        }

        /** @var Request $request */
        $request = $this->app['request'];

        if ($request && starts_with($request->path(), config('admin.url', 'admin'))) {
            $this->app->register(AdminEventServiceProvider::class);
        }

        $this->app->singleton(Settings::class, function($app) {
            return new Settings($app['files'], storage_path('app/settings.'.$app->environment().'.json'));
        });

        $this->app->alias(Settings::class, 'settings');

        $this->app->singleton(WidgetManager::class, function() {
            return new WidgetManager();
        });
        
        $this->app->singleton(LanguageManager::class, function($app) {
            return new LanguageManager($app['settings'], resource_path('data/languages.json'));
        });
    }
}
