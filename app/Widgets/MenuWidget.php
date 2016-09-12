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
}