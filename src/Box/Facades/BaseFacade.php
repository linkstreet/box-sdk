<?php

namespace Linkstreet\Box\Facades;

/**
 * Class BaseFacade
 * @package Linkstreet\Box\Facades
 */
class BaseFacade
{

    /**
     * @param $method
     * @param $args
     * @return mixed
     */
    public static function __callStatic($method, $args)
    {

        $invoked_class = get_called_class();

        $invoked_class::initialize();

        switch (count($args)) {
            case 0:
                return $invoked_class::$instance->$method();

            case 1:
                return $invoked_class::$instance->$method($args[0]);

            case 2:
                return $invoked_class::$instance->$method($args[0], $args[1]);

            case 3:
                return $invoked_class::$instance->$method($args[0], $args[1], $args[2]);

            case 4:
                return $invoked_class::$instance->$method($args[0], $args[1], $args[2], $args[3]);

            default:
                return call_user_func_array([$invoked_class::$instance, $method], $args);
        }
    }
}
