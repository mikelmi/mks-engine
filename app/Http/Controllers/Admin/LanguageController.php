<?php

namespace App\Http\Controllers\Admin;


use App\Models\Language;
use App\Repositories\LanguageRepository;
use App\Services\Settings;
use App\Traits\CrudPermissions;
use Illuminate\Http\Request;
use Mikelmi\MksAdmin\Form\AdminForm;
use Mikelmi\MksAdmin\Http\Controllers\AdminController;
use Mikelmi\MksAdmin\Traits\CrudRequests;
use Mikelmi\MksAdmin\Traits\ToggleRequests;
use Mikelmi\SmartTable\SmartTable;

class LanguageController extends AdminController
{
    use CrudRequests,
        ToggleRequests,
        CrudPermissions;

    public $toggleField = 'active';

    public $permissionsPrefix = 'lang';

    /**
     * @var LanguageRepository
     */
    protected $langRepo;

    protected function init()
    {
        $this->langRepo = app(LanguageRepository::class);
    }

    protected function dataGridUrl(): string
    {
        return route('admin::language.index');
    }

    protected function dataGridJson(SmartTable $smartTable)
    {
        $locale = settings('locale');

        $items = $this->langRepo->available()->map(function($item) use ($locale) {
            $item = array_only($item->toArray(), ['iso', 'name', 'title', 'enabled']);
            $item['id'] = $item['iso'];
            $item['default'] = $item['iso'] === $locale;

            return $item;
        })->values();

        return $smartTable->make($items)
            ->setSearchColumns(['name'])
            ->apply()
            ->response();
    }

    protected function dataGridOptions(): array
    {
        $canEdit = $this->canEdit();
        $canToggle = $this->canToggle();
        $canDelete = $this->canDelete();

        $actions = [];
        $links = [];

        if ($canEdit) {
            $actions[] = ['type' => 'edit', 'url' => hash_url('language/edit/{{row.id}}')];
        }

        if ($canToggle) {
            $actions[] = ['type' => 'toggleOne', 'url' => route('admin::language.setDefault'), 'key' => 'default'];
        }

        if ($canDelete) {
            $actions[] = ['type' => 'delete', 'url' => route('admin::language.delete')];
        }

        if ($this->canCreate()) {
            $links[] = [
                'title' => __('admin::messages.Add'),
                'type' => 'link',
                'btnType' => 'primary',
                'icon' => 'plus',
                'url' => '#addLangModal',
                'attributes' => [
                    'data-toggle' => 'modal',
                ]
            ];
        }

        $options = [
            'title' => __('general.Languages'),
            'links' => $links,
            'columns' => [
                ['key' => 'iso', 'title' => 'ISO', 'sortable' => true, 'searchable' => true,
                    'type' => 'language',
                    'searchType' => 'search',
                ],
                ['key' => 'name', 'title' => __('general.Name'), 'type' => 'link', 'url' => hash_url('language/show/{{row.id}}'), 'sortable' => true, 'searchable' => true],
                ['key' => 'title', 'title' => __('general.Title'), 'sortable' => true, 'searchable' => true],
                ['key' => 'enabled', 'title' => __('general.Status'), 'type' => 'status', 'url' => route('admin::language.toggle'),
                    'sortable' => true, 'searchable' => true,
                    'disabled' => !$canToggle
                ],
                ['type' => 'actions', 'actions' => $actions],
            ],
            'rowAttributes' => [
                'ng-class' => "{'table-success': row.default}"
            ]
        ];

        if ($canToggle) {
            $options['toggleButton'] = [
                route('admin::language.toggle.batch', 1),
                route('admin::language.toggle.batch', 0)
            ];
        }

        if ($canDelete) {
            $options['deleteButton'] = route('admin::language.delete');
        }

        return $options;
    }

    protected function dataGridHtml($scope = null)
    {
        return $this->makeDataGrid($scope)
            ->setScope($scope)
            ->response('admin.language.index');
    }

    public function all()
    {
        $all = $this->langRepo->all()->diffKeys($this->langRepo->available());

        return $all->map(function($item) {
            return [
                'id' => $item['iso'],
                'text' => $item['title'] . ' (' . $item['name'] . ')'
            ];
        })->values();
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'language' => 'required'
        ]);

        $this->langRepo->add($request->get('language'));

        $this->flashSuccess(trans('general.Saved'));

        return $this->redirect('/language');
    }

    public function delete(Request $request, $iso = null)
    {
        $keys = $iso ?: $request->get('id');
        
        $result = $this->langRepo->delete($keys);
        
        return response()->json($result);
    }

    public function toggle($iso)
    {
        $language = $this->langRepo->get($iso);
        $status = !$language->isEnabled();

        if (!$this->langRepo->setStatus($iso, $status)) {
            abort(500);
        }

        return [
            'model' => [
                'enabled' => $status
            ]
        ];
    }

    public function toggleBatch(Request $request, $status)
    {
        $this->validate($request, [
            'id' => 'array'
        ]);

        $id = $request->get('id');

        if (!$this->langRepo->setStatuses($id, (bool) $status)) {
            abort(500);
        }

        return [
            'models' => $this->langRepo->available()->whereIn('iso', $id)->toArray()
        ];
    }

    protected function formModel($model)
    {
        if ($model instanceof Language) {
            return $model;
        }

        return $this->langRepo->get($model);
    }
    
    public function form($iso)
    {
        $model = $this->formModel($iso);

        $form = new AdminForm();

        $form->setMode(AdminForm::MODE_EDIT);

        $form->setAction(route('admin::language.update', $model->getIso()));
        $form->addBreadCrumb(__('general.Languages'), hash_url('language'));
        $form->addBreadCrumb($model->getIso(), hash_url('language/show', $model->getIso()));
        $form->setBackUrl(hash_url('language'));

        if ($this->canEdit($model)) {
            $form->setEditUrl(hash_url('language/edit', $model->getIso()));
        }

        $form->addGroup('general', [
            'title' => __('general.Language'),
            'fields' => [
                ['name' => 'iso', 'label'=> 'ISO', 'value' => $model->getIso(), 'type' => 'staticText'],
                ['name' => 'title', 'value' => $model->getTitle(), 'label' => __('general.Title'), 'required' => true],
                ['name' => 'enabled', 'type' => 'toggle', 'value' => $model->getEnabled(), 'label' => __('general.Active')],
                ['name' => 'site[title]', 'value' => $model->get('site.title'), 'label' => __('general.Site name')],
                ['name' => 'site[description]', 'value' => $model->get('site.description'), 'label' => __('general.Description')],
                ['name' => 'site[keywords]', 'value' => $model->get('site.keywords'), 'label' => __('general.Keywords')],
            ]
        ]);

        $form->addGroup('pages', [
            'title' => __('general.Pages'),
            'fields' => [
                ['name' => 'home', 'label' => __('general.Homepage'), 'type' => 'route',
                    'value' => ['route' => $model->get('home.route'), 'params' => $model->get('home.params')]
                ],
                ['name' => 'e404', 'label' => '404', 'value' => $model->get('e404'), 'type' => 'pages'],
                ['name' => 'e500', 'label' => __('general.Error page'), 'value' => $model->get('e500'), 'type' => 'pages'],
                ['name' => 'e503', 'label' => __('general.Offline page'), 'value' => $model->get('e503'), 'type' => 'pages'],
            ],
        ]);

        return $form;
    }
    
    public function update(Request $request, $iso)
    {
        $this->validate($request, [
            'title' => 'required'
        ]);
        
        $model = $this->langRepo->get($iso);
        
        $model->setTitle($request->get('title'));
        $model->setEnabled((bool)$request->get('enabled'));
        $model->setParams($request->only(['site', 'home', 'e404', 'e500', 'e503']));

        $this->langRepo->save($model);

        $this->flashSuccess(trans('general.Saved'));

        return $this->redirect('/language');
    }

    public function setDefault(Settings $settings, $iso)
    {
        $language = $this->langRepo->get($iso);
        $settings->set('locale', $language->getIso());
        $settings->save();

        return $this->redirect('/language');
    }

    public function getSelectList($iso = null)
    {
        return $this->langRepo->getSelectList($iso);
    }
}