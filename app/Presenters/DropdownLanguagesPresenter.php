<?php

namespace App\Presenters;


use App\Models\Language;
use Illuminate\Support\Collection;

class DropdownLanguagesPresenter extends NavLanguagesPresenter
{
    public function render(Collection $items, array $attrs = [])
    {
        if ($items->count() == 0) {
            return '';
        }
        
        $attributes = $attrs;

        $attributes['class'] = $this->option('class_ul') . (isset($attributes['class']) ? ' ' . $attributes['class'] : '');

        $result = '<ul ' . html_attr($attributes) . '>';

        $result .= '<li class="' . $this->option('class_li_children') . '">';

        $aClass = $this->option('class_a') . ' ' .$this->option('class_a_children');

        $result .= '<a href="#" class="'. $aClass .'" data-toggle="dropdown" role="button" aria-expanded="false">';

        $language = $this->getCurrentLanguage();
        if (!$language) {
            $language = $items->first();
        }

        $result .= $language->iconImage() . ' ' . $language->getTitle();
        
        $result .= '</a>';

        $dropdownClass = $this->option('class_sub_ul');

        if (strpos($attributes['class'], 'right') !== false) {
            $dropdownClass .= ' dropdown-menu-right';
        }

        return $result . '<div class="'.$dropdownClass.'">' . $this->renderItems($items) . '</div></ul>';
    }
    
    protected function renderItems(Collection $items, &$result = '')
    {
        $class_a = $this->option('class_li_deep', 'dropdown-item');
        $class_current = $this->option('class_current');

        /** @var Language $item */
        foreach ($items as $item) {
            $a_attr = [
                'class' => $class_a,
                'href' => route('language.change', $item->getIso())
            ];

            if ($item->getIso() === $this->locale) {
                $a_attr['class'] .= ' ' . $class_current;
            }

            $result .= $this->renderItem($item, [], $a_attr) . PHP_EOL;
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
        return 'dropdown';
    }
}