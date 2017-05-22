<?php
/**
 * Author: mike
 * Date: 12.05.17
 * Time: 19:41
 */

namespace App\DataGrid\Columns;


use App\Services\CategoryManager;

class ColumnCategory extends ColumnList
{
    /**
     * @var string
     */
    protected $categoryType = '';

    /**
     * @param string $categoryType
     * @return ColumnCategory
     */
    public function setCategoryType(string $categoryType): ColumnCategory
    {
        $this->categoryType = $categoryType;
        return $this;
    }

    public function renderSearch(): string
    {
        if ($this->searchType) {
            return parent::renderSearch();
        }

        $input = '';

        if ($this->searchable) {
            $attr = [
                'st-search' => $this->searchKey ?: $this->key,
                'class' => 'form-control form-control-sm form-block',
                'placeholder' => $this->title,
                'mks-select',
            ];

            $input = '<select ' . html_attr($attr) . '>';
            $input .= '<option value=""></option>';

            /** @var CategoryManager $cm */
            $cm = resolve(CategoryManager::class);

            $options = $cm->getSelectOptionsFlat($this->categoryType);

            foreach ($options as $section => $categories) {
                $input .= '<optgroup label="' . e($section) . '">';

                foreach($categories as $category) {
                    $input .= '<option value="' . $category['id'] . '">' . e($category['text']) . '</option>';
                }

                $input .= '</optgroup>';
            }



            $input .= '</select>';
        }

        return sprintf('<th>%s</th>', $input);



        return parent::renderSearch();
    }

}