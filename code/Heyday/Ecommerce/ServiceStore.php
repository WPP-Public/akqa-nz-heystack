<?php

namespace Heyday\Ecommerce;

class ServiceStore
{

    private static $serviceContainer = null;

    public static function get()
    {

        return self::$serviceContainer;

    }

    public static function set($container)
    {

        self::$serviceContainer = $container;
        
    }

    public static function getService($service)
    {

        return self::$serviceContainer->get($service);

    }

}