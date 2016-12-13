<?php

namespace Box\Exceptions\Files;

use Box\Enums\ExceptionMessages;
use Box\Exceptions\BaseException;

class FileNotFoundException extends BaseException
{
    public function __construct($message = ExceptionMessages::FILENOTFOUND)
    {
        parent::__construct($message, 404);
    }
}
