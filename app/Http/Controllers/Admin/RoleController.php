<?php

namespace App\Http\Controllers\Admin;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Mikelmi\MksAdmin\Form\AdminModelForm;
use Mikelmi\MksAdmin\Http\Controllers\AdminController;
use Mikelmi\MksAdmin\Traits\CrudRequests;
use Mikelmi\SmartTable\SmartTable;

class RoleController extends AdminController
{
    use CrudRequests;

    public $modelClass = Role::class;

    protected function dataGridUrl(): string
    {
        return route('admin::role.index');
    }

    protected function dataGridOptions(): array
    {
        return [
            'title' => __('general.Roles'),
            'createLink' => hash_url('role/create'),
            'deleteButton' => route('admin::role.delete'),
            'columns' => [
                ['key' => 'id', 'title' => 'ID', 'sortable' => true, 'searchable' => true],
                ['key' => 'name', 'title' => __('general.Title'), 'type' => 'link', 'url' => hash_url('role/edit/{{row.id}}'), 'sortable' => true, 'searchable' => true],
                ['key' => 'display_name', 'title' => __('general.Display Title'), 'sortable' => true, 'searchable' => true],
                ['key' => 'permissionsList', 'title' => __('general.Permissions'), 'searchable' => true],
                ['type' => 'actions', 'actions' => [
                    ['type' => 'edit', 'url' => hash_url('role/edit/{{row.id}}')],
                    ['type' => 'delete', 'url' => route('admin::role.delete'),
                        'attributes' => ['ng-if' => '!row.is_system']
                    ]
                ]],
            ]
        ];
    }

    public function dataGridJson(SmartTable $smartTable)
    {
        $conn = \DB::getDefaultConnection();

        if ($conn == 'sqlite') {
            $permissionsList = \DB::raw('GROUP_CONCAT(' . (\DB::getTablePrefix()) . 'permissions.name, \', \') as permissionsList');
        } else {
            $permissionsList = \DB::raw('GROUP_CONCAT(' . (\DB::getTablePrefix()) . 'permissions.name SEPARATOR \', \') as permissionsList');
        }

        $items = Role::select([
            'roles.id',
            'roles.name',
            'roles.display_name',
            $permissionsList,
        ])->leftJoin('permission_role', 'roles.id', '=', 'permission_role.role_id')
            ->leftJoin('permissions', 'permission_role.permission_id', '=', 'permissions.id')
            ->groupBy(['roles.id', 'roles.name', 'roles.display_name']);

        return $smartTable->make($items)
            ->setSearchColumns(['roles.name', 'roles.display_name', 'roles.description'])
            ->setHavingColumns(['permissionsList'])
            ->apply()
            ->response();
    }

    public function form(Role $model)
    {
        $form = new AdminModelForm($model);

        $form->setAction(route('admin::role.' . ($model->id ? 'update':'create'), $model->id));
        $form->addBreadCrumb(__('general.Roles'), hash_url('role'));
        $form->setBackUrl(hash_url('role'));
        $form->setNewUrl(hash_url('role/edit'));

        if ($model->id) {
            $form->addModelField('id', 'ID');
            if (!$model->isSystem()) {
                $form->setDeleteUrl(route('admin::role.delete', $model->id));
            }
        }

        $fields = [
            ['name' => 'name', 'required' => true, 'label' => __('general.Title')],
            ['name' => 'display_name', 'label' => __('general.Display Title')],
            ['name' => 'description', 'type' => 'textarea', 'label' => __('general.Description')],
            ['name' => 'permissions[]', 'type' => 'select2', 'url' => route('admin::role.permissions', $model->id),
                'label' => __('general.Permissions'),
                'multiple' => true,
                'disabled' => $model->isSystem()
            ],
        ];

        $form->setFields($fields);

        return $form;
    }

    public function save(Request $request, Role $model)
    {
        $id = $model->id;

        $this->validate($request, [
            'name' => 'required|unique:roles,name' . ($id ? ',' . $id : ''),
        ]);

        \DB::beginTransaction();

        $model->fill($request->only(['name', 'display_name', 'description']));

        $model->save();

        if (!$model->isSystem()) {
            $model->perms()->sync((array)$request->input('permissions'));
        }

        \DB::commit();

        $this->flashSuccess(trans('general.Saved'));

        return $this->redirect([
            '/role',
            '/role/create',
        ]);
    }

    public function permissions($roleId = null)
    {
        /** @var Collection $all */
        $all = Permission::ordered()->select('id', 'name as text')->get();

        if ($roleId) {
            $ids = Role::find($roleId)->perms()->pluck('id')->toArray();

            if ($ids) {
                $all->each(function ($item) use ($ids) {
                    $item->selected = in_array($item->id, $ids);
                });
            }
        }

        return $all;
    }

    public function listForModel($modelType = null, $modelId = null)
    {
        /** @var Collection $list */
        $list = Role::ordered()->select('id', 'name as text')->get();

        if ($modelId) {
            if (!class_exists($modelType)) {
                abort(500, 'Class "' . $modelType . '" not found');
            }

            $ref = new \ReflectionClass($modelType);

            if (!$ref->isSubclassOf(Model::class) || !$ref->hasMethod('roles')) {
                abort(500, 'Class "' . $modelType . '" has no roles association');
            }

            /** @var Model $model */
            $model = $ref->newInstance();
            $idKey = $model->getKeyName();

            if ($ref->hasMethod('bootSoftDeletes')) {
                $model = $model->withTrashed();
            }

            $ids = $model->find($modelId)->roles()->pluck($idKey)->toArray();

            if ($ids) {
                $list->each(function ($item) use ($ids) {
                    $item->selected = in_array($item->id, $ids);
                });
            }
        }

        return $list;
    }

    protected function deletableQuery()
    {
        return Role::notSystem();
    }
}