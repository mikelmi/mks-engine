<?php

namespace App\Http\Controllers\Admin;


use App\Repositories\LanguageRepository;
use App\Services\Settings;
use Illuminate\Http\Request;
use Mikelmi\MksAdmin\Form\AdminForm;
use Mikelmi\MksAdmin\Http\Controllers\AdminController;
use Mikelmi\MksAdmin\Traits\DataGridRequests;
use Mikelmi\MksAdmin\Traits\DeleteRequests;
use Mikelmi\MksAdmin\Traits\ToggleRequests;
use Mikelmi\SmartTable\SmartTable;

class LanguageController extends AdminController
{
    use DataGridRequests,
        ToggleRequests,
        DeleteRequests;

    public $toggleField = 'active';

    /**
     * @var LanguageRepository
     */
    protected $langRepo;

    public function __construct()
    {
        parent::__construct();

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
        return [
            'title' => __('general.Languages'),
            'links' => [
                [
                    'title' => __('admin::messages.Add'),
                    'type' => 'link',
                    'btnType' => 'primary',
                    'icon' => 'plus',
                    'url' => '#addLangModal',
                    'attributes' => [
                        'data-toggle' => 'modal',
                    ]
                ]
            ],
            'toggleButton' => [route('admin::language.toggle.batch', 1), route('admin::language.toggle.batch', 0)],
            'deleteButton' => route('admin::language.delete'),
            'columns' => [
                ['key' => 'iso', 'title' => 'ISO', 'sortable' => true, 'searchable' => true,
                    'type' => 'language',
                    'searchType' => 'search',
                ],
                ['key' => 'name', 'title' => __('general.Name'), 'type' => 'link', 'url' => '#/language/edit/{{row.id}}', 'sortable' => true, 'searchable' => true],
                ['key' => 'title', 'title' => __('general.Title'), 'sortable' => true, 'searchable' => true],
                ['key' => 'enabled', 'title' => __('general.Status'), 'type' => 'status', 'url' => route('admin::language.toggle'),
                    'sortable' => true, 'searchable' => true,
                ],
                ['type' => 'actions', 'actions' => [
                    ['type' => 'edit', 'url' => '#/language/edit/{{row.id}}'],
                    ['type' => 'toggleOne', 'url' => route('admin::language.setDefault'), 'key' => 'default'],
                    ['type' => 'delete', 'url' => route('admin::language.delete')]
                ]],
            ],
            'rowAttributes' => [
                'ng-class' => "{'table-success': row.default}"
            ]
        ];
    }

    protected function dataGridHtml($scope = null)
    {
        return $this->makeDataGrid($scope)
            ->setScope($scope)
            ->response('admin.language.index');
    }

    public function all(LanguageRepository $languageRepository)
    {
        $all = $languageRepository->all()->diffKeys($languageRepository->available());

        return $all->map(function($item) {
            return [
                'id' => $item['iso'],
                'text' => $item['title'] . ' (' . $item['name'] . ')'
            ];
        })->values();
    }

    public function add(Request $request, LanguageRepository $languageRepository)
    {
        $this->validate($request, [
            'language' => 'required'
        ]);

        $languageRepository->add($request->get('language'));

        $this->flashSuccess(trans('general.Saved'));

        return $this->redirect('/language');
    }

    public function delete(Request $request, LanguageRepository $languageRepository, $iso = null)
    {
        $keys = $iso ?: $request->get('id');
        
        $result = $languageRepository->delete($keys);
        
        return response()->json($result);
    }

    public function toggle(LanguageRepository $languageRepository, $iso)
    {
        $language = $languageRepository->get($iso);
        $status = !$language->isEnabled();

        if (!$languageRepository->setStatus($iso, $status)) {
            abort(500);
        }

        return [
            'model' => [
                'enabled' => $status
            ]
        ];
    }

    public function toggleBatch(Request $request, LanguageRepository $languageRepository, $status)
    {
        $this->validate($request, [
            'id' => 'array'
        ]);

        $id = $request->get('id');

        if (!$languageRepository->setStatuses($id, (bool) $status)) {
            abort(500);
        }

        return [
            'models' => $languageRepository->available()->whereIn('iso', $id)->toArray()
        ];
    }
    
    public function edit(LanguageRepository $languageRepository, $iso)
    {
        $model = $languageRepository->get($iso);

        $form = new AdminForm();

        $form->setMode(AdminForm::MODE_EDIT);

        $form->setAction(route('admin::language.save', $model->getIso()));
        $form->addBreadCrumb(__('general.Languages'), '#/language');
        $form->addBreadCrumb($model->getIso());
        $form->setBackUrl('#/language');

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

        return $form->response();
    }
    
    public function save(Request $request, LanguageRepository $languageRepository, $iso)
    {
        $this->validate($request, [
            'title' => 'required'
        ]);
        
        $model = $languageRepository->get($iso);
        
        $model->setTitle($request->get('title'));
        $model->setEnabled((bool)$request->get('enabled'));
        $model->setParams($request->only(['site', 'home', 'e404', 'e500', 'e503']));

        $languageRepository->save($model);

        $this->flashSuccess(trans('general.Saved'));

        return $this->redirect('/language');
    }

    public function setDefault(LanguageRepository $languageRepository, Settings $settings, $iso)
    {
        $language = $languageRepository->get($iso);
        $settings->set('locale', $language->getIso());
        $settings->save();

        return $this->redirect('/language');
    }

    public function getSelectList(LanguageRepository $languageRepository, $iso = null)
    {
        return $languageRepository->enabled()->map(function($item) use ($iso) {
            return [
                'id' => $item->iso,
                'text' => $item->title,
                'selected' => $item->iso === $iso
            ];
        })->values();
    }
}