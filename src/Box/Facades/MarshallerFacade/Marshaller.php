<?php

namespace Box\Facades\MarshallerFacade;

use Box\Marshallers\BaseMarshaller;

class Marshaller extends BaseFacade
{

    public static $instance = null;

    protected static function initialize()
    {
        if (self::$instance == null) {
            self::$instance  = new BaseMarshaller();
        }
    }
}
