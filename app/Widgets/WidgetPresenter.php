<?php
/**
 * Author: mike
 * Date: 24.03.17
 * Time: 17:28
 */

namespace App\Widgets;


use App\Models\Widget;
use Illuminate\Http\Request;
use Mikelmi\MksAdmin\Form\AdminModelForm;

abstract class WidgetPresenter implements \App\Contracts\WidgetPresenter
{
    /**
     * @var Widget
     */
    protected $model;

    /**
     * @var string
     */
    protected $viewPrefix = '';

    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * @param Widget $model
     */
    public function setModel(Widget $model)
    {
        $this->model = $model;
        $this->setAttributes($model->getHtmlAttributes());
    }

    /**
     * @return Widget
     */
    public function getModel(): Widget
    {
        return $this->model;
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @param array $attributes
     */
    public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [];
    }

    /**
     * @param AdminModelForm $form
     * @param null $mode
     */
    public function form(AdminModelForm $form, $mode = null)
    {

    }

    /**
     * @param Request $request
     */
    public function beforeSave(Request $request)
    {

    }

    /**
     * @return string
     */
    abstract public function title(): string;

    /**
     * @return string
     */
    abstract public function alias(): string;

    /**
     * @param null $path
     * @param array $data
     * @return \Illuminate\View\View
     */
    protected function view($path = null, array $data = [])
    {
        $vars = [
            'model' => $this->model,
            'template' => $this->model->param('in_block') ? 'widget.block' : 'widget.empty',
            'title' => $this->model->param('show_title') ? $this->model->title : null,
        ];

        if (($name = $this->model->name) && \View::exists($this->viewPrefix . 'widget.named.' . $name)) {
            $view = $this->viewPrefix . 'widget.named.' . $name;
        } else {
            $view = $path ? $path : $this->viewPrefix . 'widget.' . $this->alias();
        }

        $attr = $this->getAttributes();

        if ($this->model->param('in_block')) {
            $attr['class'] = trim('card widget ' . array_get($attr, 'class'));
        }

        $vars['attr'] = $attr;

        return view($view, $vars, $data);
    }

    /**
     * @return string
     */
    public function render(): string
    {
        return $this->view()->render();
    }
}