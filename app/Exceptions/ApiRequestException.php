<?php

namespace App\Exceptions;

use Exception;


class ApiRequestException extends Exception
{

    protected $errors = [];
    protected $errorCode;

    public function __construct(array $errors = [], $code = 0)
    {
        $this->errors = $errors;
        $this->errorCode = $code;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getErrorCode()
    {
        return $this->errorCode;
    }

    public function render($errors, $code)
    {
        return api()->fail($errors)->status($code)->toJson();
    }
}
