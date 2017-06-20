<?php
/**
 * Author: mike
 * Date: 27.05.17
 * Time: 19:56
 */

namespace App\Widgets;


use Mikelmi\MksAdmin\Form\AdminModelForm;

class SliderWidget extends WidgetPresenter
{

    /**
     * @return string
     */
    public function title(): string
    {
        return __('general.SliderWidget');
    }

    /**
     * @return string
     */
    public function alias(): string
    {
        return 'slider';
    }

    public function form(AdminModelForm $form, $mode = null)
    {
        $form->addGroup('slider', [
            'title' => __('general.Slides'),
            'fields' => [
                ['name' => 'params[slides]', 'nameSce' => 'params.slides', 'label' => ' ', 'type' => 'custom',
                    'view' => 'admin.form.field.slides',
                    'layout' => 'default',
                    'value' => $this->model->param('slides', json_encode([]))
                ],
            ]
        ]);
    }
}