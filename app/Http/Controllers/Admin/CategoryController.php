<?php

namespace App\Http\Controllers\Admin;


use App\Events\CategoryTypesCollect;
use App\Models\Category;
use App\Models\Section;
use App\Services\CategoryManager;
use Illuminate\Http\Request;
use Mikelmi\MksAdmin\Http\Controllers\AdminController;

class CategoryController extends AdminController
{
    public function index(CategoryManager $categoryManager, $scope = null)
    {
        $types = $categoryManager->getTypes();

        return view('admin.category.index', compact('scope', 'types'));
    }

    public function sections()
    {
        return Section::all();
    }

    public function saveSection(Request $request)
    {
        $id = $request->input('id');

        $model = $id ? Section::findOrFail($id) : new Section();

        $this->validate($request, [
            'title' => 'required',
            'type' => 'required',
        ]);

        $model->title = $request->input('title');
        $model->type = $request->input('type');
        $model->save();

        return $model;
    }

    public function deleteSection(Request $request)
    {
        return Section::destroy($request->input('id'));
    }

    public function categories($scope)
    {
        return Category::getTree($scope);
    }

    public function move(Request $request, $scope, $id)
    {
        $item = Category::ofSection($scope)->find($id);

        $this->validate($request, [
            'old.index' => 'required|integer',
            'new.index' => 'required|integer'
        ]);

        $oldParent = $request->input('old.parent');
        $newParent = $request->input('new.parent');

        if ($newParent != $oldParent) {
            if ($newParent) {
                $parent = Category::ofSection($scope)->find($newParent);
                $parent->appendNode($item);
            } else {
                $item->saveAsRoot();
            }

            $pos = $item->getSiblings()->count() - $request->input('new.index');
        } else {
            $pos = $request->input('old.index') - $request->input('new.index');
        }

        if ($pos !== 0) {
            if ($pos > 0) {
                $item->up($pos);
            } else {
                $item->down(abs($pos));
            }
        }

        return $item;
    }

    public function delete($id)
    {
        $item = Category::find($id);

        return [
            'result' => $item->delete()
        ];
    }

    public function edit($scope, $id = null)
    {
        $section = Section::find($scope);

        $model = $id ? $item = Category::ofSection($scope)->find($id) : new Category();

        $model->section()->associate($section);

        return view('admin.category.edit', compact('model', 'section'));
    }

    public function save(Request $request, $scope, $id = null)
    {
        $section = Section::find($scope);

        $model = $id ? $item = Category::ofSection($scope)->find($id) : new Category();

        $this->validate($request, [
            'title' => 'required|min:3|max:255',
        ]);

        $model->section()->associate($section);

        $model->title = $request->input('title');

        if ($request->exists('parent_id')) {
            $parent_id = $request->input('parent_id');
            if (!$parent_id) {
                if ($model->parent) {
                    $model->makeRoot();
                }
            } else {
                $parent = Category::ofSection($scope)->find($parent_id);
                if (!$model->isChildOf($parent)) {
                    $model->appendToNode($parent);
                }
            }
        }

        $model->save();

        $this->flashSuccess(trans('general.Saved'));

        return $this->redirect([
            '/category/' . $scope,
            '/category/edit/' . $scope
        ]);
    }

    public function treeOptions($scope, $id = null)
    {
        $items = Category::getTree($scope);

        $result = [];

        $current = null;

        if ($id) {
            $current = Category::ofSection($scope)->find($id);
        }

        $traverse = function ($items, $prefix = '-') use (&$traverse, &$result, $current) {
            foreach ($items as $item) {
                $result[] = [
                    'id' => $item->id,
                    'text' => $prefix . ' ' . $item->title,
                    'disabled' => $current && $item->isDescendantOf($current),
                    'selected' => $current && $current->parent_id == $item->id,
                ];

                $traverse($item->children, $prefix . '-');
            }
        };

        $traverse($items);

        return $result;
    }
    
    public function select(Request $request, CategoryManager $categoryManager, $type = null)
    {
        return $categoryManager->getSelectOptions($type, $request->get('selected'));
    }
}