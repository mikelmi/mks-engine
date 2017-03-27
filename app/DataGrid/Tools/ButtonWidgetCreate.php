<?php
/**
 * Author: mike
 * Date: 24.03.17
 * Time: 14:09
 */

namespace App\DataGrid\Tools;


use App\Services\WidgetManager;

class ButtonWidgetCreate extends GridDropDownButton
{
    public function __construct($url = '', $title = null, $btnType = null, $icon = null)
    {
        parent::__construct($url, $title ?: __('admin::messages.Add'), $btnType ?: 'primary', $icon);

        /** @var WidgetManager $widgetManager */
        $widgetManager = resolve(WidgetManager::class);

        foreach ($widgetManager->getPresentersList() as $class => $title) {
            $this->addItem([
                'url' => hash_url('widget/create', urlencode($class)),
                'title' => $title
            ]);
        }
    }

}