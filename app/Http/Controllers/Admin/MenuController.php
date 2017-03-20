<?php

namespace App\Http\Controllers\Admin;


use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Mikelmi\MksAdmin\Form\AdminModelForm;
use Mikelmi\MksAdmin\Http\Controllers\AdminController;

class MenuController extends AdminController
{
    public function index($scope = null)
    {
        return view('admin.menu.index', compact('scope'));
    }

    public function all()
    {
        return Menu::ordered()->get();
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
        return MenuItem::getTree($scope);
    }

    public function moveItem(Request $request, $scope, $id)
    {
        $item = MenuItem::ofMenu($scope)->find($id);

        $this->validate($request, [
            'old.index' => 'required|integer',
            'new.index' => 'required|integer'
        ]);

        $oldParent = $request->input('old.parent');
        $newParent = $request->input('new.parent');

        if ($newParent != $oldParent) {
            if ($newParent) {
                $parent = MenuItem::ofMenu($scope)->find($newParent);
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

    public function deleteItem($id)
    {
        $item = MenuItem::find($id);

        return [
            'result' => $item->delete()
        ];
    }

    public function editItem($scope, $id = null)
    {
        $menu = Menu::find($scope);

        $model = $id ? $item = MenuItem::ofMenu($scope)->find($id) : new MenuItem();

        $model->menu()->associate($menu);

        $form = new AdminModelForm($model);

        $form->setAction(route('admin::menu.items.save', ['scope' => $menu->id, 'id' => $model->id]));
        $form->addBreadCrumb(__('general.Menu'), '#/menuman');
        $form->addBreadCrumb($menu->name, '#/menuman/' . $menu->id);
        $form->setBackUrl('#/menuman/' . $menu->id);

        if ($id) {
            $form->addModelField('id', 'ID');
        }

        $form->setFields([
            ['name' => 'title', 'required' => true, 'label' => __('general.Title')],
            ['name' => 'link', 'label' => __('general.Link'), 'type' => 'route',
                'value' => ['route' => $model->route, 'params' => $model->params],
                'attributes' => [
                    'raw-value' => $model->url,
                    'field-raw' => 'url',
                    'raw-enabled' => 'true',
                    'empty-title' => 'URL'
                ]
            ],
            ['name' => 'parent_id', 'label' => __('general.Parent Item'), 'type' => 'select2',
                'url' => route('admin::menu.tree.options', ['scope'=>$menu->id, 'id'=>$model->id])
            ],
        ]);

        return $form->response();

        return view('admin.menu.item', compact('model', 'menu'));
    }

    public function saveItem(Request $request, $scope, $id = null)
    {
        $menu = Menu::find($scope);

        $model = $id ? $item = MenuItem::ofMenu($scope)->find($id) : new MenuItem();

        $this->validate($request, [
            'title' => 'required|min:3|max:255',
        ]);

        $model->menu()->associate($menu);

        $model->title = $request->input('title');
        $model->route = $request->input('link.route');
        $model->params = $request->input('link.params');
        $model->url = $request->input('url');
        $model->target = $request->input('target', '');

        if ($request->exists('parent_id')) {
            $parent_id = $request->input('parent_id');
            if (!$parent_id) {
                if ($model->parent) {
                    $model->makeRoot();
                }
            } else {
                $parent = MenuItem::ofMenu($scope)->find($parent_id);
                if (!$model->isChildOf($parent)) {
                    $model->appendToNode($parent);
                }
            }
        }

        $model->save();

        $this->flashSuccess(trans('general.Saved'));

        return $this->redirect([
            '/menuman/' . $scope,
            '/menuman/items/' . $scope . '/edit'
        ]);
    }

    public function treeOptions($scope, $id = null)
    {
        $items = MenuItem::getTree($scope);

        $result = [];

        $current = null;

        if ($id) {
            $current = MenuItem::ofMenu($scope)->find($id);
        }

        $traverse = function ($items, $prefix = '-') use (&$traverse, &$result, $current) {
            foreach ($items as $item) {
                $result[] = [
                    'id' => $item->id,
                    'text' => $prefix . ' ' . $item->title,
                    'disabled' => $current ? ($current->id == $item->id || $item->isDescendantOf($current)) : false,
                    'selected' => $current && $current->parent_id == $item->id,
                ];

                $traverse($item->children, $prefix . '-');
            }
        };

        $traverse($items);

        return $result;
    }
}