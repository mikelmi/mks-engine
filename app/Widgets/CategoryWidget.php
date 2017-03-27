<?php

namespace App\Widgets;


use App\Models\Category;
use App\Models\Section;
use Illuminate\Support\Collection;
use Mikelmi\MksAdmin\Form\AdminModelForm;


class CategoryWidget extends NavPresenter
{

    /**
     * @return string
     */
    public function title(): string
    {
        return __('general.Categories');
    }

    /**
     * @return string
     */
    public function alias(): string
    {
        return 'category';
    }

    public function form(AdminModelForm $form, $mode = null)
    {
        $fields = [
            ['name' => 'content', 'label' => __('general.Section'), 'type' => 'select2',
                'options' => Section::pluck('title', 'id')->toArray(),
                'required' => true
            ]
        ];

        $form->addGroup('menu', [
            'title' => __('general.Categories'),
            'fields' => array_merge($fields, $this->formFields())
        ]);
    }

    public function rules():array
    {
        return [
            'content' => 'required'
        ];
    }

    /**
     * @return Collection
     */
    protected function getItems(): Collection
    {
        return Category::getTree($this->model->content);
    }
}