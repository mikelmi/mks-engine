<?php
/**
 * Author: mike
 * Date: 27.03.17
 * Time: 20:51
 */

namespace App\Services;


use App\Contracts\SettingsScope;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Mikelmi\MksAdmin\Form\AdminForm;
use Mikelmi\MksAdmin\Form\FormGroup;

class SettingsManager
{
    use ValidatesRequests;

    /**
     * @var SettingsScope[]
     */
    private $scopes;

    /**
     * @var Settings
     */
    protected $settings;

    /**
     * SettingsManager constructor.
     * @param array $scopes
     * @param Settings $settings
     */
    public function __construct(array $scopes, Settings $settings)
    {
        $this->scopes = [];

        foreach($scopes as $scope) {
            $this->addScope($scope);
        }

        $this->settings = $settings;
    }

    /**
     * @param SettingsScope $scope
     */
    public function addScope(SettingsScope $scope)
    {
        $this->scopes[$scope->name()] = $scope;
    }

    /**
     * @return SettingsScope[]
     */
    public function getScopes(): array
    {
        return $this->scopes;
    }

    /**
     * @param null $scope
     * @return AdminForm
     */
    public function getForm($scope = null): AdminForm
    {
        $form = new AdminForm();

        $scopes = $this->getScopes();

        foreach ($scopes as $scopeName => $settingsScope) {
            $group = new FormGroup($scopeName, $settingsScope->title());

            if ($scope === $scopeName) {
                $group->setActive(true);
            }

            $fields = $settingsScope->fields();

            foreach ($fields as &$field) {
                $name = $field['name'];
                $field['name'] = $this->scopedFieldName($scopeName, $name);
                $field['nameSce'] = $this->scopedFieldNameSce($scopeName, array_get($field, 'nameSce', $name));
                if (!array_key_exists('value', $field)) {
                    $field['value'] = $this->settings->get($field['nameSce']);
                }
            }

            $group->setFields($fields);
            $form->addGroup($group);
        }

        return $form;
    }

    /**
     * @param $scope
     * @param $name
     * @return string
     */
    public function scopedFieldName($scope, $name): string
    {
        if (preg_match('/(.+)\[(.+)\]/', $name, $m)) {
            return sprintf('%s[%s][%s]', $scope, $m[1], $m[2]);
        }
        return $scope . '[' . $name . ']';
    }

    /**
     * @param $scope
     * @param $name
     * @return string
     */
    public function scopedFieldNameSce($scope, $name): string
    {
        return $scope . '.' . $name;
    }

    /**
     * @param Request $request
     * @return int|false
     */
    public function save(Request $request)
    {
        $scopes = $this->getScopes();

        $rules = [];

        // Validate
        foreach ($scopes as $scopeName => $settingsScope) {
            $scopeRules = $settingsScope->rules();

            foreach ($scopeRules as $field => $rule) {
                $fieldName = $this->scopedFieldNameSce($scopeName, $field);
                $rules[$fieldName] = $rule;
            }
        }

        if ($rules) {
            $this->validate($request, $rules);
        }

        //Set data
        $old = $new = [];
        foreach ($scopes as $scopeName => $settingsScope) {
            if (!$request->exists($scopeName)) {
                continue;
            }

            $fields = array_map(function($item) {
                $name = $item['name'];
                if ($i = strpos($name, '[')) {
                    $name = substr($name, 0, $i);
                }
                return $name;
            }, $settingsScope->fields());

            $fields = array_unique($fields);

            $old[$scopeName] = $this->settings->get($scopeName, []);
            $new[$scopeName] = array_only((array) $request->get($scopeName, []), $fields);

            $this->settings->set($scopeName, $new[$scopeName]);
        }

        if ($result = $this->settings->save()) {

            foreach ($scopes as $scopeName => $settingsScope) {
                if (!array_key_exists($scopeName, $new)) {
                    continue;
                }

                $settingsScope->afterSave($old[$scopeName], $new[$scopeName]);
            }
        }

        return $result;
    }
}