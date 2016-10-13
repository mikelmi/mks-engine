<?php

namespace App\Presenters;


use App\Models\Language;
use App\Services\LanguageManager;
use Illuminate\Support\Collection;

class NavLanguagesPresenter extends NavMenuPresenter
{
    protected $locale;

    protected $withIcons = true;
    
    public function __construct(array $options = [])
    {
        parent::__construct($options);
        
        $this->locale = $this->option('locale');
        $this->withIcons = $this->option('icons');
    }

    /**
     * @param Collection $items
     * @param string $result
     * @return string
     */
    protected function renderItems(Collection $items, &$result = '')
    {
        $class_li = $this->option('class_li');
        $class_current = $this->option('class_current');
        $class_a = $this->option('class_a');


        /** @var Language $item */
        foreach ($items as $item) {

            $li_attr = [
                'class' => $class_li
            ];

            $a_attr = [
                'class' => $class_a,
                'href' => route('language.change', $item->getIso())
            ];

            if ($item->getIso() === $this->locale) {
                $a_attr['class'] .= ' ' . $class_current;
            }

            $result .= $this->renderItem($item, $li_attr, $a_attr) . PHP_EOL;
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

        return '<li '.html_attr($li_attr).'><a '.html_attr($a_attr).'>' . $icon . e($item->getTitle()) . '</a></li>';
    }

    /**
     * @return Language|null
     */
    protected function getCurrentLanguage()
    {
        if ($this->locale) {
            return app(LanguageManager::class)->get($this->locale);
        }

        return null;
    }
}