<?php

namespace App\Http\Controllers\Admin;

use App\Models\Permission;
use Illuminate\Http\Request;
use Mikelmi\MksAdmin\Form\AdminModelForm;
use Mikelmi\MksAdmin\Http\Controllers\AdminController;
use Mikelmi\MksAdmin\Traits\DataGridRequests;
use Mikelmi\MksAdmin\Traits\DeleteRequests;
use Mikelmi\SmartTable\SmartTable;

class PermissionController extends AdminController
{
    use DataGridRequests,
        DeleteRequests;

    public $modelClass = Permission::class;

    protected function dataGridUrl(): string
    {
        return route('admin::permission.index');
    }

    protected function dataGridOptions(): array
    {
        return [
            'title' => __('general.Permission'),
            'createLink' => '#/permission/edit',
            'deleteButton' => route('admin::permission.delete'),
            'columns' => [
                ['key' => 'id', 'title' => 'ID', 'sortable' => true, 'searchable' => true],
                ['key' => 'name', 'title' => __('general.Title'), 'type' => 'link', 'url' => '#/permission/edit/{{row.id}}', 'sortable' => true, 'searchable' => true],
                ['key' => 'display_name', 'title' => __('general.Display Title'), 'sortable' => true, 'searchable' => true],
                ['type' => 'actions', 'actions' => [
                    ['type' => 'edit', 'url' => '#/permission/edit/{{row.id}}'],
                    ['type' => 'delete', 'url' => route('admin::permission.delete')]
                ]],
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

    public function edit(Permission $model)
    {
        $form = new AdminModelForm($model);

        $form->setAction(route('admin::permission.save', $model->id));
        $form->addBreadCrumb(__('general.Permissions'), '#/permission');
        $form->setBackUrl('#/permission');
        $form->setNewUrl('#/permission/edit');

        if ($model->id) {
            $form->addModelField('id', 'ID');
            $form->setDeleteUrl(route('admin::permission.delete', $model->id));
        }

        $fields = [
            ['name' => 'name', 'required' => true, 'label' => __('general.Title')],
            ['name' => 'display_name', 'label' => __('general.Display Title')],
            ['name' => 'description', 'type' => 'textarea', 'label' => __('general.Description')],
        ];

        $form->setFields($fields);

        return $form->response();
    }

    public function save(Request $request, Permission $model)
    {
        $id = $model->id;

        $this->validate($request, [
            'name' => 'required|unique:permissions,name' . ($id ? ','.$id : ''),
        ]);

        $model->name = $request->input('name');
        $model->display_name = $request->input('display_name');
        $model->description = $request->input('description');

        $model->save();

        $this->flashSuccess(trans('general.Saved'));

        return $this->redirect([
            '/permission',
            '/permission/edit',
        ]);
    }
}