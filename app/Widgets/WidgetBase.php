<?php

namespace App\Widgets;


use App\Models\Widget;
use Illuminate\Http\Request;

abstract class WidgetBase implements WidgetInterface
{
    /**
     * @var Widget
     */
    protected $model;

    public function setModel(Widget $model)
    {
        $this->model = $model;
    }

    public function rules()
    {
        return [];
    }

    abstract public function beforeSave(Request $request);

    public function render()
    {
        return '';
    }

    public function view($name, array $data = [])
    {
        $vars = array_merge([
            'model' => $this->model,
            'template' => $this->model->param('template', 'empty'),
        ], $data);

        if (!$vars['template'] || !\View::exists('widget.'.$vars['template'])) {
            $vars['template'] = 'empty';
        }

        return view($name, $vars);
    }

    public function getTemplates()
    {
        return [
            '' => trans('general.Empty'),
            'block' => trans('general.Block'),
        ];
    }
}