<?php

namespace App\Presenters;

use App\Contracts\NestedMenuInterface;
use Illuminate\Support\Collection;

class SelectMenuPresenter extends NavMenuPresenter
{
    public function render(Collection $items, array $attrs = [])
    {
        if ($items->count() == 0) {
            return '';
        }

        $attributes = $attrs;

        $attributes['class'] = $this->option('class_ul') . (isset($attributes['class']) ? ' ' . $attributes['class'] : '');

        $attributes['onchange'] = 'location.href=this.value;';

        return '<select ' . html_attr($attributes) . '>' . $this->renderItems($items) . '</select>';
    }

    protected function renderItems(Collection $items, &$result = '')
    {
        /** @var NestedMenuInterface $item */
        foreach ($items as $item) {

            $attrs = [
                'value' => $item->getUrl(),
            ];

            if ($item->isCurrent()) {
                $attrs['selected'] = 'selected';
            }

            $result .= '<option ' . html_attr($attrs) . '>' . str_repeat('-', $item->getDepth()) . $item->getTitle() . '</option>';
        }

        return $result;
    }

    public static function title()
    {
        return 'select';
    }

    public static function options()
    {
        return [
            'class_ul' => 'form-control',
        ];
    }
}