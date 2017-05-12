<?php

namespace App\Http\Controllers\Admin;


use App\Events\SettingsScopesCollect;
use App\Services\SettingsManager;
use Illuminate\Http\Request;

class SettingsController extends AdminController
{
    /**
     * @var SettingsManager
     */
    private $settingsManager;

    protected function init()
    {
        parent::init();

        $this->settingsManager = resolve(SettingsManager::class);
    }
    
    public function index($scope = 'site')
    {
        $form = $this->settingsManager->getForm($scope);

        $form->setTitle(__('general.Settings'));

        $form->setAction(route('admin::settings.save'));

        return $form->response();
    }

    public function save(Request $request)
    {
        if ($this->settingsManager->save($request)) {
            $this->flashSuccess(__('general.Saved'));

            $this->triggerClearCache($request);

            return 'Ok';
        }

        abort(422, 'Settings not saved');
    }
}