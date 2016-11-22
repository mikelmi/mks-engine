<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Mikelmi\MksAdmin\Http\Controllers\AdminController;
use Mikelmi\SmartTable\SmartTable;

class UserController extends AdminController
{
    public function index()
    {
        return view('admin.user.index');
    }

    public function data(SmartTable $smartTable)
    {
        $conn = \DB::getName();

        if ($conn == 'sqlite') {
            $rolesList = \DB::raw('GROUP_CONCAT('.(\DB::getTablePrefix()).'roles.name, \', \') as rolesList');
        } else {
            $rolesList = \DB::raw('GROUP_CONCAT('.(\DB::getTablePrefix()).'roles.name SEPARATOR \', \') as rolesList');
        }

        $items = User::select([
            'users.id',
            'users.name',
            'users.email',
            'users.active',
            'users.created_at',
            $rolesList,
        ])->leftJoin('role_user','users.id','=','role_user.user_id')
            ->leftJoin('roles','role_user.role_id','=','roles.id')
            ->groupBy(['users.id', 'users.name', 'users.email', 'users.active', 'users.created_at']); //Fix FULL_GROUP_BY

        return $smartTable->make($items)
            ->setSearchColumns(['users.name', 'users.email'])
            ->setHavingColumns(['rolesList'])
            ->apply()
            ->response();
    }

    public function delete(Request $request, $id = null)
    {
        if ($id === null) {
            $id = $request->get('id', []);
        }

        $res = User::notCurrent()->whereIn('id',(array)$id)->delete();

        if (!$res) {
            app()->abort(422);
        }

        return response()->json($res);
    }

    public function edit($id = null)
    {
        $model = $id ? User::findOrFail($id) : new User();

        return view('admin.user.edit', compact('model'));
    }

    public function save(Request $request, $id = null)
    {
        $this->validate($request, [
            'email' => 'required|email|unique:users,email' . ($id ? ','.$id : ''),
            'name' => 'required',
            'password' => 'confirmed' . (!$id ? '|required' : '')
        ]);

        $model = $id ? User::findOrFail($id) : new User();

        \DB::beginTransaction();

        $model->email = $request->input('email');
        $model->name = $request->input('name');
        if ($password = $request->input('password')) {
            $model->password = bcrypt($password);
        }

        if (!$model->is_current) {
            $model->active = $request->input('active', false);
        }

        $model->save();

        if (!$model->is_current) {
            $model->roles()->sync((array)$request->input('roles'));
        }

        \DB::commit();

        $this->flashSuccess(trans('general.Saved'));

        return $this->redirect([
            '/user',
            '/user/edit',
        ]);
    }

    public function roles($userId = null)
    {
        /** @var Collection $all */
        $all = Role::ordered()->select('id', 'name as text')->get();

        if ($userId) {
            $ids = User::find($userId)->roles()->pluck('id')->toArray();

            if ($ids) {
                $all->each(function ($item) use ($ids) {
                    $item->selected = in_array($item->id, $ids);
                });
            }
        }

        return $all;
    }

    function toggle($id)
    {
        $model = User::notCurrent()->findOrFail($id);
        $model->active = !$model->active;
        $model->save();

        return response()->json([
            'model' => [
                'active' => $model->active
            ]
        ]);
    }

    function toggleBatch(Request $request, $status)
    {
        $id = $request->get('id', []);

        $res = User::notCurrent()->whereIn('id',$id)->update([
            'active' => $status
        ]);

        if (!$res) {
            app()->abort(402);
        }

        $data = [];
        $models = User::notCurrent()->whereIn('id',$id)->get();

        foreach($models as $model) {
            $data[$model->id] = [
                'active' => $model->active
            ];
        }

        return response()->json([
            'models' => $data
        ]);
    }
}