<?php
/**
 * Author: mike
 * Date: 20.03.17
 * Time: 16:00
 */

namespace App\Form\Field;


use Mikelmi\MksAdmin\Form\Field;
use Mikelmi\MksAdmin\Form\FieldInterface;

class RouteLink extends Field
{
    protected $values = [];

    protected $class = 'mks-control';

    public function setValue($value): FieldInterface
    {
        parent::setValue($value);

        if (is_array($value)) {
            $this->values = $value;
        } elseif (is_string($value) && ($values = json_decode($value, true))) {
            $this->values = $values;
        }

        return $this;
    }

    protected function value($key)
    {
        return array_get($this->values, $key);
    }

    public function renderInput(): string
    {
        $attr = $this->getAttributes();

        return '<mks-link-select '.html_attr($attr).'></mks-link-select>';
    }

    protected function getDefaultAttributes(): array
    {
        $result = parent::getDefaultAttributes();

        $result['field-route'] = $this->getName(). '[route]';
        $result['field-params'] = $this->getName(). '[params]';

        $params = $this->value('params');
        $result['route'] = $this->value('route');
        $result['params'] = $params;

        if (is_string($params)) {
            $result['data-title'] = $params;
        }

        return $result;
    }


}