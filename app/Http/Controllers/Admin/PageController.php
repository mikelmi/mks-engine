<?php

namespace App\Http\Controllers\Admin;


use App\Models\Page;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Mikelmi\MksAdmin\Http\Controllers\AdminController;
use Mikelmi\SmartTable\SmartTable;

class PageController extends AdminController
{
    public function index($scope = null)
    {
        $viewPath = $scope == 'trash' ? 'admin.page.trash' : 'admin.page.index';

        $view = view(
            $viewPath,
            [
                'data_url' => route('admin::pages.data', $scope),
                'scope' => $scope,
                'count' => $this->getCount()
            ]
        );

        return $this->setCountsHeader(response($view));
    }

    public function trash()
    {
        return $this->index('trash');
    }

    public function data(SmartTable $smartTable, $scope = null)
    {
        $items = $scope == 'trash' ? Page::onlyTrashed() : new Page();

        $items = $items->select([
            'id',
            'title',
            'path',
            'created_at',
        ]);

        return $smartTable->make($items)
            ->setSearchColumns(['title', 'path', 'page_text'])
            ->apply()
            ->orderBy('created_at', 'desc')
            ->orderBy('title')
            ->response();
    }

    public function getCount($scope = null)
    {
        if ($scope == 'trash') {
            return Page::onlyTrashed()->count();
        }

        return Page::all()->count();
    }

    public function edit($id = null)
    {
        $model = $id ? Page::withTrashed()->findOrFail($id) : new Page();

        return view(
            'admin.page.edit',
            [
                'model' => $model
            ]
        );
    }

    public function save(Request $request, $id = null)
    {
        $model = $id ? Page::withTrashed()->findOrFail($id) : new Page();

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

        $model->params = $request->input('params', []);
        $model->meta_title = $request->input('meta_title');
        $model->meta_keywords = $request->input('meta_keywords');
        $model->meta_description = $request->input('meta_description');

        \DB::beginTransaction();

        $model->save();

        $rolesShowing = $model->param('roles');

        if (!$rolesShowing || $rolesShowing == '1') {
            $model->roles()->detach();
        } else {
            $model->roles()->sync((array)$request->input('roles'));
        }

        if ($request->header('X-Submit-Flag') == 2) {
            $model->restore();
        }

        \DB::commit();

        $this->flashSuccess(trans('a.Saved'));

        return $this->redirect([
            '/page' . ($model->trashed() ? '/trash' : ''),
            '/page/edit',
            '/page'
        ]);
    }

    public function toTrash(Request $request, $id = null)
    {
        $ids = $id ? $id : $request->input('id');
        $res = Page::destroy($ids);

        $response = response()->json($res);

        return $this->setCountsHeader($response);
    }

    public function restore(Request $request, $id = null)
    {
        $ids = $id ? $id : $request->input('id');
        $res = Page::onlyTrashed()->whereIn('id', (array)$ids)->restore();

        $response = response()->json($res);

        return $this->setCountsHeader($response);
    }

    public function delete(Request $request, $id = null)
    {
        $ids = $id ? $id : $request->input('id');
        $res = Page::withTrashed()->whereIn('id', (array)$ids)->forceDelete();

        $response = response()->json($res);

        return $this->setCountsHeader($response);
    }

    /**
     * @param Response|JsonResponse $response
     * @return Response|JsonResponse
     */
    private function setCountsHeader($response)
    {
        return $response->header('X-Model-Data', json_encode([
            'pages_count' => $this->getCount(),
            'trash_count' => $this->getCount('trash')
        ]));
    }

    public function roles($pageId = null)
    {
        /** @var Collection $all */
        $all = Role::select('id', 'name as text')->get();

        if ($pageId) {
            $ids = Page::withTrashed()->find($pageId)->roles()->pluck('id')->toArray();

            if ($ids) {
                $all->each(function ($item) use ($ids) {
                    $item->selected = in_array($item->id, $ids);
                });
            }
        }

        return $all;
    }
}