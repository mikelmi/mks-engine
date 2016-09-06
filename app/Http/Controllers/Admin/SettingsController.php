<?php

namespace App\Http\Controllers\Admin;


use App\Events\SettingsScopesCollect;
use App\Models\SettingsScope;
use App\Services\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Mikelmi\MksAdmin\Http\Controllers\AdminController;

class SettingsController extends AdminController
{
    /**
     * @return Collection
     */
    private function getScopes()
    {
        $collection = new Collection();

        event(new SettingsScopesCollect($collection));
        
        return $collection;
    }

    /**
     * @param string $name
     * @return SettingsScope|nulls
     */
    private function getScope($name)
    {
        return $this->getScopes()->get($name);
    }
    
    public function index(Settings $settings, $scope = 'site')
    {
        $scopes = $this->getScopes();
        /** @var SettingsScope $scopeObject */
        $scopeObject = $scopes->get($scope);

        if (!$scopeObject) {
            abort('404', 'Scope not found');
        }

        $model = $settings->getRepository($scope);
        $scopeObject->getModel($model);
        
        $view = $scopeObject->getView('admin.settings.'.$scope);

        return view($view, compact('model', 'scopes', 'scope', 'scopeObject'));
    }

    public function save(Request $request, Settings $settings, $scope = 'site')
    {
        $scopeObject = $this->getScope($scope);

        if (!$scopeObject) {
            abort('404', 'Scope not found');
        }

        $rules = $scopeObject->getRules();

        if ($rules) {
            $this->validate($request, $rules);
        }

        $old = $settings->getRepository($scope);
        $data = $request->only($scopeObject->getFields());

        $settings->set($scope, $data);
        $settings->save();

        $scopeObject->afterSave($old, $settings->getRepository($scope));

        $this->flashSuccess(trans('a.Saved'));

        return $this->redirect('/settings/' . $scope);
    }
}