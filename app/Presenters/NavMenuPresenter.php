<?php

namespace App\Presenters;


use App\Models\MenuItem;
use Illuminate\Support\Collection;

class NavMenuPresenter implements MenuPresenterInterface
{
    protected $options = [
        'class_ul' => 'nav', // class for <ul>
        'class_li' => 'nav-item', //class for ul->li
        'class_li_deep' => 'dropdown-item', //class for li->ul->li
        'class_current' => 'active', //class for current menu item
        'class_a' => 'nav-link', //classfor li->a
        'class_li_children' => 'nav-item dropdown', //class for <li> which has children
        'class_a_children' => 'dropdown-toggle', //class for <a> which has children
        'class_sub_ul' => 'dropdown-menu', //class for li->ul
    ];

    public function __construct(array $options = [])
    {
        $this->options = array_merge(
            $this->options,
            array_merge(static::options(), $options)
        );
    }

    /**
     * @param Collection $items
     * @param array $attrs
     * @return mixed
     */
    public function render(Collection $items, array $attrs = [])
    {
        $attributes = $attrs;

        $attributes['class'] = $this->option('class_ul') . (isset($attributes['class']) ? ' ' . $attributes['class'] : '');

        return '<ul ' . html_attr($attributes) . '>' . $this->renderItems($items) . '</ul>';
    }

    protected function renderItems(Collection $items, &$result = '')
    {
        $class_li = $this->option('class_li');
        $class_li_deep = $this->option('class_li_deep');
        $class_a = $this->option('class_a');
        $class_current = $this->option('class_current');
        $class_li_children = $this->option('class_li_children');
        $class_a_children = $this->option('class_a_children');
        $class_sub_ul = $this->option('class_sub_ul');

        /** @var MenuItem $item */
        foreach ($items as $item) {
            $hasChildren = $item->hasChildren();

            $li_attr = [
                'class' => !$item->depth ? $class_li : $class_li_deep,
            ];

            $a_attr = [
                'class' => $class_a,
                'href' => $item->getUrl()
            ];

            if ($item->isCurrent()) {
                $a_attr['class'] .= ' ' . $class_current;
            }

            if ($hasChildren) {
                $li_attr['class'] .= ' ' . $class_li_children;
                $a_attr['class'] .= ' ' . $class_a_children;
                $a_attr = array_merge($a_attr, $this->linkWithChildrenAttr());
            }

            $result .= $this->renderItem($item, $li_attr, $a_attr) . PHP_EOL;

            if ($hasChildren) {
                $el = $item->depth > 0 ? 'div' : 'ul';
                $result .= '<' . $el . ' class="' . $class_sub_ul . '">';
                $this->renderItems($item->children, $result);
                $result .= '</' . $el . '>';
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

    public static function options()
    {
        return [];
    }

    /**
     * @param $name
     * @param null $default
     * @return mixed
     */
    public function option($name, $default = null)
    {
        return array_get($this->options, $name, $default);
    }
}