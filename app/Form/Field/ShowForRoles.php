<?php
/**
 * Author: mike
 * Date: 20.03.17
 * Time: 13:03
 */

namespace App\Form\Field;


use Illuminate\Database\Eloquent\Model;
use Mikelmi\MksAdmin\Form\Field\Select;

class ShowForRoles extends Select
{
    protected $ngModel;

    protected $rolesName = 'roles[]';

    /**
     * @var Model|null
     */
    protected $model;

    public function __construct($name = null, $value = null, $label = null)
    {
        parent::__construct($name, $value, $label);

        $this->setOptions([
            '' => __('general.Show for all'),
            '1' => __('general.Show for registered'),
            '2' => __('general.Show for roles'),
            '-1' => __('general.Hide for roles'),
        ]);

        $this->ngModel = 'showForRoles' . uniqid();

        if (is_null($label)) {
            $this->label = __('general.Roles');
        }
    }

    /**
     * @return string
     */
    public function getNgModel(): string
    {
        return $this->ngModel;
    }

    /**
     * @param string $ngModel
     * @return ShowForRoles
     */
    public function setNgModel(string $ngModel): ShowForRoles
    {
        if ($ngModel) {
            $this->ngModel = $ngModel;
        }
        return $this;
    }


    protected function getDefaultAttributes(): array
    {
        $result = parent::getDefaultAttributes();

        $result['ng-model'] = $this->getNgModel();
        $result['ng-init'] = sprintf("%s='%s'", $this->getNgModel(), $this->getValue());

        return $result;
    }

    public function render():string
    {
        $template = $this->template;

        if (!$template) {
            $template = 'admin.form.field.show-roles-' . ($this->getLayout() ?: 'default');
        }

        return view($template, ['field' => $this]);
    }

    /**
     * @return string
     */
    public function getRolesName(): string
    {
        return $this->rolesName;
    }

    /**
     * @param string $rolesName
     * @return ShowForRoles
     */
    public function setRolesName(string $rolesName): ShowForRoles
    {
        $this->rolesName = $rolesName;
        return $this;
    }

    /**
     * @return Model|null
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param Model|null $model
     * @return ShowForRoles
     */
    public function setModel($model)
    {
        $this->model = $model;
        return $this;
    }

    public function renderSelectRoles(): string
    {
        $attr = [
            'class' => 'form-control',
            'multiple' => true,
            'mks-select' => true,
            'name' => $this->getRolesName(),
            'data-url' => route('admin::role.forModel', $this->model ? [get_class($this->model), $this->model->getKey()] : []),
        ];

        if ($this->isDisabled()) {
            $attr['disabled'] = 'true';
        }

        return '<select'.html_attr($attr).'></select>';
    }

    public function renderStaticInput(): string
    {
        $this->setDisabled(true);

        $result = parent::renderStaticInput();

        $result .= $this->renderSelectRoles();

        return $result;
    }
}