<?php

namespace App\Widgets;


use App\Models\Category;
use App\Models\Section;
use App\Presenters\SelectMenuPresenter;
use Illuminate\Http\Request;

class CategoryWidget extends MenuWidget implements WidgetInterface
{

    /**
     * @return string
     */
    public static function title()
    {
        return trans('general.Categories');
    }

    public function form()
    {
        return view('admin.widget.form.category', [
            'model' => $this->model,
            'sections' => Section::pluck('title', 'id'),
            'presenters' => $this->getPresentersList()
        ]);
    }

    public function beforeSave(Request $request)
    {
        $this->model->content = $request->input('content');
    }

    public function render()
    {
        if (!$this->model->content) {
            return;
        }

        $type = array_get($this->presenters, $this->model->param('type', ''));

        $presenter = $this->makePresenter($type);

        if ($presenter instanceof SelectMenuPresenter) {
            $items = Category::getFlatTree($this->model->content);
        } else {
            $items = Category::getTree($this->model->content);
        }

        $items = $presenter->render($items, ['class' => $this->model->param('css_class')]);

        return $this->view('widget.menu', [
            'items' => $items
        ])->render();
    }

    public function rules()
    {
        return [
            'content' => 'required'
        ];
    }
}