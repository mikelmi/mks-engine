<?php
/**
 * Author: mike
 * Date: 17.03.17
 * Time: 16:31
 */

namespace App\DataGrid\Actions;


use Mikelmi\MksAdmin\DataGrid\Actions\Action;

class ToggleOne extends Action
{
    protected $icon = 'star-o';

    protected $iconOn = 'star';

    protected $btnType = 'outline-secondary no-b';

    /**
     * @var string
     */
    protected $key = '';

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @param string $key
     * @return ToggleOne
     */
    public function setKey(string $key): ToggleOne
    {
        $this->key = $key;
        return $this;
    }

    /**
     * @return null|string
     */
    public function getTitle()
    {
        if ($this->title === null) {
            $this->title = __('admin::messages.Default');
        }

        return $this->title;
    }

    /**
     * @return string
     */
    public function getOnClick(): string
    {
        if ($this->onClick === null) {
            $this->onClick = sprintf("grid.updateRow(row, '%s/'+row.id, '%s')", $this->url, $this->getConfirm());
        }

        return $this->onClick;
    }

    protected function defaultAttributes(): array
    {
        $result = parent::defaultAttributes();

        if ($key = $this->getKey()) {
            $v = 'row.' . $key;
            $result['ng-class'] = "{'text-warning': $v}";
            $result['ng-disabled'] = $v;
        }

        return $result;
    }

    /**
     * @return string
     */
    public function getIconOn(): string
    {
        return $this->iconOn;
    }

    /**
     * @param string $iconOn
     * @return ToggleOne
     */
    public function setIconOn(string $iconOn): ToggleOne
    {
        $this->iconOn = $iconOn;
        return $this;
    }

    protected function iconHtml(): string
    {
        $attr = ['class' => 'fa'];
        if ($key = $this->getKey()) {
            $v = 'row.' . $key;
            $attr['ng-class'] = "{'fa-{$this->iconOn}': $v, 'fa-{$this->icon}': !{$v}}";
        }

        return '<i'.html_attr($attr).'></i>';
    }


}