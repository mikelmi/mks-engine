<?php
/**
 * Author: mike
 * Date: 17.03.17
 * Time: 18:49
 */

namespace App\Form\Field;


use Mikelmi\MksAdmin\Form\Field\Text;

class CheckedInput extends Text
{
    /**
     * @var string
     */
    protected $addon = '';

    /**
     * @var string
     */
    protected $ngModel = '';

    /**
     * @var bool
     */
    protected $checked = false;

    /**
     * @return bool
     */
    public function isChecked(): bool
    {
        return $this->checked;
    }

    /**
     * @param bool $checked
     * @return CheckedInput
     */
    public function setChecked(bool $checked): CheckedInput
    {
        $this->checked = $checked;
        return $this;
    }

    /**
     * @return string
     */
    public function getAddon(): string
    {
        return $this->addon;
    }

    /**
     * @param string $addon
     * @return CheckedInput
     */
    public function setAddon(string $addon): CheckedInput
    {
        $this->addon = $addon;
        return $this;
    }

    /**
     * @param string $ngModel
     * @return CheckedInput
     */
    public function setNgModel(string $ngModel): CheckedInput
    {
        $this->ngModel = $ngModel;
        return $this;
    }

    public function getNgModel()
    {
        if (!$this->ngModel) {
            $this->ngModel = 'checkedInput'.uniqid();
        }

        return $this->ngModel;
    }

    public function getDefaultAttributes(): array
    {
        $result = parent::getDefaultAttributes();

        $result['ng-disabled'] = "!{$this->getNgModel()}";

        if ($this->isChecked()) {
            $result['ng-init'] = "{$this->getNgModel()}=true";
        }

        return $result;
    }

    public function renderInput(): string
    {
        $input = parent::renderInput();

        return view('admin.form.field.checked-input', ['field' => $this, 'input' => $input])->render();
    }


}