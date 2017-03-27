<?php
/**
 * Author: mike
 * Date: 24.03.17
 * Time: 17:22
 */

namespace App\Contracts;


use App\Models\Widget;
use Illuminate\Http\Request;
use Mikelmi\MksAdmin\Form\AdminModelForm;

interface WidgetPresenter
{
    /**
     * WidgetPresenter constructor.
     * @param Widget $model
     */
    public function setModel(Widget $model);

    /**
     * @return Widget
     */
    public function getModel(): Widget;

    /**
     * @return string
     */
    public function title(): string;

    /**
     * @return string
     */
    public function alias(): string;

    /**
     * @return array
     */
    public function rules(): array;

    /**
     * @param AdminModelForm $form
     * @param null $mode
     */
    public function form(AdminModelForm $form, $mode = null);

    /**
     * @param Request $request
     */
    public function beforeSave(Request $request);

    /**
     * @return string
     */
    public function render(): string;

    /**
     * @param array $attributes
     */
    public function setAttributes(array $attributes);

    /**
     * @return array
     */
    public function getAttributes(): array;
}