<?php
/**
 * Author: mike
 * Date: 17.03.17
 * Time: 18:34
 */

namespace App\Form\Field;


use Mikelmi\MksAdmin\Form\Field\Select2;

class LanguageSelect extends Select2
{
    public function __construct($name = null, $value = null, $label = null)
    {
        parent::__construct($name, $value, $label);

        if (!$label) {
            $this->label = __('general.Language');
        }
    }

    public function getUrl(): string
    {
        if (!$this->url) {
            $this->url = route('admin::language.select', $this->getValue());
        }

        return $this->url;
    }

    protected function getDefaultAttributes(): array
    {
        $result = parent::getDefaultAttributes();

        if ($url = $this->getUrl()) {
            $result['data-url'] = $url;
        }

        $result['data-lang-icon'] = route('lang.icon');

        return $result;
    }


}