<?php
/**
 * Author: mike
 * Date: 27.05.17
 * Time: 12:53
 */

namespace App\Form\Field;


use Mikelmi\MksAdmin\Form\Field\Toggle;

class ToggleDefault extends Toggle
{
    /**
     * @return bool
     */
    public function isDefault(): bool
    {
        return is_null($this->value) || $this->value === '';
    }

    public function isOff(): bool
    {
        return !$this->isDefault() && parent::isOff();
    }

    /**
     * @return string
     */
    public function renderInput(): string
    {
        if ($this->isDisabled()) {
            return $this->renderDisabledInput();
        }

        return sprintf(
            '<div class="toggle-control">
                <div class="btn-group btn-group-sm" data-toggle="buttons">
                    <label class="btn btn-outline-secondary%s">
                        <input type="radio" name="'.$this->name.'" autocomplete="off" value=""%s>
                        %s
                    </label>
                    <label class="btn btn-outline-success%s">
                        <input type="radio" name="'.$this->name.'" autocomplete="off" value="%s"%s>
                        %s
                    </label>
                    <label class="btn btn-outline-danger%s">
                        <input type="radio" name="'.$this->name.'" autocomplete="off" value="%s"%s>
                        %s
                    </label>
                </div>
            </div>',

            $this->isDefault() ? ' active' : ' ',
            $this->isDefault() ? ' checked' : '',
            $this->getDefaultTitle(),

            $this->isOn() ? ' active' : '',
            $this->onValue,
            $this->isOn() ? ' checked' : '',
            $this->getOnTitle(),

            $this->isOff() ? ' active' : ' ',
            $this->offValue,
            $this->isOff() ? ' checked' : '',
            $this->getOffTitle()
        );
    }

    protected function renderDisabledInput(): string
    {
        return sprintf(
            '<h5 class="pt-1"><span class="badge badge-%s">%s</span></h5>',
            $this->isDefault() ? 'default' : ($this->isOn() ? 'success' : 'danger'),
            $this->isDefault() ? $this->getDefaultTitle() : ($this->isOn() ? $this->getOnTitle() : $this->getOffTitle())
        );
    }

    public function getDefaultTitle(): string {
        return __('general.Default');
    }
}