<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\Widget;
use App\Models\WidgetRoutes;
use App\Services\WidgetManager;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Mikelmi\MksAdmin\Http\Controllers\AdminController;
use Mikelmi\SmartTable\SmartTable;

class WidgetController extends AdminController
{
    public function index(WidgetManager $widgetManager)
    {
        return view('admin.widget.index', ['types' => $widgetManager->getTypes()]);
    }

    public function data(SmartTable $smartTable)
    {
        $items = Widget::select([
            'id',
            'class',
            'name',
            'title',
            'status',
            'position',
            'ordering'
        ]);

        return $smartTable->make($items)
            ->setSearchColumns(['name', 'title'])
            ->apply()
            ->response();
    }

    public function delete(Request $request, $id = null)
    {
        if ($id === null) {
            $id = $request->get('id', []);
        }

        $res = Widget::whereIn('id',(array)$id)->delete();

        if (!$res) {
            app()->abort(422);
        }

        return response()->json($res);
    }

    public function add(WidgetManager $widgetManager, $class)
    {
        $widget = $widgetManager->make($class);

        $model = new Widget();
        $model->class = $class;

        $widget->setModel($model);

        $model->status = true;

        return view('admin.widget.edit', compact('model', 'widget'));
    }

    public function edit(WidgetManager$widgetManager, $id)
    {
        $model = Widget::findOrFail($id);

        $widget = $widgetManager->make($model->class);
        $widget->setModel($model);

        return view('admin.widget.edit', compact('model', 'widget'));
    }

    public function save(WidgetManager $widgetManager, Request $request, $id = null)
    {
        $this->validate($request, [
           'class' => 'required' 
        ]);
        
        $widget = $widgetManager->make($request->get('class'));
        
        $this->validate($request, array_merge([
            'title' => 'required',
            'name' => 'alpha_dash',
            'position' => 'alpha_dash',
            'ordering' => 'integer'
        ], $widget->rules()));

        $model = $id ? Widget::findOrFail($id) : new Widget();

        $model->class = get_class($widget);

        $model->title = $request->input('title');
        $model->position = $request->input('position');
        $model->lang = $request->input('lang');
        $model->ordering = $request->input('ordering', 0);
        $model->status = $request->input('status', true);
        $model->params = $request->input('params');

        if ($request->exists('name')) {
            $model->name = $request->input('name');
        }

        if (!$model->name) {
            $model->name = str_slug($model->title);
        }
        
        $widget->setModel($model);

        \DB::beginTransaction();

        $widget->beforeSave($request);

        $model->save();

        $showing = $model->param('showing');

        if (!$showing) {
            $model->routes()->delete();
        } else {
            $routes = $request->input('routes', []);
            $routeModels = [];

            if (is_array($routes)) {

                /** @var Collection $exists */
                $exists = $model->routes->keyBy('id');

                foreach ($routes as $i => $name) {
                    if (!$name) {
                        continue;
                    }

                    $params = $request->input('route_params.' . $i);

                    $key = $request->input('route_ids.' . $i);

                    if ($exists->has($key)) {
                        $exists[$key]->route = $name;
                        $exists[$key]->params = $params;
                        $routeModels[] = $exists->get($key);
                    } else {
                        $routeModels[] = new WidgetRoutes([
                            'route' => $name,
                            'params' => $params
                        ]);
                    }
                }

                if ($routeModels) {
                    $model->routes()->saveMany($routeModels);
                }

                $toDelete = array_diff($exists->keys()->all(), $request->input('route_ids', []));

                if ($toDelete) {
                    $model->routes()->whereIn('id', $toDelete)->delete();
                }
            }
        }

        $rolesShowing = $model->param('roles');

        if (!$rolesShowing || $rolesShowing == '1') {
            $model->roles()->detach();
        } else {
            $model->roles()->sync((array)$request->input('roles'));
        }

        \DB::commit();

        $this->flashSuccess(trans('a.Saved'));

        return $this->redirect('/widget');
    }

    function toggle($id)
    {
        $model = Widget::findOrFail($id);
        $model->status = !$model->status;
        $model->save();

        return response()->json([
            'model' => [
                'status' => $model->status
            ]
        ]);
    }

    function toggleBatch(Request $request, $status)
    {
        $id = $request->get('id', []);

        $res = Widget::whereIn('id', $id)->update([
            'status' => $status
        ]);

        if (!$res) {
            app()->abort(402);
        }

        $data = [];
        $models = Widget::whereIn('id', $id)->get();

        foreach($models as $model) {
            $data[$model->id] = [
                'status' => $model->status
            ];
        }

        return response()->json([
            'models' => $data
        ]);
    }

    function move($id, $down = false)
    {
        $model = Widget::findOrFail($id);

        if ($down) {
            $model->ordering++;
        } else {
            $model->ordering--;
        }

        $model->save();

        return response()->json([
            'model' => [
                'ordering' => $model->ordering
            ]
        ]);
    }

    function routes($id = null)
    {
        $model = $id ? Widget::findOrFail($id) : new Widget();

        /** @var Collection $items */
        $items = $model->routes()->get();

        return $items->map(function($item) {
            return [
                'id' => $item->route,
                'params' => $item->params,
                'model_id' => $item->id
            ];
        });
    }

    public function roles($widgetId = null)
    {
        /** @var Collection $all */
        $all = Role::select('id', 'name as text')->get();

        if ($widgetId) {
            $ids = Widget::find($widgetId)->roles()->pluck('id')->toArray();

            if ($ids) {
                $all->each(function ($item) use ($ids) {
                    $item->selected = in_array($item->id, $ids);
                });
            }
        }

        return $all;
    }
}