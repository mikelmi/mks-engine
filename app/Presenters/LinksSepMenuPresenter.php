<?php

namespace App\Presenters;


use App\Contracts\NestedMenuInterface;
use Illuminate\Support\Collection;

class LinksSepMenuPresenter extends LinksMenuPresenter
{
    /**
     * @return string
     */
    public static function title()
    {
        return 'links (with separator)';
    }

    protected function renderItems(Collection $items, &$result = '')
    {
        $count = $items->count();
        /** @var NestedMenuInterface $item */
        foreach ($items as $i => $item) {

            $attrs = [
                'href' => $item->getUrl(),
            ];

            if ($item->isCurrent()) {
                $attrs['class'] = 'active';
            }

            $sep = null;

            if ($i < $count-1) {
                $sep = ' <span>|</span> ';
            }

            $result .= '<a ' . html_attr($attrs) . '>' . $item->getTitle() . $sep . '</a>';
        }

        return $result;
    }
}