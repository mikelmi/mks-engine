<?php

namespace App\Widgets;


use App\Models\Widget;
use Illuminate\Http\Request;

abstract class WidgetBase implements WidgetInterface
{
    private $attr = [];
    /**
     * @var Widget
     */
    protected $model;

    public function setModel(Widget $model)
    {
        $this->model = $model;
        $this->attr = $model->getHtmlAttributes();
    }

    public function rules()
    {
        return [];
    }
    
    public function getGeneralAttributes($clear = false)
    {
        if ($clear) {
            $result = $this->attr;
            $this->attr = [];
            return $result;
        }

        return $this->attr;
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
            'template' => $this->model->param('template', 'empty')
        ], $data);

        if (!$vars['template'] || !\View::exists('widget.'.$vars['template'])) {
            $vars['template'] = 'empty';
        }

        $attr = $this->getGeneralAttributes();

        if ($vars['template'] != 'empty') {
            $attr['class'] = (isset($attr['class']) ? $attr['class'] . ' ' : '') . 'card widget';
        }

        $vars['attr'] = $attr;

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