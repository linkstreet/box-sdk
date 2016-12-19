<?php

namespace Linkstreet\Box\Exceptions\Files;

use Linkstreet\Box\Enums\ExceptionMessages;
use Linkstreet\Box\Exceptions\BaseException;

/**
 * Class FileNotFoundException
 * @package Linkstreet\Box\Exceptions\Files
 */
class FileNotFoundException extends BaseException
{
    /**
     * FileNotFoundException constructor.
     * @param string $message
     */
    public function __construct($message = ExceptionMessages::FILE_NOT_FOUND)
    {
        parent::__construct($message, 404);
    }
}
