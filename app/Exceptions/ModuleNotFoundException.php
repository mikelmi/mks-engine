<?php

namespace App\Exceptions;


class ModuleNotFoundException extends \Exception
{
    public function __construct($name, $code = 0, \Exception $previous = null)
    {
        $message = "Module '$name' not found";

        parent::__construct($message, $code, $previous);
    }
}