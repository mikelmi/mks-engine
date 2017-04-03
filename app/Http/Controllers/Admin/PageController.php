<?php

namespace App\Http\Controllers\Admin;


use App\Models\Page;
use App\Traits\CrudPermissions;
use Illuminate\Http\Request;
use Mikelmi\MksAdmin\Form\AdminModelForm;
use Mikelmi\MksAdmin\Http\Controllers\AdminController;
use Mikelmi\MksAdmin\Traits\CountItemsResponse;
use Mikelmi\MksAdmin\Traits\CrudRequests;
use Mikelmi\MksAdmin\Traits\TrashRequests;
use Mikelmi\SmartTable\SmartTable;

class PageController extends AdminController
{
    use CrudRequests,
        TrashRequests,
        CountItemsResponse,
        CrudPermissions;

    public $modelClass = Page::class;

    public $countScopes = ['all', 'trash'];

    public $permissionsPrefix = 'pages';

    protected function dataGridUrl($scope = null): string
    {
        return route('admin::page.index', $scope);
    }

    protected function dataGridJson(SmartTable $smartTable, $scope = null)
    {
        $query = $scope == 'trash' ? Page::onlyTrashed() : Page::query();

        $conn = \DB::getName();

        if ($conn == 'sqlite') {
            $path = 'coalesce({table}.lang||"/"||{table}.path, {table}.path) as path';
        } else {
            $path = 'CONCAT_WS("/", {table}.lang, {table}.path) as path';
        }

        $path = \DB::raw(str_replace('{table}', \DB::getTablePrefix().'pages', $path));

        $items = $query->select([
            'id',
            'title',
            'lang',
            $path,
            'created_at',
        ]);

        return $smartTable->make($items)
            ->setSearchColumns(['title', 'path', 'page_text'])
            ->apply()
            ->orderBy('created_at', 'desc')
            ->orderBy('title')
            ->response();
    }

    protected function dataGridOptions($scope = null): array
    {
        $canCreate = $this->canCreate();
        $canEdit = $this->canEdit();
        $canDelete = $this->canDelete();
        $canRestore = $this->canRestore();

        $actions = [];
        $tools = [];

        if ($canEdit) {
            $actions[] = ['type' => 'edit', 'url' => hash_url('page/edit/{{row.id}}')];
        }

        if ($scope == 'trash') {
            if ($canRestore) {
                $actions[] = ['type' => 'restore', 'url' => route('admin::page.restore')];
                $tools[] = ['type' => 'restore', 'url' => route('admin::page.restore')];
            }
        } else {
            if ($canDelete) {
                $actions[] = ['type' => 'trash', 'url' => route('admin::page.toTrash')];
                $tools[] = ['type' => 'trash', 'url' => route('admin::page.toTrash')];
            }
        }

        if ($canDelete) {
            $actions[] = ['type' => 'delete', 'url' => route('admin::page.delete')];
        }

        return [
            'title' => __('general.Pages'),
            'createLink' => $canCreate ? hash_url('page/create') : false,
            'tools' => $tools,
            'deleteButton' => $canDelete ? route('admin::page.delete') : false,
            'columns' => [
                ['key' => 'id', 'sortable' => true, 'searchable' => true],
                ['key' => 'title', 'type' => 'link',  'title'=> __('general.Title'), 'sortable' => true, 'searchable' => true, 'url' => hash_url('page/show/{{row.id}}')],
                ['key' => 'lang', 'title' => __('general.Language'), 'type' => 'language', 'sortable' => true, 'searchable' => true],
                ['key' => 'path', 'type' => $scope == 'trash' ? '':'link', 'title' => 'URL', 'target' => '_blank', 'url' => '{{row.url}}'],
                ['key' => 'created_at', 'type' => 'date', 'title' => __('general.Created at')],
                ['type' => 'actions', 'actions' => $actions],
            ],
            'baseUrl' => hash_url('page'),
            'scopes' => [
                ['title' => __('general.Pages'), 'badge'=>'{{page.model.count_all}}'],
                ['name' => 'trash', 'title' => __('admin::messages.Trash'), 'icon' => 'trash', 'badge'=>'{{page.model.count_trash}}']
            ]
        ];
    }

    protected function formModel($model = null)
    {
        if ($model instanceof Page) {
            return $model;
        }

        return $model ? Page::withTrashed()->find($model) : new Page();
    }

    public function form(Page $model, $mode = null)
    {
        $form = new AdminModelForm($model);

        $form->setAction(route('admin::page.' . ($model->id ? 'update':'store'), $model->id));
        $form->addBreadCrumb(__('general.Pages'), hash_url('page'));
        $form->setBackUrl(hash_url('page'));

        if ($this->canCreate($model)) {
            $form->setNewUrl(hash_url('page/create'));
        }

        if ($model->id) {
            if ($this->canEdit($model)) {
                $form->setEditUrl(hash_url('page/edit', $model->id));
            }
            if ($this->canDelete($model)) {
                $form->setDeleteUrl(route('admin::page.delete', $model->id));
            }
        }

        if ($mode == AdminModelForm::MODE_VIEW && !$model->trashed()) {
            $pathField = ['name' => 'path', 'label' => 'URL', 'type' => 'link', 'value' => $model->url, 'target' => '_blank'];
        } else {
            $pathField = ['name' => 'path', 'label' => 'URL', 'type' => 'checkedInput'];
        }

        $form->addGroup('general', [
            'title' => __('general.Page'),
            'fields' => [
                ['name' => 'title', 'required' => true, 'label' => __('general.Name')],
                ['name' => 'lang', 'type' => 'language'],
                $pathField,
                ['name' => 'page_text', 'label' => __('general.Text'), 'type' => 'editor', 'allowContent'=>true],
            ]
        ]);

        $form->addGroup('seo', [
            'title' => 'SEO',
            'fields' => [
                ['name' => 'seo', 'type' => 'seo', 'value' => [
                    'title' => $model->meta_title,
                    'description' => $model->meta_description,
                    'keywords' => $model->meta_keywords,
                ]],
            ],
        ]);

        $form->addGroup('params', [
            'title' => __('general.Params'),
            'fields' => [
                ['name' => 'params[template]', 'nameSce' => 'params.template', 'type' => 'toggle', 'label' => __('general.Empty Template'), 'value' => $model->param('template')],
                ['name' => 'params[hide_title]', 'nameSce' => 'params.hide_title', 'type' => 'toggle', 'label' => __('general.Hide Title'), 'value' => $model->param('hide_title')],
                ['name' => 'params[roles]', 'nameSce' => 'params.roles', 'type' => 'rolesShow', 'value' => $model->param('roles'), 'model' => $model],
            ],
        ]);

        return $form;
    }

    public function save(Request $request, Page $model)
    {
        $this->validate($request, [
            'title' => 'required|min:3|max:255',
            'path' => 'alpha_dash'
        ]);

        $model->title = $request->input('title');
        $model->page_text = $request->input('page_text');

        if ($request->exists('path')) {
            $model->path = $request->input('path');
        }

        if (!$model->path) {
            $model->path = str_slug($model->title);
        }

        if ($request->exists('lang')) {
            $model->lang = $request->input('lang');
        }

        $model->params = $request->input('params', []);
        $model->meta_title = $request->input('seo.title');
        $model->meta_keywords = $request->input('seo.keywords');
        $model->meta_description = $request->input('seo.description');

        \DB::beginTransaction();

        $model->save();

        $rolesShowing = $model->param('roles');

        $roles = !$rolesShowing || $rolesShowing == '1' ? [] : (array)$request->input('roles');
        $model->syncRoles($roles);

        if ($request->header('X-Submit-Flag') == 2) {
            $model->restore();
        }

        \DB::commit();

        $this->flashSuccess(__('general.Saved'));

        return $this->redirect([
            '/page/scope' . ($model->trashed() ? '/trash' : ''),
            '/page/create',
            '/page'
        ]);
    }

    public function show($id)
    {
        $model = $this->formModel($id);

        return $this->form($model, AdminModelForm::MODE_VIEW)
            ->setupViewMode()
            ->addBreadCrumb($model->title)
            ->response();
    }
}