<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 12.09.16
 * Time: 11:49
 */

namespace App\Widgets;


use App\Models\Menu;
use Illuminate\Http\Request;

class MenuWidget extends WidgetBase implements WidgetInterface
{

    /**
     * @return string
     */
    public static function title()
    {
        return trans('a.Menu');
    }

    public function form()
    {
        $menu = Menu::orderBy('name')->get();

        return view('admin.widget.form.menu', ['model' => $this->model, 'menu' => $menu]);
    }

    public function rules()
    {
        return [
            'content' => 'required'
        ];
    }

    public function beforeSave(Request $request)
    {
        $this->model->content = $request->input('content');
    }

    public function getTemplates()
    {
        $result = []; //parent::getTemplates();

        $result['horizontal'] = trans('a.Horizontal');
        $result['vertical'] = trans('a.Vertical');
        $result['vertical2'] = trans('a.Vertical2');

        return $result;
    }
    
    public function render()
    {
        $template = $this->model->param('template');

        if (!$template || !in_array($template, $this->getTemplates())) {
            $template = 'horizontal';
        }

        return $this->view('widget.menu.'.$template)->render();
    }
}