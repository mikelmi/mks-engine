<?php

namespace App\Http\Controllers\Admin;


use App\DataGrid\Tools\ButtonWidgetCreate;
use App\Models\Widget;
use App\Models\WidgetRoutes;
use App\Services\WidgetManager;
use App\Traits\CrudPermissions;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Mikelmi\MksAdmin\Form\AdminForm;
use Mikelmi\MksAdmin\Form\AdminModelForm;
use Mikelmi\MksAdmin\Http\Controllers\AdminController;
use Mikelmi\MksAdmin\Traits\CrudRequests;
use Mikelmi\MksAdmin\Traits\MoveRequests;
use Mikelmi\MksAdmin\Traits\ToggleRequests;
use Mikelmi\SmartTable\SmartTable;

class WidgetController extends AdminController
{
    use CrudRequests,
        CrudPermissions,
        ToggleRequests,
        MoveRequests;

    public $modelClass = Widget::class;

    public $toggleField = 'active';

    public $permissionsPrefix = 'widgets';

    /**
     * @var WidgetManager
     */
    private $widgetManager;

    protected function init()
    {
        $this->widgetManager = resolve(WidgetManager::class);
    }


    public function index2(WidgetManager $widgetManager)
    {
        return view('admin.widget.index', [
            'types' => $widgetManager->getTypes(),
            'lang_icon_url' => route('lang.icon')
        ]);
    }

    protected function dataGridUrl(): string
    {
        return route('admin::widget.index');
    }

    public function dataGridJson(SmartTable $smartTable)
    {
        $items = Widget::select([
            'id',
            'class',
            'name',
            'title',
            'active',
            'position',
            'priority',
            'lang'
        ]);

        return $smartTable->make($items)
            ->setSearchColumns(['name', 'title'])
            ->orderBy('priority', 'desc')
            ->apply()
            ->response();
    }

    protected function dataGridOptions(): array
    {
        $canEdit = $this->canEdit();
        $canDelete = $this->canDelete();
        $canCreate = $this->canCreate();
        $canToggle = $this->canToggle();

        $actions = [];
        $links = [];

        if ($canEdit) {
            $actions[] = ['type' => 'edit', 'url' => hash_url('widget/edit/{{row.id}}')];
        }

        if ($canDelete) {
            $actions[] = ['type' => 'delete', 'url' => route('admin::widget.delete')];
        }

        if ($canCreate) {
            $links[] = new ButtonWidgetCreate();
        }

        return [
            'title' => __('general.Widgets'),
            'links' => $links,
            'toggleButton' => $canToggle ?
                [route('admin::widget.toggle.batch', 1), route('admin::widget.toggle.batch', 0)] : false,
            'deleteButton' => $canDelete ? route('admin::widget.delete') : false,
            'columns' => [
                ['key' => 'id', 'title' => 'ID', 'sortable' => true, 'searchable' => true],
                ['key' => 'title', 'title' => __('general.Title'), 'type' => 'link', 'url' => hash_url('widget/show/{{row.id}}'), 'sortable' => true, 'searchable' => true],
                ['key' => 'class', 'displayKey' => 'class_title', 'title' => __('general.Type'),
                    'type' => 'list', 'options' => $this->widgetManager->getPresentersList(),
                    'sortable' => true, 'searchable' => true],
                ['key' => 'active', 'title' => __('general.Status'), 'type' => 'status', 'url' => route('admin::widget.toggle'),
                    'sortable' => true, 'searchable' => true,
                    'disabled' => !$canToggle
                ],
                ['key' => 'position', 'title' => __('general.Position'), 'sortable' => true, 'searchable' => true],
                ['key' => 'lang', 'title' => __('general.Language'), 'type' => 'language', 'sortable' => true, 'searchable' => true],
                ['key' => 'priority', 'title' => __('general.Priority'), 'type' => 'priority', 'url' => route('admin::widget.move'), 'sortable' => true, 'searchable' => true],
                ['type' => 'actions', 'actions' => $actions],
            ],
            'rowAttributes' => [
                'ng-class' => "{'table-warning': !row.active}"
            ]
        ];
    }

    public function create($class)
    {
        $model = new Widget();
        $model->class = $this->widgetManager->presenter($class)->alias();

        $form = $this->form($model, AdminForm::MODE_CREATE);

        $form->setupCreateMode();

        return $form->response();
    }

    protected function form(Widget $model, $mode = null): AdminForm
    {
        $form = new AdminModelForm($model);

        $form->setAction(route('admin::widget.' . ($model->id ? 'update' : 'store'), $model->id));
        $form->addBreadCrumb(__('general.Widgets'), hash_url('widget'));
        $form->setBackUrl(hash_url('widget'));

        $fields = [];

        if ($model->id) {
            $fields[] = ['name' => 'id', 'label' => 'ID'];

            if ($this->canEdit($model)) {
                $form->setEditUrl(hash_url('widget/edit', $model->id));
            }

            if ($this->canDelete($model)) {
                $form->setDeleteUrl(route('admin::widget.delete', $model->id));
            }
        }

        $fields = array_merge($fields, [
            ['name' => 'class_title', 'label' => __('general.Type'), 'type' => 'staticText'],
            ['name' => 'class', 'type' => 'hidden'],
            ['name' => 'lang', 'type' => 'language'],
            ['name' => 'title', 'label' => __('general.Title'), 'required' => true],
            ['name' => 'name', 'label' => __('general.Name'), 'type' => 'checkedInput'],
            ['name' => 'position', 'label' => __('general.Position')],
            ['name' => 'priority', 'label' => __('general.Priority'), 'type' => 'number'],
            ['name' => 'active', 'type' => 'toggle', 'label' => __('general.Active')],
        ]);

        $form->addGroup('general', [
            'title' => __('general.Widget'),
            'fields' => $fields
        ]);

        $presenter = $this->widgetManager->exists($model->class) ? $this->widgetManager->presenter($model->class) : null;

        if ($presenter) {
            $presenter->setModel($model);
            $presenter->form($form, $mode);
        }

        $form->addGroup('params', [
            'title' => __('general.Params'),
            'fields' => [
                ['name' => 'params[show_title]', 'nameSce' => 'params.show_title', 'label' => __('general.Show Title'),
                    'type' => 'toggle', 'value' => $model->param('show_title')],
                ['name' => 'params[in_block]', 'nameSce' => 'params.in_block', 'label' => __('general.In Block'),
                    'type' => 'toggle', 'value' => $model->param('in_block')],
                ['name' => 'params[attr]', 'nameSce' => 'params.attr', 'label' => __('general.html_attr'),
                    'type' => 'assoc', 'value' => $model->param('attr')],
                ['name' => 'params[roles]', 'nameSce' => 'params.roles', 'type' => 'rolesShow', 'value' => $model->param('roles'), 'model' => $model],
                ['name' => 'params[showing]', 'nameSce' => 'params.showing', 'type' => 'routesShow', 'url' => route('admin::widget.routes', $model->id),
                    'value' => $model->param('showing')
                ]
            ]
        ]);

        return $form;
    }

    public function save(Request $request, Widget $model)
    {
        $this->validate($request, [
            'class' => 'required',
            'title' => 'required',
            'name' => 'alpha_dash',
            'position' => 'alpha_dash',
            'priority' => 'integer'
        ]);

        $class = $request->get('class');

        $presenter = $this->widgetManager->exists($class) ? $this->widgetManager->presenter($class) : null;
        
        if ($presenter) {
            $this->validate($request, $presenter->rules());
        }

        $model->class = $presenter ? $presenter->alias() : $class;
        $model->title = $request->input('title');
        $model->position = $request->input('position');
        $model->lang = $request->input('lang');
        $model->priority = $request->input('priority');
        $model->active = $request->input('active', true);
        $model->content = $request->input('content', '');
        $model->params = $request->input('params');

        if ($request->exists('name')) {
            $model->name = $request->input('name');
        }

        if (!$model->name) {
            $model->name = str_slug($model->title);
        }
        
        $presenter->setModel($model);

        \DB::beginTransaction();

        $presenter->beforeSave($request);

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

        $roles = !$rolesShowing || $rolesShowing == '1' ? [] : (array)$request->input('roles');
        $model->syncRoles($roles);

        \DB::commit();

        $this->flashSuccess(trans('general.Saved'));

        return $this->redirect('/widget');
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
}