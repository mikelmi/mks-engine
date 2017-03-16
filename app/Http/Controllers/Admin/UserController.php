<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Mikelmi\MksAdmin\Form\AdminModelForm;
use Mikelmi\MksAdmin\Http\Controllers\AdminController;
use Mikelmi\MksAdmin\Traits\DataGridRequests;
use Mikelmi\MksAdmin\Traits\DeleteRequests;
use Mikelmi\SmartTable\SmartTable;

class UserController extends AdminController
{
    use DataGridRequests,
        DeleteRequests;

    public $modelClass = User::class;
    public $toggleField = 'active';

    protected function dataGridUrl(): string
    {
        return route('admin::user.index');
    }

    protected function dataGridJson(SmartTable $smartTable)
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

    protected function dataGridOptions(): array
    {
        return [
            'title' => __('general.Users'),
            'createLink' => '#/user/edit',
            'toggleButton' => [route('admin::user.toggle.batch', 1), route('admin::user.toggle.batch', 0)],
            'deleteButton' => route('admin::user.delete'),
            'columns' => [
                ['key' => 'id', 'title' => 'ID', 'sortable' => true, 'searchable' => true],
                ['key' => 'name', 'title' => __('general.Name'), 'type' => 'link', 'url' => '#/user/edit/{{row.id}}', 'sortable' => true, 'searchable' => true],
                ['key' => 'email', 'title' => 'E-mail', 'sortable' => true, 'searchable' => true],
                ['key' => 'active', 'title' => __('general.Status'), 'type' => 'status', 'url' => route('admin::user.toggle'),
                    'sortable' => true, 'searchable' => true,
                    'buttonAttributes' => ['ng-disabled' => 'row.is_current']
                ],
                ['key' => 'rolesList', 'title' => __('general.Roles'), 'searchable' => true],
                ['key' => 'created_at', 'title' => __('general.Created at'), 'type' => 'date', 'sortable' => true, 'searchable' => true],
                ['type' => 'actions', 'actions' => [
                    ['type' => 'edit', 'url' => '#/user/edit/{{row.id}}'],
                    ['type' => 'delete', 'url' => route('admin::user.delete'),
                        'attributes' => ['ng-if' => '!row.is_current']
                    ]
                ]],
            ],
            'rowAttributes' => [
                'ng-class' => "{'table-warning': !row.active}"
            ]
        ];
    }

    protected function deletableQuery()
    {
        return User::notCurrent();
    }

    public function edit(User $model)
    {
        $form = new AdminModelForm($model);

        $form->setAction(route('admin::user.save', $model->id));
        $form->addBreadCrumb(__('general.Users'), '#/user');
        $form->setBackUrl('#/user');
        $form->setNewUrl('#/user/edit');

        if ($model->id) {
            $form->addModelField('id', 'ID');
            if (!$model->isCurrent()) {
                $form->setDeleteUrl(route('admin::user.delete', $model->id));
            }
        }

        $fields = [
            ['name' => 'name', 'required' => true, 'label' => __('general.Name')],
            ['name' => 'email', 'type' => 'email', 'required' => true, 'label' => 'E-mail'],
            ['name' => 'active', 'type' => 'toggle', 'disabled' => $model->isCurrent(), 'label' => __('general.Active')],
            ['name' => 'roles[]', 'type' => 'select2', 'url' => route('admin::user.roles', $model->id),
                'label' => __('general.Roles'),
                'multiple' => true,
                'disabled' => $model->isCurrent()
            ],
            ['name' => 'password', 'label' => __('general.Password'), 'type' => 'changePassword'],
            ['name' => 'created_at', 'type' => 'staticText', 'label' => __('general.Created at')],
            ['name' => 'updated_at', 'type' => 'staticText', 'label' => __('general.Updated at')],
        ];

        $form->setFields($fields);

        return $form->response();
    }

    public function save(Request $request, User $model)
    {
        $id = $model->id;

        $this->validate($request, [
            'email' => 'required|email|unique:users,email' . ($id ? ','.$id : ''),
            'name' => 'required',
            'password' => 'confirmed' . (!$id ? '|required' : '')
        ]);

        \DB::beginTransaction();

        $model->email = $request->input('email');
        $model->name = $request->input('name');

        if ($password = $request->input('password')) {
            $model->password = bcrypt($password);
        }

        if (!$model->isCurrent()) {
            $model->active = $request->input('active', false);
        }

        $model->save();

        if (!$model->isCurrent()) {
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