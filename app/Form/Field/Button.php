<?php
/**
 * Author: mike
 * Date: 11.05.17
 * Time: 18:31
 */

namespace App\Form\Field;


use Mikelmi\MksAdmin\Form\Field\Custom;

class Button extends Custom
{
    protected $class = 'btn btn-primary';

    /**
     * @var string
     */
    protected $icon = '';

    /**
     * @param string $icon
     * @return Button
     */
    public function setIcon(string $icon): Button
    {
        $this->icon = $icon;
        return $this;
    }

    public function renderInput(): string
    {
        $attr = $this->getAttributes();

        $attr['type'] = 'button';

        $label = $this->label;

        if ($this->icon) {
            $label = '<i class="fa fa-' . $this->icon . '"></i> ';
        }

        return '<button ' . html_attr($attr) . '>'.$label.'</button>';
    }

    public function getLabel(): string
    {
        return '';
    }

}