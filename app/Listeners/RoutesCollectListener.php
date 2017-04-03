<?php

namespace App\Listeners;


use App\Events\PagePathChanged;
use App\Models\MenuItem;
use App\Models\Page;
use App\Models\WidgetRoutes;
use App\Repositories\LanguageRepository;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Collection;

class RoutesCollectListener
{

    public function subscribe(Dispatcher $events)
    {
        $events->listen('route-params.page.id', self::class . '@onRouteParamsPageId');
        $events->listen('route-params.page', self::class . '@onRouteParamsPagePath');
        $events->listen('route-params.user', self::class . '@onRouteParamsUser');
        $events->listen('route-params.language.change', self::class . '@onRouteParamsLanguage');
        $events->listen(PagePathChanged::class, self::class . '@onPagePathChanged');
    }

    private function collectPageParams(Collection $data, array $columns)
    {
        $pages = Page::ordered()->select($columns);
        $search = request('q');

        if ($search) {
            $pages->where('title', 'like', '%'.$search.'%');
        }

        $pagination = $pages->paginate(10)->toArray();

        //set lang icons
        if (in_array('lang', $columns)) {
            $iconRoute = route('lang.icon');

            foreach ($pagination['data'] as &$item) {
                if (!$item['lang']) {
                    continue;
                }
                $item['lang'] = sprintf('<img src="%s" alt=""> %s', $iconRoute . '/' .$item['lang'], $item['lang']);
            }

            $data->put('html_columns', ['lang']);
        }

        $data->put('items', $pagination['data']);

        unset($pagination['data']);
        $data->put('pagination', $pagination);

        $data->put('title', __('general.Pages'));
    }

    public function onRouteParamsPageId(Collection $data)
    {
        $this->collectPageParams($data, ['id', 'title']);

        $data->put('columns', [
            'id' => 'ID',
            'title' => __('general.Title')
        ]);
    }

    public function onRouteParamsPagePath(Collection $data)
    {
        $this->collectPageParams($data, ['path', 'title', 'lang']);

        $data->put('columns', [
            'title' => __('general.Title'),
            'lang' => __('general.Language'),
            'path' => 'URI',
        ]);
    }

    public function onRouteParamsUser(Collection $data)
    {
        $columns = ['id', 'name', 'email'];

        /** @var Builder $items */
        $items = User::orderBy('name')->active()->select($columns);
        $search = request('q');

        if ($search) {
            $items->where(function($query) use ($search) {
                $query->where('name', 'like', '%'.$search.'%')
                      ->orWhere('name', 'like', '%'.$search.'%');
            });
        }

        $pagination = $items->paginate(10)->toArray();

        $data->put('items', $pagination['data']);

        unset($pagination['data']);
        $data->put('pagination', $pagination);

        $data->put('title', __('general.Users'));

        $data->put('columns', [
            'id' => 'ID',
            'title' => __('general.Title'),
            'email' => 'E-mail',
        ]);
    }

    public function onRouteParamsLanguage(Collection $data)
    {
        /** @var LanguageRepository $langRepo */
        $langRepo = resolve(LanguageRepository::class);
        $items = $langRepo->getSelectList();

        $data->put('items', $items);
        $data->put('title', trans('general.Language'));
    }

    public function onPagePathChanged(PagePathChanged $event)
    {
        $old = $event->getOldPath();
        $new = $event->getNewPath();
        
        if ($old && $new) {

            \DB::beginTransaction();

            if (\DB::getName() == 'mysql') {
                $menuItems = MenuItem::where('params->path', $old)->get();
                $widgetRoutes = WidgetRoutes::where('params->path', $old)->get();
            } else {
                $menuItems = MenuItem::where('params', 'like', '%"path":"' . $old . '"%')->get();
                $widgetRoutes = WidgetRoutes::where('params', 'like', '%"path":"' . $old . '"%')->get();
            }

            foreach ($menuItems as $item) {
                $item->params = ['path' => $new];
                $item->save();
            }

            foreach ($widgetRoutes as $item) {
                $item->params = ['path' => $new];
                $item->save();
            }

            \DB::commit();
        }
    }
}