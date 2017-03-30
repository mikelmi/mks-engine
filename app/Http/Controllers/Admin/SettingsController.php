<?php

namespace App\Http\Controllers\Admin;


use App\Events\SettingsScopesCollect;
use App\Services\SettingsManager;
use App\Settings\SettingsScope;
use App\Services\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Mikelmi\MksAdmin\Form\AdminForm;
use Mikelmi\MksAdmin\Form\FormGroup;
use Mikelmi\MksAdmin\Http\Controllers\AdminController;

class SettingsController extends AdminController
{
    /**
     * @var SettingsManager
     */
    private $settingsManager;

    protected function init()
    {
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
            $this->flashSuccess(trans('general.Saved'));

            return 'Ok';
        }

        abort(422, 'Settings not saved');
    }
}