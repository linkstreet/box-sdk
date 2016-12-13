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
    /**
     * FileNotFoundException constructor.
     * @param string $message
     */
    public function __construct($message = ExceptionMessages::FILE_NOT_FOUND)
    {
        parent::__construct($message, 404);
    }
}
