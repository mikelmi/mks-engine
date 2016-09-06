<?php

namespace App\Http\Controllers\Admin;

use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Mikelmi\MksAdmin\Http\Controllers\AdminController;
use Mikelmi\SmartTable\SmartTable;

class PermissionController extends AdminController
{
    public function index()
    {
        return view('admin.permission.index');
    }

    public function data(SmartTable $smartTable)
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

    public function delete(Request $request, $id = null)
    {
        if ($id === null) {
            $id = $request->get('id', []);
        }

        $res = Permission::whereIn('id',(array)$id)->delete();

        if (!$res) {
            app()->abort(422);
        }

        return response()->json($res);
    }

    public function edit($id = null)
    {
        $model = $id ? Permission::findOrFail($id) : new Permission();

        return view('admin.permission.edit', compact('model'));
    }

    public function save(Request $request, $id = null)
    {
        $this->validate($request, [
            'name' => 'required|unique:permissions,name' . ($id ? ','.$id : ''),
        ]);

        $model = $id ? Permission::findOrFail($id) : new Permission();

        $model->name = $request->input('name');
        $model->display_name = $request->input('display_name');
        $model->description = $request->input('description');

        $model->save();

        $this->flashSuccess(trans('a.Saved'));

        return $this->redirect([
            '/permission',
            '/permission/edit',
        ]);
    }
}