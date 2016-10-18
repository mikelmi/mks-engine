<?php

namespace App\Http\Controllers\Admin;


use App\Models\Page;
use App\Repositories\LanguageRepository;
use App\Services\Settings;
use Illuminate\Http\Request;
use Mikelmi\MksAdmin\Http\Controllers\AdminController;
use Mikelmi\SmartTable\SmartTable;

class LanguageController extends AdminController
{
    public function index()
    {
        return view('admin.language.index');
    }

    public function data(SmartTable $smartTable, LanguageRepository $languageRepository)
    {
        $iconUrl = route('lang.icon');

        $locale = settings('locale');

        $items = $languageRepository->available()->map(function($item) use ($iconUrl, $locale) {
            $item = array_only($item->toArray(), ['iso', 'name', 'title', 'enabled']);
            $item['icon'] = $iconUrl . '/' . $item['iso'];
            $item['default'] = $item['iso'] === $locale;

            return $item;
        })->values();

        return $smartTable->make($items)
            ->setSearchColumns(['name'])
            ->apply()
            ->response();
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

        $this->flashSuccess(trans('a.Saved'));

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

        $pages = Page::ordered()->pluck('title', 'id')->toArray();

        return view('admin.language.edit', compact('model', 'pages'));
    }
    
    public function save(Request $request, LanguageRepository $languageRepository, $iso)
    {
        $this->validate($request, [
            'title' => 'required'
        ]);
        
        $model = $languageRepository->get($iso);
        
        $model->setTitle($request->get('title'));
        $model->setEnabled((bool)$request->get('enabled'));
        $model->setParams($request->only(['site', 'home', '404', '500', '503']));

        $languageRepository->save($model);

        $this->flashSuccess(trans('a.Saved'));

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