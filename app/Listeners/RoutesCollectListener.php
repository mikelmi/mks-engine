<?php

namespace App\Listeners;


use App\Events\PagePathChanged;
use App\Events\RoutesCollect;
use App\Models\MenuItem;
use App\Models\Page;
use App\Models\WidgetRoutes;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Collection;

class RoutesCollectListener
{

    public function subscribe(Dispatcher $events)
    {
        $events->listen(RoutesCollect::class, self::class . '@onRoutesCollect');
        $events->listen('route-params.page.id', self::class . '@onRouteParamsPageId');
        $events->listen('route-params.page', self::class . '@onRouteParamsPagePath');
        $events->listen(PagePathChanged::class, self::class . '@onPagePathChanged');
    }
    
    public function onRoutesCollect(RoutesCollect $event)
    {
        $event->routes->forget('page.id');
        $page = $event->routes->get('page');
        if ($page) {
            $page['text'] = trans('general.Page');
            $page['extended'] = true;
            $event->routes['page'] = $page;
        }
    }

    private function collectParams(Collection $data, array $columns)
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
                $item['title'] = '<h1>'.$item['title'].'</h1>';
                $item['lang'] = sprintf('<img src="%s" alt=""> %s', $iconRoute . '/' .$item['lang'], $item['lang']);
            }

            $data->put('html_columns', ['lang']);
        }

        $data->put('items', $pagination['data']);

        unset($pagination['data']);
        $data->put('pagination', $pagination);

        $data->put('title', trans('general.Pages'));
    }

    public function onRouteParamsPageId(Collection $data)
    {
        $this->collectParams($data, ['id', 'title']);

        $data->put('columns', [
            'id' => 'ID',
            'title' => trans('general.Title')
        ]);
    }

    public function onRouteParamsPagePath(Collection $data)
    {
        $this->collectParams($data, ['path', 'title', 'lang']);

        $data->put('columns', [
            'title' => trans('general.Title'),
            'lang' => trans('general.Language'),
            'path' => 'Path',
        ]);
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