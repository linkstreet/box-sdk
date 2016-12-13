<?php

namespace Box\Exceptions;

use Exception;

class BaseException extends Exception
{

    public function __construct($message, $code)
    {
        parent::__construct($message, $code);
    }
}
