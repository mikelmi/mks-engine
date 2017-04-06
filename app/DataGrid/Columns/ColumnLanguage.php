<?php
/**
 * Author: mike
 * Date: 17.03.17
 * Time: 13:53
 */

namespace App\DataGrid\Columns;


use App\Repositories\LanguageRepository;
use Mikelmi\MksAdmin\DataGrid\Columns\Column;

class ColumnLanguage extends Column
{
    protected $cellAttributes = ['class' => 'text-center'];

    protected $headAttributes = ['class' => 'text-center'];

    protected $searchType = '';

    public function renderSearch(): string
    {
        if ($this->searchType) {
            return parent::renderSearch();
        }

        $input = '';

        if ($this->searchable) {
            $attr = [
                'st-search' => $this->key,
                'class' => 'form-control form-control-sm form-block',
                'placeholder' => $this->title,
                'mks-select',
            ];

            $input = '<select ' . html_attr($attr) . '>';
            $input .= '<option value=""></option>';

            foreach($this->getOptions() as $key => $label) {
                $input .= '<option value="' . e($key) . '">' . e($label) . '</option>';
            }

            $input .= '</select>';
        }

        return sprintf('<th>%s</th>', $input);
    }

    public function getOptions()
    {
        /** @var LanguageRepository $langRepo */
        $langRepo = app(LanguageRepository::class);

        return $langRepo->enabled()->pluck('title', 'iso');
    }

    protected function cell(): string
    {
        $v = 'row.'.$this->getKey();

        return sprintf(
            '<img ng-if="%s" ng-src="%s/{{%1$s}}" /> {{%1$s}}',
            $v,
            route('lang.icon')
        );
    }


}