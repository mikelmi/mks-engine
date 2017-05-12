<?php

namespace App\Widgets;


use Mikelmi\MksAdmin\Form\AdminModelForm;

class HtmlWidget extends TextWidget
{
    /**
     * @return string
     */
    public function title(): string
    {
        return __('general.HtmlWidget');
    }

    public function alias(): string
    {
        return 'html';
    }

    public function form(AdminModelForm $form, $mode = null)
    {
        $form->addGroup('text', [
            'title' => $this->title(),
            'fields' => [
                ['name' => 'content', 'label' => __('general.Text'), 'type' => 'editor', 'rows' => 5]
            ]
        ]);
    }
}