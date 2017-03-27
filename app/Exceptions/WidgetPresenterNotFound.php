<?php
/**
 * Author: mike
 * Date: 24.03.17
 * Time: 18:57
 */

namespace App\Exceptions;


class WidgetPresenterNotFound extends \Exception
{
    public function __construct($name, $code = 0, \Exception $previous = null)
    {
        $message = "Widget Presenter '$name' not found";

        parent::__construct($message, $code, $previous);
    }
}