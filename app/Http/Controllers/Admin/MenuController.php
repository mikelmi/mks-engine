<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 07.09.16
 * Time: 2:35
 */

namespace App\Http\Controllers\Admin;


use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Mikelmi\MksAdmin\Http\Controllers\AdminController;

class MenuController extends AdminController
{
    public function index($scope = null)
    {
        return view('admin.menu.index', compact('scope'));
    }

    public function all()
    {
        return Menu::all();
    }

    public function save(Request $request)
    {
        $id = $request->input('id');

        $model = $id ? Menu::findOrFail($id) : new Menu();

        $this->validate($request, [
            'name' => 'required'
        ]);

        $model->name = $request->input('name');
        $model->save();

        return $model;
    }
    
    public function delete(Request $request)
    {
        return Menu::destroy($request->input('id'));
    }

    public function items($scope)
    {
        return MenuItem::scoped(['menu_id' => $scope])->defaultOrder()->get()->toTree();
    }
    
    public function moveItem(Request $request, $scope, $id)
    {
        $item = MenuItem::scoped(['menu_id' => $scope])->find($id);

        $this->validate($request, [
            'old.index' => 'required|integer',
            'new.index' => 'required|integer'
        ]);

        $oldParent = $request->input('old.parent');
        $newParent = $request->input('new.parent');

        if ($newParent != $oldParent) {
            if ($newParent) {
                $parent = MenuItem::scoped(['menu_id' => $scope])->find($newParent);
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

    public function deleteItem(Request $request, $id)
    {
        $item = MenuItem::find($id);

        return [
            'result' => $item->delete()
        ];
    }

    public function editItem($scope, $id = null)
    {
        $menu = Menu::find($scope);

        $model = $id ? $item = MenuItem::scoped(['menu_id' => $scope])->find($id) : new MenuItem();

        $model->menu()->associate($menu);

        return view('admin.menu.item', compact('model', 'menu'));
    }

    public function saveItem(Request $request, $scope, $id = null)
    {
        $menu = Menu::find($scope);

        $model = $id ? $item = MenuItem::scoped(['menu_id' => $scope])->find($id) : new MenuItem();

        $this->validate($request, [
            'title' => 'required|min:3|max:255',
        ]);
        
        $model->menu()->associate($menu);

        $model->title = $request->input('title');
        $model->route = $request->input('route');
        $model->params = $request->input('params');
        $model->url = $request->input('url');
        $model->target = $request->input('target', '');

        if ($request->exists('parent_id')) {
            $parent_id = $request->input('parent_id');
            if (!$parent_id) {
                if ($model->parent) {
                    $model->makeRoot();
                }
            } else {
                $parent = MenuItem::scoped(['menu_id' => $scope])->find($parent_id);
                if (!$model->isChildOf($parent)) {
                    $model->appendToNode($parent);
                }
            }
        }

        $model->save();

        $this->flashSuccess(trans('a.Saved'));

        return $this->redirect([
            '/menuman/' . $scope,
            '/menuman/items/' . $scope. '/edit'
        ]);
    }

    public function treeOptions($scope, $id = null)
    {
        $items = MenuItem::scoped(['menu_id' => $scope])->defaultOrder()->get()->toTree();

        $result = [];

        $current = null;

        if ($id) {
            $current = MenuItem::scoped(['menu_id' => $scope])->find($id);
        }

        $traverse = function ($items, $prefix = '-') use (&$traverse, &$result, $current) {
            foreach ($items as $item) {
                $result[] = [
                    'id' => $item->id,
                    'text' => $prefix.' '.$item->title,
                    'disabled' => $current && $item->isDescendantOf($current),
                    'selected' => $current && $current->parent_id == $item->id,
                ];

                $traverse($item->children, $prefix.'-');
            }
        };

        $traverse($items);

        return $result;
    }
}