<?php

namespace App\Listeners;


use App\Events\RoutesCollect;
use App\Models\Page;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Collection;

class RoutesCollectListener
{

    public function subscribe(Dispatcher $events)
    {
        $events->listen(RoutesCollect::class, self::class . '@onRoutesCollect');
        $events->listen('route-params.page.id', self::class . '@onRouteParamsPageId');
        $events->listen('route-params.page', self::class . '@onRouteParamsPagePath');
    }
    
    public function onRoutesCollect(RoutesCollect $event)
    {
        $event->routes->forget('page.id');
        $page = $event->routes->get('page');
        if ($page) {
            $page['text'] = trans('a.Page');
            $page['extended'] = true;
            $event->routes['page'] = $page;
        }
    }

    private function collectParams(Collection $data, array $columns)
    {
        $pages = Page::orderBy('title')->select($columns);
        $search = request('q');

        if ($search) {
            $pages->where('title', 'like', '%'.$search.'%');
        }

        $pagination = $pages->paginate(10)->toArray();

        $data->put('items', $pagination['data']);

        unset($pagination['data']);
        $data->put('pagination', $pagination);

        $data->put('title', trans('a.Pages'));
    }

    public function onRouteParamsPageId(Collection $data)
    {
        $this->collectParams($data, ['id', 'title']);

        $data->put('columns', [
            'id' => 'ID',
            'title' => trans('a.Title')
        ]);
    }

    public function onRouteParamsPagePath(Collection $data)
    {
        $this->collectParams($data, ['path', 'title']);

        $data->put('columns', [
            'path' => 'Path',
            'title' => trans('a.Title')
        ]);
    }
}