<?php

namespace App\Presenters;


use App\Models\Language;
use Illuminate\Support\Collection;

class SelectLanguagesPresenter extends NavLanguagesPresenter
{
    public function render(Collection $items, array $attrs = [])
    {
        if ($items->count() == 0) {
            return '';
        }
        
        $attributes = $attrs;

        $attributes['class'] = $this->option('class_ul') . (isset($attributes['class']) ? ' ' . $attributes['class'] : '');

        $attributes['onchange'] = 'location.href=\'' .route('language.change'). '/\'+this.value;';

        return '<select ' . html_attr($attributes) . '>' . $this->renderItems($items) . '</select>';
    }
    
    protected function renderItems(Collection $items, &$result = '')
    {
        /** @var Language $item */
        foreach ($items as $item) {

            $attrs = [
                'value' => $item->getIso(),
            ];

            if ($item->getIso() === $this->locale) {
                $attrs['selected'] = 'selected';
            }

            $result .= '<option ' . html_attr($attrs) . '>' . $item->getTitle() . '</option>';
        }

        return $result;
    }

    /**
     * @param Language $item
     * @param $li_attr
     * @param $a_attr
     * @return string
     */
    protected function renderItem($item, $li_attr, $a_attr)
    {
        $icon = null;

        if ($this->withIcons) {
            $icon = $item->iconImage() . ' ';
        }

        return '<a '.html_attr($a_attr).'>' . $icon . e($item->getTitle()) . '</a>';
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