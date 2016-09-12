<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 12.09.16
 * Time: 13:48
 */

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
}