<?php

namespace App\Widgets;


use App\Models\Widget;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

interface WidgetInterface
{
    /**
     * @return string
     */
    public static function title();

    /**
     * @param Widget $model
     * @return mixed
     */
    public function setModel(Widget $model);

    /**
     * @return View
     */
    public function form();

    /**
     * @return array
     */
    public function rules();

    /**
     * @param Request $request
     * @return mixed
     */
    public function beforeSave(Request $request);

    /**
     * @return string
     */
    public function render();

    /**
     * @return array
     */
    public function getTemplates();
}