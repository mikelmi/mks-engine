<?php
/**
 * Author: mike
 * Date: 03.04.17
 * Time: 18:55
 */

namespace App\Http\Controllers\Admin;


use App\Services\TagService;
use App\Traits\CrudPermissions;
use Cviebrock\EloquentTaggable\Models\Tag;
use Illuminate\Http\Request;
use Mikelmi\MksAdmin\Form\AdminModelForm;
use Mikelmi\MksAdmin\Http\Controllers\AdminController;
use Mikelmi\MksAdmin\Traits\CrudRequests;
use Mikelmi\SmartTable\SmartTable;

class TagController extends AdminController
{
    use CrudRequests,
        CrudPermissions;

    public $modelClass = Tag::class;

    public $permissionsPrefix = 'tags';

    /**
     * @var TagService
     */
    private $tagService;

    public function init()
    {
        $this->tagService = resolve(TagService::class);
    }

    /**
     * Return tags for select field
     *
     * @param Request $request
     * @param $type
     * @return \Illuminate\Support\Collection
     */
    public function all(Request $request, $type)
    {
        /** @var \Illuminate\Database\Eloquent\Collection $tags */
        $tags = $this->tagService->getAllTags($type);

        $id = $request->get('id');
        $selected = [];

        if ($id) {
            $model = call_user_func([$type, 'find'], $id);
            if ($model) {
                $selected = $model->tags->pluck('tag_id')->toArray();
            }
        }

        return $tags->map(function($item) use ($selected) {
            return [
                'id' => $item->normalized,
                'text' => $item->name,
                'selected' => $selected && in_array($item->tag_id, $selected),
            ];
        });
    }

    protected function dataGridUrl($scope = null): string
    {
        return route('admin::tags.index', $scope);
    }

    protected function dataGridJson(SmartTable $smartTable)
    {
        $items = $this->tagService->allWithCounts()->each(function($item) {
            if (!$item->id) {
                $item->id = $item->tid;
            }
        });

        return $smartTable->make($items)
            ->setSearchColumns(['name'])
            ->apply()
            ->response();
    }

    protected function dataGridOptions(): array
    {
        $canEdit = $this->canEdit();
        $canDelete = $this->canDelete();

        $actions = [];
        $tools = [];

        if ($canEdit) {
            $actions[] = ['type' => 'edit', 'url' => hash_url('tags/edit/{{row.id}}')];
        }

        if ($canDelete) {
            $actions[] = ['type' => 'delete', 'url' => route('admin::tags.delete')];
        }

        return [
            'title' => __('general.Tags'),
            'tools' => $tools,
            'deleteButton' => $canDelete ? route('admin::tags.delete') : false,
            'columns' => [
                ['key' => 'id', 'sortable' => true, 'searchable' => true],
                ['key' => 'name', 'title'=> __('general.Name'), 'sortable' => true, 'searchable' => true],
                ['key' => 'count', 'title' => __('general.Amount'), 'sortable' => true, 'searchable' => true],
                ['type' => 'actions', 'actions' => $actions],
            ]
        ];
    }

    protected function form(Tag $model, $mode = null)
    {
        if (!$model->tag_id) {
            abort(404, 'Tag not found');
        }

        $form = new AdminModelForm($model);

        $form->setAction(route('admin::tags.update', $model->tag_id));
        $form->addBreadCrumb(__('general.Tags'), hash_url('tags'));
        $form->setBackUrl(hash_url('tags'));

        $fields = [
            ['name' => 'tag_id', 'label' => 'ID']
        ];

        if ($this->canEdit($model)) {
            $form->setEditUrl(hash_url('tags/edit', $model->id));
        }

        if ($this->canDelete($model)) {
            $form->setDeleteUrl(route('admin::tags.delete', $model->id));
        }

        $fields = array_merge($fields, [
            ['name' => 'name', 'label' => __('general.Name'), 'required' => true]
        ]);

        $form->setFields($fields);

        return $form;
    }

    public function save(Request $request, Tag $model)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        $name = $request->input('name');

        $exists = $this->tagService->find($name);

        if ($exists && $exists->tag_id != $model->tag_id) {
            abort(422, "Tag {$name} already exists");
        }

        $model->name = $name;
        $model->normalized = $this->tagService->normalize($name);
        $model->save();

        $this->flashSuccess(__('general.Saved'));

        return $this->redirect('/tags');
    }

    public function delete(Request $request, $id = null)
    {
        if ($id === null) {
            $id = $request->get('id', []);
        }

        $result = $this->tagService->deleteById($id);

        if (!$result) {
            app()->abort(422);
        }

        return response()->json($result);
    }
}