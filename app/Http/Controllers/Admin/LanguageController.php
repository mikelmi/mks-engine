<?php

namespace App\Http\Controllers\Admin;


use App\Models\Page;
use App\Services\LanguageManager;
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

    public function data(SmartTable $smartTable, LanguageManager $languageManager)
    {
        $iconUrl = route('lang.icon');

        $locale = settings('locale');

        $items = $languageManager->available()->map(function($item) use ($iconUrl, $locale) {
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

    public function all(LanguageManager $languageManager)
    {
        $all = $languageManager->all()->diffKeys($languageManager->available());

        return $all->map(function($item) {
            return [
                'id' => $item['iso'],
                'text' => $item['title'] . ' (' . $item['name'] . ')'
            ];
        })->values();
    }

    public function add(Request $request, LanguageManager $languageManager)
    {
        $this->validate($request, [
            'language' => 'required'
        ]);

        $languageManager->add($request->get('language'));

        $this->flashSuccess(trans('a.Saved'));

        return $this->redirect('/language');
    }

    public function delete(Request $request, LanguageManager $languageManager, $iso = null)
    {
        $keys = $iso ?: $request->get('id');
        
        $result = $languageManager->delete($keys);
        
        return response()->json($result);
    }

    public function toggle(LanguageManager $languageManager, $iso)
    {
        $language = $languageManager->get($iso);
        $status = !$language->isEnabled();

        if (!$languageManager->setStatus($iso, $status)) {
            abort(500);
        }

        return [
            'model' => [
                'enabled' => $status
            ]
        ];
    }

    public function toggleBatch(Request $request, LanguageManager $languageManager, $status)
    {
        $this->validate($request, [
            'id' => 'array'
        ]);

        $id = $request->get('id');

        if (!$languageManager->setStatuses($id, (bool) $status)) {
            abort(500);
        }

        return [
            'models' => $languageManager->available()->whereIn('iso', $id)->toArray()
        ];
    }
    
    public function edit(LanguageManager $languageManager, $iso)
    {
        $model = $languageManager->get($iso);

        $pages = Page::ordered()->pluck('title', 'id')->toArray();

        return view('admin.language.edit', compact('model', 'pages'));
    }
    
    public function save(Request $request, LanguageManager $languageManager, $iso)
    {
        $this->validate($request, [
            'title' => 'required'
        ]);
        
        $model = $languageManager->get($iso);
        
        $model->setTitle($request->get('title'));
        $model->setEnabled((bool)$request->get('enabled'));
        $model->setParams($request->only(['site', 'home', '404', '500', '503']));

        $languageManager->save($model);

        $this->flashSuccess(trans('a.Saved'));

        return $this->redirect('/language');
    }

    public function setDefault(LanguageManager $languageManager, Settings $settings, $iso)
    {
        $language = $languageManager->get($iso);
        $settings->set('locale', $language->getIso());
        $settings->save();

        return $this->redirect('/language');
    }

    public function getSelectList(LanguageManager $languageManager, $iso = null)
    {
        return $languageManager->enabled()->map(function($item) use ($iso) {
            return [
                'id' => $item->iso,
                'text' => $item->title,
                'selected' => $item->iso === $iso
            ];
        })->values();
    }
}