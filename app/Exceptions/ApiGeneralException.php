<?php

namespace App\Exceptions;

use Exception;
use Throwable;

class ApiGeneralException extends Exception
{
    public function __construct($message = "", $code = 400)
    {
        parent::__construct($message, $code);
    }

    public function render($message, $code)
    {
        return api()->fail($message)->status($code)->toJson();
    }
}
