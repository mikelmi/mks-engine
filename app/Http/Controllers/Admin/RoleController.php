<?php

namespace App\Http\Controllers\Admin;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Mikelmi\MksAdmin\Http\Controllers\AdminController;
use Mikelmi\SmartTable\SmartTable;

class RoleController extends AdminController
{
    public function index()
    {
        return view('admin.role.index');
    }

    public function data(SmartTable $smartTable)
    {
        $conn = \DB::getDefaultConnection();

        if ($conn == 'sqlite') {
            $permissionsList = \DB::raw('GROUP_CONCAT('.(\DB::getTablePrefix()).'permissions.name, \', \') as permissionsList');
        } else {
            $permissionsList = \DB::raw('GROUP_CONCAT('.(\DB::getTablePrefix()).'permissions.name SEPARATOR \', \') as permissionsList');
        }

        $items = Role::select([
            'roles.id',
            'roles.name',
            'roles.display_name',
            $permissionsList,
        ])->leftJoin('permission_role','roles.id','=','permission_role.role_id')
            ->leftJoin('permissions','permission_role.permission_id','=','permissions.id')
            ->groupBy('roles.id');

        return $smartTable->make($items)
            ->setSearchColumns(['roles.name', 'roles.display_name', 'roles.description'])
            ->setHavingColumns(['permissionsList'])
            ->apply()
            ->response();
    }

    public function delete(Request $request, $id = null)
    {
        if ($id === null) {
            $id = $request->get('id', []);
        }

        $res = Role::notSystem()->whereIn('id',(array)$id)->delete();

        if (!$res) {
            app()->abort(422);
        }

        return response()->json($res);
    }

    public function edit($id = null)
    {
        $model = $id ? Role::findOrFail($id) : new Role();

        return view('admin.role.edit', compact('model'));
    }

    public function save(Request $request, $id = null)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name' . ($id ? ','.$id : ''),
        ]);

        $model = $id ? Role::findOrFail($id) : new Role();

        \DB::beginTransaction();

        $model->name = $request->input('name');
        $model->display_name = $request->input('display_name');
        $model->description = $request->input('description');

        $model->save();

        if (!$model->is_system) {
            $model->perms()->sync((array)$request->input('permissions'));
        }

        \DB::commit();

        $this->flashSuccess(trans('general.Saved'));

        return $this->redirect([
            '/role',
            '/role/edit',
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
}