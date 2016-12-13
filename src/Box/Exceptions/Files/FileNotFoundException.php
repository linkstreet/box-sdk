<?php

namespace Box\Exceptions\Files;

use Box\Enums\ExceptionMessages;
use Box\Exceptions\BaseException;

/**
 * Class FileNotFoundException
 * @package Box\Exceptions\Files
 */
class FileNotFoundException extends BaseException
{
    public function __construct($message = ExceptionMessages::FILENOTFOUND)
    /**
     * FileNotFoundException constructor.
     * @param string $message
     */
    {
        parent::__construct($message, 404);
    }
}
