<?php

namespace Linkstreet\Box\Exceptions;

use Exception;

/**
 * Class BaseException
 * @package Linkstreet\Box\Exceptions
 */
class BaseException extends Exception
{

    /**
     * BaseException constructor.
     * @param string $message
     * @param int $code
     */
    public function __construct($message, $code)
    {
        parent::__construct($message, $code);
    }
}
