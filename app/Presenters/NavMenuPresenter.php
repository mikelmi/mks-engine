<?php

namespace App\Presenters;


use App\Models\MenuItem;
use Illuminate\Database\Eloquent\Collection;

class NavMenuPresenter implements MenuPresenterInterface
{

    public $class_ul = 'nav'; // class for <ul>
    public $class_li = 'nav-item'; //class for ul->li
    public $class_li_deep = 'dropdown-item'; //class for li->ul->li
    public $class_li_current = 'active'; //class for current menu item
    public $class_a = 'nav-link'; //class for li->a
    public $class_li_children = 'nav-item dropdown'; //class for <li> which has children
    public $class_a_children = 'dropdown-toggle'; //class for <a> which has children
    public $class_sub_ul = 'dropdown-menu'; //class for li->ul

    /**
     * @param Collection $items
     * @param array $attrs
     * @return mixed
     */
    public function render(Collection $items, array $attrs = [])
    {
        $attributes = $attrs;

        $attributes['class'] = $this->class_ul . (isset($attributes['class']) ? ' ' . $attributes['class'] : '');

        return '<ul ' . html_attr($attributes) . '>' . $this->renderItems($items) . '</ul>';
    }

    protected function renderItems(Collection $items, &$result = '')
    {
        /** @var MenuItem $item */
        foreach ($items as $item) {
            $hasChildren = $item->hasChildren();

            $li_attr = [
                'class' => !$item->depth ? $this->class_li : $this->class_li_deep,
            ];

            $a_attr = [
                'class' => $this->class_a,
                'href' => $item->getUrl()
            ];

            if ($item->isCurrent()) {
                $li_attr['class'] .= ' ' . $this->class_li_current;
            }

            if ($hasChildren) {
                $li_attr['class'] .= ' ' . $this->class_li_children;
                $a_attr['class'] .= ' ' . $this->class_a_children;
                $a_attr = array_merge($a_attr, $this->linkWithChildrenAttr());
            }

            $result .= $this->renderItem($item, $li_attr, $a_attr) . PHP_EOL;

            if ($hasChildren) {
                $result .= '<ul class="'.$this->class_sub_ul.'">';
                $this->renderItems($item->children, $result);
                $result .= '</ul>';
            }
            $result .= '</li>';
        }

        return $result;
    }

    protected function renderItem($item, $li_attr, $a_attr)
    {
        if ($item->depth > 0) {
            return '<a '.html_attr(array_merge($a_attr, $li_attr)).'>' . e($item->title) . '</a>';
        }

        return '<li '.html_attr($li_attr).'><a '.html_attr($a_attr).'>' . e($item->title) . '</a>';
    }

    /**
     * @return array
     */
    protected function linkWithChildrenAttr()
    {
        return [
            'data-toggle' => 'dropdown',
            'role' => 'button',
            'aria-haspopup' => 'true',
            'aria-expanded' => 'false'
        ];
    }

    /**
     * @return string
     */
    public static function title()
    {
        return 'nav (' . trans('a.Vertical') . ')';
    }
}