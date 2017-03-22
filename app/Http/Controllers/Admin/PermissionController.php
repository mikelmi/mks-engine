<?php

namespace App\Http\Controllers\Admin;

use App\Models\Permission;
use App\Traits\CrudPermissions;
use Illuminate\Http\Request;
use Mikelmi\MksAdmin\Form\AdminModelForm;
use Mikelmi\MksAdmin\Http\Controllers\AdminController;
use Mikelmi\MksAdmin\Traits\CrudRequests;
use Mikelmi\SmartTable\SmartTable;

class PermissionController extends AdminController
{
    use CrudRequests,
        CrudPermissions;

    public $modelClass = Permission::class;

    public $permissionsPrefix = 'permissions';

    protected function dataGridUrl(): string
    {
        return route('admin::permission.index');
    }

    protected function dataGridOptions(): array
    {
        $canEdit = $this->canEdit();
        $canDelete = $this->canDelete();

        $actions = [];

        if ($canEdit) {
            $actions[] = ['type' => 'edit', 'url' => hash_url('permission/edit/{{row.id}}')];
        }

        if ($canDelete) {
            $actions[] = ['type' => 'delete', 'url' => route('admin::permission.delete')];
        }

        return [
            'title' => __('general.Permission'),
            'createLink' =>  $this->canCreate() ? hash_url('permission/create') : false,
            'deleteButton' => $canDelete ? route('admin::permission.delete'): '',
            'columns' => [
                ['key' => 'id', 'title' => 'ID', 'sortable' => true, 'searchable' => true],
                ['key' => 'name', 'title' => __('general.Title'), 'type' => 'link', 'url' => hash_url('permission/show/{{row.id}}'), 'sortable' => true, 'searchable' => true],
                ['key' => 'display_name', 'title' => __('general.Display Title'), 'sortable' => true, 'searchable' => true],
                ['type' => 'actions', 'actions' => $actions]
            ]
        ];
    }

    public function dataGridJson(SmartTable $smartTable)
    {
        $items = Permission::select([
            'id',
            'name',
            'display_name'
        ]);

        return $smartTable->make($items)
            ->setSearchColumns(['name', 'display_name', 'description'])
            ->apply()
            ->response();
    }

    public function form(Permission $model)
    {
        $form = new AdminModelForm($model);

        if ($model->id) {
            $form->setAction(route('admin::permission.update', $model->id));
        } else {
            $form->setAction(route('admin::permission.store'));
        }

        $form->addBreadCrumb(__('general.Permissions'), hash_url('permission'));
        $form->setBackUrl(hash_url('permission'));

        if ($this->canCreate()) {
            $form->setNewUrl(hash_url('permission/create'));
        }

        if ($model->id) {
            $form->addModelField('id', 'ID');

            if ($this->canEdit($model)) {
                $form->setEditUrl(hash_url('permission/edit', $model->id));
            }

            if ($this->canDelete($model)) {
                $form->setDeleteUrl(route('admin::permission.delete', $model->id));
            }
        }

        $fields = [
            ['name' => 'name', 'required' => true, 'label' => __('general.Title')],
            ['name' => 'display_name', 'label' => __('general.Display Title')],
            ['name' => 'description', 'type' => 'textarea', 'label' => __('general.Description')],
        ];

        $form->setFields($fields);

        return $form;
    }

    public function save(Request $request, Permission $model)
    {
        $id = $model->id;

        $this->validate($request, [
            'name' => 'required|unique:permissions,name' . ($id ? ','.$id : ''),
        ]);

        $model->fill($request->only(['name', 'display_name', 'description']));

        $model->save();

        $this->flashSuccess(trans('general.Saved'));

        return $this->redirect([
            '/permission',
            '/permission/create',
        ]);
    }
}